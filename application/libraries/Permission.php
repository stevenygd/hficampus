<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*Authority Class by Steven Yang*/
class Permission{
	
	var $_db;
	var $_CI;
	
	function __construct()
	{
		//get CI super class
		$this->_CI = & get_instance();
		$this->_db = $this->_CI->db;

	}
	
	public function syn($uid)
	{
		//check input@todo
		if ($this->_db->get_where('user',array('id'=>$uid))->num_rows()!=1)
		{
			return FALSE;
		}
		if ($uid == 0)
		{
			return TRUE;
		}
		
		//get all the permission for backup
		$recent_permission=$this->_db->get_where('permission',array('uid'=>$uid))->result_array();
		
		/**  clean old records **/
		
		//delete all recent permission records
		$this->_db->where('uid',$uid);
		$this->_db->delete('permission');
		
		//delete all KVDB
		
		// 初始化key-values
		$kv = new SaeKV();
		if ($kv->init()===FALSE)
		{
			return FALSE;
		}
		
		// 循环获取所有符合的key-values
		$ret = $kv->pkrget($uid.':auth:', 100);
		while (true) {
			end($ret);
			$start_key = key($ret);
			$i = count($ret);
			if ($i < 100) break;
			$ret = $kv->pkrget($uid.':auth:', 100, $start_key);
		}
		
		// 循环删除所有符合的key-values
		foreach ($ret as $i=>$item)
		{
			if ($kv->delete($i)===FALSE)
				return FALSE;
		}
		
		/** calculate recend permission **/
		 
		/**
		 * get auth permission infor
		 * $subs  the array that contains all the group id that the user subjects to
		 * $auths the array that contains all the authority information
		   $auth['URI']=array([0]=>array(auth_info),[1]=>array(another_auth_info),...)
		 * $indipermi the array that contains all additional user authorization
		   $indipermi['URI']=array([0]=>array(auth_info),[1]=>array(another_auth_info),...);
		 * $uris the array that contains all the uris that the user should have
		 * 优化：等权限URI？
		 */
		try{
			$subs  = $this->_db->get_where('user_sub',array('uid'=>$uid))->result_array();
			$uris=array();
			$auths = array();
			foreach ($subs as $item)
			{
				$temp=$this->_db->get_where('auth',array('gid'=>$item['gid']))->result_array();
				foreach ($temp as $jtem)
				{
					$authuri=$jtem['mtype'].'/'.$jtem['mid'].'/'.$jtem['ntype'].'/'.$jtem['nid'];
					if (! isset($auths[$authuri]))
						$auths[$authuri]=array();
					array_push($auths[$authuri],$jtem);
					array_push($uris,$authuri);
				}
			}
			$indipermi=array();
			foreach ($this->_db->get_where('permi_history',array('uid'=>$uid))->result_array() as $item)
			{
				$tempuri=$item['mtype'].'/'.$item['mid'].'/'.$item['ntype'].'/'.$item['nid'];
				if (! in_array($tempuri,$uris))
					array_push($uris,$tempuri);
				
				if (! isset($indipermi[$tempuri]))
						$indipermi[$tempuri]=array();
				array_push($indipermi[$tempuri],$item);
			}
			//echo var_dump($uris);
			//echo var_dump($auths);
			//echo var_dump($indipermi);
			
			/**
			 * Calculate the permission the user have
			 */
			$data=array();
			foreach ($uris as $item)
			{
				$tempauth='';
				if (isset($auths[$item])){
					foreach ($auths[$item] as $jtem)
					{
						$tempauth=$tempauth.$jtem['auth'];
					}
				}
				
				if (isset($indipermi[$item])){
					foreach ($indipermi[$item] as $jtem)
					{
						$tempauth=$tempauth.$jtem['auth'];
					}
				}
				
				$cal_auth='';
				foreach (array('l','c','u','r','d') as $jtem)
				{
					if ($this->_CI->auth->str_ck($tempauth,$jtem))
						$cal_auth=$cal_auth.$jtem;
					else
						$cal_auth=$cal_auth.'-';
				}
				
				trim($item,'/');
				$tempuri=explode('/',$item);
				array_push($data,array('uid'=>$uid,
									   'mtype'=>$tempuri[0],
									   'mid'=>$tempuri[1],
									   'ntype'=>$tempuri[2],
									   'nid'=>$tempuri[3],
									   'permi'=>$cal_auth));
			}
			//echo var_dump($data);
			
			foreach ($data as $item)
			{
				if (! $this->_db->insert('permission',$item))
					throw new MY_Exception('Database Error');
			}
			return TRUE;
		}
		catch (Exception $e){
			foreach ($recent_permission as $item)
			{
				if (! $this->_db->insert('permission',$item))
					throw new MY_Exception('Database Error');
			}
			log_message('error','Something Wrong:'.$e->getMessage());
			return FALSE;
		}
	}
	
