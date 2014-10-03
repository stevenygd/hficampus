<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Group.php by StevenY. */
class Chatroom extends SAE_Model{
	
	var $uid = NULL;
	var $chatId = NULL;
	
	function __construct($chatId = NULL){
		parent::__construct();
		
		//initialize
		$this->tableName = 'chatroom';
		$this->subContent= array(
            'tableName' => 'chatroom_members',
            'primaryKey' => 'id',
            'associatedKey' => 'chatid'
        );
		
		$this->uid = $this->user->id;
		if (isset($chatId)){
			$this->chatid = $chatId;
		}
		
		//load config file
		$this->config->load('chatroom');
	}
	
	//create 
	public function create($data){
		//check data
		$data = $this->_check_meta_data($data,TRUE);
		
		$temp_data = array(
			'creator' => $this->uid,
			'created_time' => date('Y-m-d h:i:s',time()),
			'expire_time' => $this->config->item('expired_time_default'),
			'appendix' => serialize(array())
		);
		
		$data = array_merge($temp_data,$data);
		
		$this->chatId = parent::create($data);
		if ($this->chatId === FALSE){
			return FALSE;
		}else{
            if ($this->add_member($this->chatId,$this->uid,TRUE) === FALSE){
                return FALSE;
            }else{
                return $this->chatId;
            }
		}
	}
	
	//delete
	public function delete($chatId){
		$this->chatId = $chatId;
		
		//check permission(only creator)
		if (! $this->is_owner($chatId)){
			return FALSE;
		}

		//database operation begins
		$this->db->trans_begin();
		parent::deleteByPk($this->chatId);
		parent::deleteSubContent(array($this->subContent['associatedKey'] => $this->chatId));
		//beyond modulus-node
		$this->db->delete('chatroom_msg',array('chatid'=>$this->chatId));
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return $this->set_err(202,'Database Operation Failed');
		}else{
			return TRUE;
		}	
	}	
	
	//edit
	public function edit($chatId,$data){		
		//check data
		$data = $this->_check_meta_data($data);
		
		//check permission
		foreach (array('topics','type','capacity') as $item){
			if (array_key_exists($item,$data)){
				if (! $this->is_owner($chatId)){
					return FALSE;
				}
			}
		}

		//database operation
		if (parent::updateByPk($data, $chatId) === FALSE){
			return $this->set_err(301,'database operation error');
		}else{
			return TRUE;
		}
	}
	
	//read functions isn't overriden
	
	//add member
	public function add_member($chatId,$uid = NULL, $is_create = FALSE){
		if (! isset($uid)){
			$uid = $this->uid;
		}
		//check permission
        if ($is_create === FALSE){
            if ($this->is_member($chatId,$this->uid) === FALSE){
                return FALSE;
            }
        }
		
		$data = array(
			'uid' =>$uid,
			$this->subContent['associatedKey'] => $chatId
		);
		//check duplicate
		if ($this->db->get_where($this->subContent['tableName'],$data)->num_rows()==0){
			return parent::createSubContent($data);
		}else{
			return TRUE;
		}
	}
	
	//delete member
	public function delete_member($chatId,$uid = NULL){
		if ( (! isset($uid)) || $uid == $this->uid ){
			//quit, didn't input $uid or $uid == $this->uid
			$uid = $this->uid;
			
			//check permission			
			if ($this->is_owner($chatId,$uid) === FALSE){
				//not owner, only quit
				if ($this->is_member($chatId,$uid) !== FALSE){
					return parent::deleteSubContent(array('uid' => $uid, $this->subContent['associatedKey'] =>$chatId));
				}else{
					return TRUE;
				}
			}else{
				//is owner, delete the group
				return $this->delete($chatId);
			}
			
		}else{
			//quick others
			if ($this->is_owner($chatId,$this->uid) === FALSE){
				return FALSE;
			}else{
				return parent::deleteSubContent(array('uid' => $uid, $this->subContent['associatedKey'] =>$chatId));
			}
		}
	}
	
	//get members
	public function get_members($chatId, $detailed = TRUE, $lim = 10, $off = 0){
		$filters = array($this->subContent['associatedKey']=>$chatId);
		if ($detailed === TRUE){
			//@debug, user_info should be....
			$this->db->select($this->subContent['tableName'] . '.*,user_info.cnfn, user_info.cnln,user_info.enn');
			$this->db->join('user_info','user_info.uid=' . $this->subContent['tableName'] . '.uid');
		}
		return parent:: _find($this->subContent['tableName'], $filters, NULL , NULL, $lim, $off);
	}
	
	//is_owner
	public function is_owner($chatId, $uid = NULL){
		if (! isset($uid)){
			$uid = $this->uid;
		}
		
		$this->chatId = $chatId;
		$count = parent::count(array($this->primaryKey => $this->chatId, 'creator' => $uid));
		if ($count != 1){
			return $this->set_err(10001,'Not the Owner of the Chatroom');
		}else{
			return TRUE;
		}
	}

	//is_member
	public function is_member($chatId, $uid = NULL){
		if (! isset($uid)){
			$uid = $this->uid;
		}
		
		$this->chatId = $chatId;
		$check = parent::findSubContent(array($this->subContent['associatedKey'] => $chatId, 'uid' => $uid));
		if (empty($check)){
			return $this->set_err(10002,'Not a member in the chatroom');
		}else{
			return TRUE;
		}
	}
	
	/**
	 * @param $strict bol whether the full structure of meta data is needed
	 * @return cleaned-up $data array if success
	 		   InvalidArgumentException if failed	   
	 */
	private function _check_meta_data($data, $strict = FALSE){
		//check structure
		if ($strict){
			$data_model = array('name','topics','type','capacity');
			foreach ($data_model as $item){
				if (! array_key_exists($item,$data)){
					throw new InvalidArgumentException('Invalid Argument: $data');
				}
			}
		}
		
		//check topics
		//@todo
		
		//check type and capacity
		if (isset($data['type'])){
			if (! ($data['type'] == 'single' || $data['type'] == 'group')){
				throw new InvalidArgumentException('Invalid Argument: $data["type"]');
			}elseif($data['type'] == 'group'){
				if (isset($data['capacity'])){
					//check capaciy
					$system_capacity = $this->config->item('capacity_allowed_value');
					if (! empty($system_capacity)){
						$temp_bol = FALSE;
						foreach ($system_capacity as $item){
							if ($data['capacity'] == $item ){
								$temp_bol = TRUE;
								break;
							}
						}
						
						if ($temp_bol === FALSE){
							throw new InvalidArgumentException('Invalid Argument: $data["capacity"]');
						}
					}
				}
			}else{
				if (isset($data['capacity'])){
					//$data['type'] = 'single'
					$data['capacity'] = 2;
				}
			}
		}
		
		return $data;
	}
	
}