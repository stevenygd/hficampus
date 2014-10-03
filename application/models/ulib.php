<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * U Library model by StevenY originally.
 */
class Ulib extends SAE_Model {
	
	var $err_msg;
	var $err_code;
	
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Function to insert
	 */
	function compet_signin($leader,$members = array()){
		$this->_options['tableName']='ulib_compet';
		
		//check inputs
		if (! (is_array($leader) && array_key_exists('sid',$leader) && array_key_exists('email',$leader))){
			$this->errorhandler->setError(101,'Wrong Output');
			return FALSE;
		}
		else{
			$this->db->like('members',$leader['sid']);
			if ($this->db->get($this->_options['tableName'])->num_rows!=0){
				$this->errorhandler->setError(101,'Wrong Output');
				return FALSE;
			}
		}
		
		if (! (is_array($members))){
			$this->errorhandler->setError(101,'Wrong Output');
			return FALSE;
		}
		else{
			foreach ($members as $item){
				//check input format
				if (!(is_array($item) && array_key_exists('sid',$item) && array_key_exists('email',$item)))
				{
					$this->errorhandler->setError(101,'Wrong Output');
					return FALSE;
				}
				
				//check whether the member as been signed in
				$this->db->like('members',$item['sid']);
				if ($this->db->get($this->_options['tableName'])->num_rows!=0){
					$this->errorhandler->setError(101,'Wrong Output');
					return FALSE;
				}
			}
		}
		
		//generate data to insert into database
		$members_s='';
		foreach ($members as $item){
			$members_s=$members_s.$item['sid'].',';
		}
		$members_s=rtrim($members_s,',');
		if ($members_s == ''){
			$members_s = 'N/A';
		}
		array_push($members,array('sid'=>$leader['sid'],'email'=>$leader['email']));
		
		$data=array('leader'   => $leader['sid'],
					'email'    => $leader['email'],
					'members'  => $members_s,
					'time'     => date('Y-m-d H:i:s',time()),
					'appendix' => serialize(array('members'=>$members))
		);
		
		//insert data, if failed, throw an exception
		$insert_id = parent::_create($this->_options['tableName'], $data);
		
		//has already successfully inserted, put the signin number into the appendix of each user
		$return = TRUE;
		foreach ($members as $key=>$item){
			//registered user
			if ($this->db->get_where('user',array('member_id'=>$item['sid']))->num_rows()==1){
				//get registered user appendix
				$uid      = end($this->db->get_where('user',array('member_id'=>$item['sid']))->result())->id;
				$app_temp = end($this->db->get_where('user_info',array('uid'=>$uid))->result())->appendix;
				$app_temp = unserialize($app_temp);
				//add to user appendix
				$app_temp['ulib']=array('comp_id'=>$insert_id);
				$app_out  = serialize($app_temp);
				
				//update user appendix
				if (! $this->db->update('user_info', array('appendix'=>$app_out), array('uid' => $uid))){
					$this->errorhandler->setError(102,'Fail to update register data for sid:'.$item['sid'].';Sid is found in table:user.');
					$return = FALSE;
				}
			}
			//unregistered user
			elseif ($this->db->get_where('user_veri',array('member_id'=>$item['sid']))->num_rows()==1){
				//get app
				$app_temp = end($this->db->get_where('user_veri',array('member_id'=>$item['sid']))->result())->appendix;
				$app_temp = unserialize($app_temp);
				
				//add to user appendix
				$app_temp['ulib']=array('comp_id'=>$insert_id);
				$app_out  = serialize($app_temp);
				
				//update user appendix
				if (! $this->db->update('user_veri', array('appendix'=>$app_out), array('member_id' => $item['sid']))){
					$this->errorhandler->setError(103,'Fail to update register data for sid:'.$item['sid'].';Sid is found in table:user_veri.');
					$return = FALSE;
				}
			}
			else{
				$this->errorhandler->setError(104,'Fail to update register data for sid:'.$item['sid'].';Sid is not found.');
				$return = FALSE;
			}
		}

		if ($return === FALSE)
			return FALSE;
		else
			return $insert_id;
	}
	
	function get_compet_info($id){
		$this->_options['tableName']='ulib_compet';
		return parent::readByPk($id);
	}	
	
	function sidsearch($sid){
		//get registered user information
		if ($this->db->get_where('user',array('member_id'=>$sid))->num_rows()==1){
			$uid       = end($this->db->get_where('user',array('member_id'=>$sid))->result())->id;
			$user_info = $this->db->get_where('user_info',array('uid'=>$uid))->row_array();
			$user_info['appendix'] = unserialize($user_info['appendix']);
			if (isset($user_info['appendix']['ulib']['comp_id']) && is_numeric($user_info['appendix']['ulib']['comp_id'])){
				$registered = TRUE;
			}else{
				$registered = FALSE;
			}
			
			//generate output
			$output = array('en'    => $user_info['enn'],
							'fn'    => $user_info['cnfn'],
							'ln'    => $user_info['cnln'],
							'msg'   => 'Target located',
							'code'  => 0,
							'regi'  => $registered,
							'class' => $user_info['appendix']['class']
			);
		}
		//get unregistered user information
		elseif ($this->db->get_where('user_veri',array('member_id'=>$sid))->num_rows()==1){
			//get app
			$user_info = $this->db->get_where('user_veri',array('member_id'=>$sid))->row_array();
			$user_info['appendix'] = unserialize($user_info['appendix']);
			if (isset($user_info['appendix']['ulib']['comp_id']) && is_numeric($user_info['appendix']['ulib']['comp_id'])){
				$registered = TRUE;
			}else{
				$registered = FALSE;
			}
			
			$output = array('en'    => $user_info['enn'],
							'fn'    => $user_info['cnfn'],
							'ln'    => $user_info['cnln'],
							'msg'   => 'Target located',
							'code'  => 0,
							'regi'  => $registered,
							'class' => $user_info['appendix']['class']
			);
		}
		else{
			$output = array('msg'  => "Not yet matched",
							'code' => 100
			);
		}
		
		return $output;
	}
}