	public function addto_group($gid,$auth,$mtype = 'all' , $mid = 0, $ntype = 'all', $nid = 0)
	{
		//check input@todo
		
		//change info
		$this->_db->trans_begin();
		$recent=$this->_db->get_where('auth',array('gid'=>$gid,'mtype'=>$mtype,'mid'=>$mid,'ntype'=>$ntype,'nid'=>$nid));
		if ($recent->num_rows()==0)
			$this->_db->insert('auth',array('gid'=>$gid,'mtype'=>$mtype,'mid'=>$mid,'ntype'=>$ntype,'nid'=>$nid,'auth'=>$auth));
		elseif ($recent->num_rows()==1)
		{
			//if exist, merge two authority record
			
			//can be bug @todo
			$recent_auth=end($recent->result())->auth.$auth;
			$merged_auth='';
			
			foreach (array('l','c','u','r','d') as $item)
			{
				if ($this->_CI->auth->str_ck($recent_auth,$item))
				{
					$merged_auth=$merged_auth.$item;
				}
				else
				{
					$merged_auth=$merged_auth.'-';
				}
			}
			
			//update
			$this->_db->update('auth',array('auth'=>$merged_auth),array('gid'=>$gid,'mtype'=>$mtype,'mid'=>$mid,'ntype'=>$ntype,'nid'=>$nid));
		}
		else
		{
			log_message('Eorror','Database Error:something wrong in the permission information.');
			$this->_db->trans_rollback();
		}
		
		//syn all the users
		$users=$this->_db->get_where('user_sub',array('gid'=>$gid))->result_array();
		try{
			foreach ($users as $item)
			{
				if (! $this->syn($item['uid']))
					throw new MY_Exception('Something Wrong?');
			}
			$this->_db->trans_commit();
			return TRUE;
		}catch(Exception $e){
			$this->_db->trans_rollback();
			//log_message
			log_message('error','Some problem in syn.function:'.$e->getMessage());
			//return Exception
			return FALSE;
		}
	}
	
	/**
	 * @param $auth can be either 'l','c','u','r','d'
	 */
	public function deauthfrom_group($gid,$auth,$mtype = 'all' , $mid = 0, $ntype = 'all', $nid = 0)
	{
		//check input@todo
		if (! in_array($auth,array('l','c','u','r','d')))
		{
			return FALSE;
		}
		
		//update the authority info		
		$this->_db->trans_begin();
		$recent=$this->_db->get_where('auth',array('mtype'=>$mtype,'mid'=>$mid,'ntype'=>$ntype,'nid'=>$nid));
		if ($recent->num_rows()==1)
		{
			$temp_permi=end($recent->result)->auth;
			//check if the deleted authority actually exist
			if ($this->_CI->auth->str_ck($temp_permi,$auth))
			{
				//exist then delete
				$newauth=str_ireplace($auth,'-',$temp_permi);
			}
			else
			{
				$this->_db->trans_commit();
				return TRUE;
			}
			
			//update data
			$this->_db->insert('auth',array('auth'=>$newauth),array('mtype'=>$mtype,'mid'=>$mid,'ntype'=>$ntype,'nid'=>$nid));
		}
		else
		{
			$this->_db->trans_rollback();
			return FALSE;
		}
		
		//syn!
		$users=$this->_db->get_where('user_sub',array('gid'=>$gid))->result_array();
		try{
			foreach ($users as $item)
			{
				if (!$this->syn($item['uid']))
					throw new MY_Exception('Something Wrong!!!!!!');
			}
			$this->_db->trans_commit();
			return TRUE;
		}catch(Exception $e){
			$this->_db->trans_rollback();
			log_message('error','Syn failed:'.$e->getMessage());
			return FALSE;
		}
	}
	
