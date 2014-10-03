<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*Authority Class by Steven Yang*/
class Auth
{
	var $id;
	var $_CI;
	var $data = array(
					  'mtype' => 'all',
					  'mid'   => 0,
					  'ntype' => 'all',
					  'nid'   => 0
					  );
	var $op = '';
	var $_override = FALSE;
	var $kv = FALSE;

	function __construct($id = NULL)
	{
		//get CI super class
		$this->_CI = & get_instance();
		
		//set uid
		if (!isset($id))
			$this->id = $this->_CI->session->userdata('uid');
		else
			$this->id=$id;
		
		//get uri string
		$uri = trim($this->_CI->uri->uri_string(),'/');

		//generate uri information
		$arr_uri  = explode('/',$uri);
		
		//load config file
		$this->_CI->config->load('permission');
		
		//filter out basic uris
		if (in_array($arr_uri[0],$this->_CI->config->item('overrides')))
			$this->_override=TRUE;
		else
		{
			//generate method
			$this->op='lr';//@todo
			switch(end($arr_uri))
			{
				case 'get':
					array_pop($arr_uri);
				break;
				case 'create':
					$this->op='c';
					array_pop($arr_uri);
				break;
				case 'delete':
					$this->op='d';
					array_pop($arr_uri);
				break;
				case 'edit':
					$this->op='u';
					 array_pop($arr_uri);
				break;
			}
			//generate permission input
			foreach ($this->data as $i=>$item)
			{
				$piece=array_shift($arr_uri);
				if ($piece == $item)
					break;
				else
					$this->data[$i]=$piece;
			}
			
			/*
			if ($this->op='get')
				if (($this->data['mid']==0) || ($this->data['nid']==0))
					$this->op='l';
				else
					$this->op='lr';
			*/
		}
	}
	
	public function set($op, $mtype = 'all' , $mid = 0, $ntype = 'all', $nid = 0, $clean = FALSE)
	{
		//default
		if ($clean===TRUE)
		{
			$this->data = array(
						'mtype' => 'all',
						'mid'   => 0,
						'ntype' => 'all',
						'nid'   => 0
						);
			$this->op = '';
			$this->_override = FALSE;
			$this->kv = FALSE;
		}
		
		if (in_array($op,array('l','c','u','r','d')))
			$this->op=$op;
		else
		{
			$this->_CI->errorhandler->setError(1010101,'Wrong Input');
			return FALSE;
		}
		
		//check input
		if  ($mtype=='all')
		{
			$mid=0;
			$ntype='all';
			$nid=0;
		}
		elseif ($mid==0)
		{
			$ntype='all';
			$nid=0;
		}
		else
		{
			if ($ntype=='all')
			{
				$nid=0;
			}
		}
		$this->data = array(
						   'mtype' => $mtype,
						   'mid'   => $mid,
						   'ntype' => $ntype,
						   'nid'   => $nid
							);
		return TRUE;
	}
		
	public function get_permission()
	{
		//Root can do everything~~~
		if ($this->id==0) 
			return TRUE;
			
		if ($this->_override)
			return TRUE;
		
		$uri=$this->data['mtype'].'/'.$this->data['mid'].'/'.$this->data['ntype'].'/'.$this->data['nid'];
			
		//check the cache(kvdb version)
		$this->kv = new SaeKV();
		if (! $this->kv->init())
			throw new MY_Exception('KVDB failed!');
		
		//@todo create different cache
		$cache = $this->kv->get($this->id.':auth:'.$uri);
		
		if ($cache !== FALSE)//if cache exist
		{
			if ($this->str_ck($cache,$this->op))
				return TRUE;
			else
				return FALSE;
		}
		else //no cache exist, create SQL
		{
			$this->_CI->db->select('uid,permi');
			$where=array(
						'mtype' => array('all' , $this->data['mtype']),
						'mid'   => array(0     , $this->data['mid']),
						'ntype' => array('all' , $this->data['ntype']),
						'nid'   => array(0     , $this->data['nid'])
						);
			foreach ($where as $i=>$item)
			{
				$q=array_unique($item);
				$this->_CI->db->where_in($i,$q);
			}
			
			//@todo
			$this->_CI->db->where_in('uid',array($this->id,0));
			//uid=0 is a user, can represent every one
			//permission table record the permission of every user, therefore, it's not necessary to check the authority of everyone
			//$this->_CI->db->where('uid',$this->id);
			
			$this->_CI->db->order_by('nid DESC, mid DESC, ntype DESC, mtype DESC, uid DESC');
			$querry_permission=$this->_CI->db->get('permission')->result_array();//get permission information from the database
			
			//check whether there are matched permission information
			if (count($querry_permission) == 0)
				$querry_permission[0]['permi']='-----';
			
			//generate overall permission string
			$permission='';
			foreach ($querry_permission as $item)
			{
				$permission=$permission.$item['permi'];	
			}
						
			//check permission(calculate all permission)
			//@todo whether calculate all permission?
			//calculate five types of permission and create cache
			$cache='';
			foreach (array('l','c','u','r','d') as $item)
			{
				if ($this->str_ck($permission,$item))
					$cache=$cache.$item;
				else
					$cache=$cache.'-';
			}
						
			//update cache
			//kvdb version
<<<<<<< HEAD
			if (! $this->kv->set($this->id.':auth:'.$uri,$cache)){
				log_message('error',"KVDB failed to update cache.".$this->kv->errno().';'.$this->kv->errmsg());			
			}
=======
			if (! $this->kv->set($this->id.':auth:'.$uri,$cache))
				throw new MY_Exception("KVDB failed!");			
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
			
			//return result
			if ($this->str_ck($cache,$this->op))
				return TRUE;
			else
			{
				return FALSE;
			}
		}
	}
	
	/**
	 * Check authority string
	 *
	 * @param string $str the tested string
	 * @param string $auth the authority, if blanked, it would be a test for whether the $str is a valid authority string
	 * @examples:
	 		test the validity of $str:
				$str_ck('l')           => TRUE;
				$str_ck('lc')          => TRUE;
				$str_ck('lcasdf?!@#$') => TRUE;//@bugs?
			check whether $str has authority under $auth:
				$str_ck('l','lcurd')  => TRUE;
				$str_ck('lc','l-urd') =>FALSE;      
	 */
	function str_ck($str, $auth = 'lcurd')
	{
		//check input
		if (strpos($auth,'-')!==FALSE)//@todo
			return FALSE;
		
		foreach (array('l','c','u','r','d') as $item)
		{
			if (strpos($auth,$item)!==FALSE)
				if (! (strpos($str,$item)!==FALSE))
					return FALSE;				
		}
		return TRUE;
	}
}