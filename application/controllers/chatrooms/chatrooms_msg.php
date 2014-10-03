<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Chatrooms_msg Controller
 * Design by Vic, StevenY.
 * Code by Vic
 * Version 1.
 * Original: msg.php
 * Controll message in chatroom function
 */
class Chatrooms_msg extends SAE_Controller {
	
	var $uid;
	var $chatId = FALSE;//chatroom id, defaul is FALSE;
	
	function __construct()
	{
		parent::__construct();
		$this->uid = $this->user->id;
		$this->load->model('chatroom_msg');
		$this->mtype = 'chatrooms';
		$this->ntype = 'msg';
	}
	
	public function get($mid = NULL, $nid =NULL, $attach = '')
	{
		if (is_numeric($mid)){
			$this->chatId = $mid;
		}else{
			show_404();
		}
		
		if (! isset($nid)){
			//the codes below display a list of message in the particular chatroom
			$lim = $this->input->get('lim') === FALSE ? 20 : $this->input->get('lim');
			$off = $this->input->get('off') === FALSE ? 0 : $this->input->get('off');
			
			$this->data['list']=$this->chatroom_msg->read($this->chatId, NULL, $lim, $off);
			$this->push();
		}elseif (is_numeric($nid)){
			//@todo
			//the codes below returns a particular message in the particular chatroom
			//show_404();
			$this->data['msg']=$this->chatroom_msg->read($this->chatId,array('chatroom_msg.id'=>$nid),1,0,TRUE);
			$this->push();
		}else{
			show_404();
		}
	}
	
	public function delete($mid = NULL, $nid =NULL, $attach = '')
	{
		if (is_numeric($mid)){
			$this->chatId = $mid;
		}else{
			show_404();
		}
		
		if (! isset($nid)){
			//@todo the codes below delete messages collective in a particular chatroom
			show_404();
		}elseif (is_numeric($nid)){
			//closed
			//the codes below delete a particular message
			//show_404();
			if ($this->chatroom_msg->deleteByPkey($nid)===FALSE){
				$this->data['code'] = $this->chatroom_msg->err_code;
				$this->data['message'] = $this->chatroom_msg->err_msg;
				$this->push();
				return;
			}
			else
			{
				//success
				$last_msg = end($this->chatroom_msg->read($this->chatId,NULL,1));	
				$this->data['last_msg'] = $last_msg;
				$this->push();
				
				//send messages to channel
				$this->load->model('chatroom');
				$members = $this->chatroom->get_members($this->chatId);
				$channel_content= array(
					'messageId'    => $nid,
					'last_msg'     => $last_msg,
					'chatId'       => $this->chatId
				);
				$this->channelPush($members,$channel_content);
			}
		}else{
			show_404();
		}
	}

	public function create($mid = NULL, $nid =NULL, $attach = '')
	{
		if (is_numeric($mid)){
			$this->chatId = $mid;
		}else{
			show_404();
		}
		
		if (! isset($nid)){
			//the codes below send a message to the particular chatroom
			$content=$this->input->post('content');
			if ($content===FALSE)
			{
				$this->code = 404;
				$this->message = "Stupid you haven't send anything";
				$this->push();
				return;
			}else{
				$this->data['messageId'] = $this->chatroom_msg->send($this->chatId,$content);
			}
			$this->data['code']=$this->chatroom_msg->err_code;
			$this->data['message']=$this->chatroom_msg->err_msg;
			$this->push();//return success
			
			//send messages to channel
			$this->load->model('chatroom');
			$members = $this->chatroom->get_members($this->chatId);
			$user_info = $this->user->get_info($this->uid);
			$channel_content= array(
				'chatId'       => $this->chatId,
				'content'      => htmlentities($content,ENT_QUOTES,"UTF-8"),//形成HTML实体（转义）
				'speaker'      => $user_info,
				'created_time' => date('Y-m-d h:i:s',time()),
				'messageId'    => $this->data['messageId']
			);
			$this->channelPush($members,$channel_content);
		}elseif (is_numeric($nid)){
			//（@todo）codes below add attachment to a particular message
			//例如:点赞
			show_404();
		}else{
			show_404();
		}
	}

	//Edit function is closed
	public function edit($mid = NULL, $nid =NULL, $attach = '')
	{
		show_404();
	}
	
}