	public function delfrom_group($gid, $auth, $mtype = 'all', $mid = 0, $ntype = 'all', $nid = 0)
	{
		
	}
	
	public function addto_indi($uid,$permi,$mtype = 'all' , $mid = 0, $ntype = 'all', $nid = 0, $appendix = NULL)
	{
		//check input@todo
		
		//update permi info
		$this->_db->trans_begin();
		$recent=$this->_db->get_where('permi_history',array('uid'=>$uid,'mtype'=>$mtype,'mid'=>$mid,'ntype'=>$ntype,'nid'=>$nid));
		if ($recent->num_rows()==0)
			$this->_db->insert('permi_history',array('uid'=>$uid,'mtype'=>$mtype,'mid'=>$mid,'ntype'=>$ntype,'nid'=>$nid,'auth'=>$permi));
		elseif ($recent->num_rows()==1)
		{
			//if exist, merge two authority record
			
			//can be bug @todo
			$recent_permi=end($recent->result())->auth.$permi;
			$merged_permi='';
			
			foreach (array('l','c','u','r','d') as $item)
			{
				if ($this->_CI->auth->str_ck($recent_permi,$item))
				{
					$merged_permi=$merged_permi.$item;
				}
				else
				{
					$merged_permi=$merged_permi.'-';
				}
			}
			
			//update
			$this->_db->update('auth',array('auth'=>$merged_permi),array('uid'=>$uid,'mtype'=>$mtype,'mid'=>$mid,'ntype'=>$ntype,'nid'=>$nid));
		}
		else
		{
			log_message('Eorror','Database Error:something wrong in the permission information.');
			$this->_db->trans_rollback();
		}
		
		//syn
		if ($this->syn($uid))
		{
			$this->_db->trans_commit();
			return TRUE;
		}
		else
		{
			$this->_db->trans_rollback();
			return FALSE;
		}
	}
	
	public function delfrom_indi($uid,$permi,$mtype = 'all' , $mid = 0, $ntype = 'all', $nid = 0)
	{
		//check input@todo
		if (! in_array($permi,array('l','c','u','r','d')))
		{
			return FALSE;
		}
		
		//update the authority info		
		$this->_db->trans_begin();
		$recent=$this->_db->get_where('permi_history',array('mtype'=>$mtype,'mid'=>$mid,'ntype'=>$ntype,'nid'=>$nid));
		if ($recent->num_rows()==1)
		{
			$temp_permi=end($recent->result())->auth;
			//check if the deleted authority actually exist
			if ($this->_CI->auth->str_ck($temp_permi,$permi))
			{
				//exist then delete
				$newpermi=str_ireplace($permi,'-',$temp_permi);
			}
			else
			{
				$this->_db->trans_commit();
				return TRUE;
			}
			
			//update data
			$this->_db->insert('auth',array('auth'=>$newpermi),array('mtype'=>$mtype,'mid'=>$mid,'ntype'=>$ntype,'nid'=>$nid));
		}
		else
		{
			$this->_db->trans_rollback();
			return FALSE;
		}
		
		//syn!
		if ($this->syn($uid))
		{
			$this->_db->trans_commit();
			return TRUE;
		}
		else
		{
			$this->_db->trans_rollback();
			return FALSE;
		}
	}		
}