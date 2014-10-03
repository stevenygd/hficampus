<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chatroom_msg extends SAE_Model{
	
	var $uid = NULL;
	var $chatId = NULL;
	var $messageId = NULL;
	var $err_code = 0;
	var $err_msg = '';
	
	function __construct($chatId = NULL){
		parent::__construct();

		//initialize
		$this->tableName = 'chatroom';
		$this->subContent= array(
            'tableName' => 'chatroom_msg',
            'primaryKey' => 'id',
            'associatedKey' => 'chatid'
        );
		
		$this->uid = $this->user->id;
		if (isset($chatId)){
			$this->chatid = $chatId;
		}
	}
	
	//send 
	public function send($chatId,$content){
		//check permission
		if ($this->_is_member($chatId,$this->uid) === FALSE){
			return FALSE;
		}
		
		$content = htmlentities($content,ENT_QUOTES,"UTF-8");//形成HTML实体（转义）
		
		$data = array(
			'chatid' => $chatId,
			'speaker' => $this->uid,
			'content' => $content,
			'created_time' => date('Y-m-d h:i:s',time()),
		);
				
		$this->messageId = parent::createSubContent($data);
		if ($this->chatId === FALSE){
			return $this->set_err(101,"JUST FAILED! i'M NOT HAPPY RIGHT NOW...");
		}else{
			return $this->messageId;
		}
	}
	
	//read 
	public function read($chatId,$filter = array(),$lim = 20,$off = 0, $detailed = TRUE){
		//check permission
		if ($this->_is_member($chatId,$this->uid) === FALSE){
			return FALSE;
		}
		if (is_array($filter)){
			$filter = array_merge(array($this->subContent['associatedKey'] => $chatId) , $filter);
		}else{
			$filter = array($this->subContent['associatedKey'] => $chatId);
		}
		
		if ($detailed === TRUE){
			$this->db->select($this->subContent['tableName'] . '.*, user_info.cnfn, user_info.cnln, user_info.enn');
			$this->db->join('user_info','user_info.uid =' . $this->subContent['tableName'] . '.speaker');
		}
		
		$ret = parent::_find($this->subContent['tableName'], $filter, NULL, 'id desc', $lim, $off);
		if ($ret === FALSE){//beyond modulus-node
			return $this->set_err(201,'Database Operation Failed');
		}else{
			return $ret;
		}	
	}
	
	//read one msg
	public function readByPkey($messageKey){
		//@todo check permission
		return parent::findSubContent(array($this->primaryKey => $messageKey));
	}
	
	//delete one msg
	public function deleteByPkey($messageKey){
		//check permission
		//check permission
		$filters = array('id'=>$messageKey,'speaker'=>$this->uid);
		$count = parent::_count($this->subContent['tableName'], $filters);
		if ($count !=1){
			return $this->set_err(301,'No Permission');
		}else{
			return parent::deleteSubContent($filters);
		}
	}
		
	//is_member
	private function _is_member($chatId, $uid = NULL){
		if (! isset($uid)){
			$uid = $this->uid;
		}
		$this->chatId = $chatId;
		if ($this->db->get_where('chatroom_members',array('chatid'=>$chatId,'uid'=>$uid))->num_rows() != 1){
			return $this->set_err(10001,'Not a member in the chatroom');
		}else{
			return TRUE;
		}
	}

}
