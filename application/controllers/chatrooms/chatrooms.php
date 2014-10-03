<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Chatrooms Controller
 * Design by Vic, StevenY.
 * Code by Vic, Ryan
 * Version 1.
 * Original: msg.php
 * Controll all the business logics of chatrooms(including create, delete, invite, etc.)
 */
class Chatrooms extends SAE_Controller {
	
	var $chatId = FALSE;//chatroom id, defaul is FALSE;
	
	function __construct()
	{
		parent::__construct();
		$this->uid = $this->user->id;
		$this->load->model('chatroom');
		$this->mtype = 'chatrooms';
	}
	
	public function get($mid = NULL)
	{

		if (! isset($mid)){
			//the codes below return the main page of chatroom
			$chatrooms_ids = $this->chatroom->findSubContent(array('uid'=>$this->uid));
			$this->load->model('chatroom_msg');
			
			foreach ($chatrooms_ids as $i => $item){
				$this->data['chatrooms'][$i]             = $this->chatroom->readByPk($item['chatid']);
				$this->data['chatrooms'][$i]['members']  = $this->chatroom->get_members($item['chatid']);
				$this->data['chatrooms'][$i]['last_msg'] = end($this->chatroom_msg->read($item['chatid'],NULL,1));
			}
			if ($this->input->get('test') === 'true'){
				$this->push('chatroom/channel');//for test
			}else{
				$this->push('chatroom/index');
			}
			//production
			//$this->render('chatroom/index','home');
			
		}elseif (is_numeric($mid)){
			$this->chatId = $mid;
			//the codes below return some specific chatroom's information
			$this->data['chatroom_info']=$this->chatroom->readByPk($this->chatId);
			$this->data['members']=$this->chatroom->get_members($this->chatId);
			$this->data['code']=$this->chatroom->err_code;
			$this->data['message']=$this->chatroom->err_msg;
			$this->push();
		}else{
			show_404();
		}
	}
	
	public function delete($mid = NULL)
	{
		if (! isset($mid)){
			//(@todo)the codes below delete chatrooms collectively
			throw new MY_Exception('Chatroom ID should be specified', 500, 'chatrooms', 10,'Exception');
		}elseif (is_numeric($mid)){
			$this->chatId = $mid;
			//the codes below is the deletion of a specific chatroom
			//if the user is the owner of the chatroom, the chatroom will be deleted completely
			if ($this->chatroom->is_owner($this->chatId))
			{
				//first get members of the chatroom
				$members = $this->chatroom->get_members($this->chatId);
				if ($this->chatroom->delete($this->chatId)){
					//successfully deleted
					$this->push();
					//push channel message
					$channel_content= array(
						'chatId'    => $this->chatId
					);
					$this->channelPush($members,$channel_content);
					return;
				}
			}
			//if the user isn't the owner of the chatroom, the user will quit the chatroom
			else
			{
				if ($this->chatroom->delete_member($this->chatId,$this->uid)!==FALSE){
					//successful
					$this->push();
					
					//push channel message
					$members = $this->chatroom->get_members($this->chatId);
					$channel_content= array(
						'quitUID'    => $this->uid,
						'chatId'     => $this->chatId
					);
					$this->channelPush($members,$channel_content);
					return;
				}
			}
		}else{
			show_404();
		}
	}

	public function create($mid = NULL)
	{
		if (! isset($mid)){
			//the codes below create a chatroom
			$this->load->library('form_validation');
			$rule=array(
					array(
						'field'   => 'name', 
						'label'   => 'Chatroom Name', 
						'rules'   => 'trim|required'
						),
					array(
						'field'	  => 'topics',
						'label'   => 'Chatroom topic',
						'rules'   => 'trim|required'
						),
					array(
						'field'	  => 'type',
						'label'   => 'Chatroom type',
						'rules'   => 'trim|required'
						),
					array(
						'field'	  => 'capacity',
						'label'   => 'Chatroom capacity',
						'rules'   => 'trim|required|is_numeric'
						),
				);
			$this->form_validation->set_rules($rule);
			if ($this->form_validation->run() === FALSE)
				echo validation_errors();
			else
			{
				$name=$this->input->post('name');
				$topics=$this->input->post('topics');
				$type=$this->input->post('type');
				$capacity=$this->input->post('capacity');
				$create=array(
								'name'=>$name,
								'topics'=>$topics,
								'type'=>$type,
								'capacity'=>$capacity,
							);
				$chatroom_id=$this->chatroom->create($create);

				if($chatroom_id === FALSE)
				{
					$this->code=$this->chatroom->err_code;
					$this->message=$this->chatroom->err_msg;
					$this->push();
					returh;
				}
				else
				{
					$this->chatId = $chatroom_id;
					$this->data['chatroom_id']=$chatroom_id;
					//get member list
					if ($this->input->post('members')!== FALSE){
						$members= json_decode($this->input->post('members'));
						
						//test
						/*$this->data['test']['json_before'] = $this->input->post('members');$this->data['test']['json_after']  = $members;*/
						
						//put every members inside
						$successful_member_list = array();
						foreach ($members as $item){
							if ($this->chatroom->add_member($this->chatId,$item) === FALSE){
								$this->data['code'][]  = $this->chatroom->err_code;
								$this->data['message'][] = $this->chatroom->err_msg;
							}
							
							//if single, just add the first one
							if ($type == 'single'){
								break;
							}
						}
						
						//success
						$this->push();
						
						//push channel message
						$members = $this->chatroom->get_members($this->chatId);
						$channel_content= array(
							'newChatId' => $this->chatId
						);
						$this->channelPush($members,$channel_content);
					}else{
						$this->push();
					}
				}
			}
		}elseif (is_numeric($mid)){
			$this->chatId = $mid;
			//the codes below invite a person into a chatroom (create new data in the chatroom)
			$uid=$this->input->post('uid');
			if ($uid===FALSE)
			{
				if ($this->input->post('members')=== FALSE){
					$this->data['code']=404;
					$this->data['message']='NOthing received';
					$this->push();
					return;
				}else{
					$members = json_decode($this->input->post('members'));
					$successful_member_list= array();
					foreach ($members as $item){
						if ($this->chatroom->add_member($this->chatId,$item) === FALSE){
							$this->data['code'][$item]    = $this->chatroom->err_code;
							$this->data['message'][$item] = $this->chatroom->err_msg;
						}else{
							$successful_member_list[$item] = $this->user->get_info($item);
						}
					}
					//success
					$this->push();
					
					//push channel message
					$members = $this->chatroom->get_members($this->chatId);
					$channel_content= array(
						'addUIDs' => $successful_member_list,
						'chatId' => $this->chatId
					);
					$this->channelPush($members,$channel_content);
				}
			}
			else
			{
				if ($this->chatroom->add_member($this->chatId,$uid)){
					//success
					$this->push();
					//push channel message
					$members = $this->chatroom->get_members($this->chatId);
					$channel_content= array(
						'addUID' => $uid,
						'user'   => $this->user->get_info($uid),
						'chatId' => $this->chatId
					);
					$this->channelPush($members,$channel_content);
				}else{
					$this->data['code']    = $this->chatroom->err_code;
					$this->data['message'] = $this->chatroom->err_msg;
					$this->push();
					return;
				}
			}
		}else{
			show_404();
		}
		
	}

	public function edit($mid = NULL)
	{
		if (! isset($mid)){
			//@todo the code below change chatrooms information collectively 
			show_404();
		}elseif (is_numeric($mid)){
			$this->chatId = $mid;
			//the codes below change information in a specific chatroom
			switch ($this->input->post('operation')){
				case 'kick':
					//codes below kick a person out of the chatroom
					$uid=$this->input->post('uid');
					if ($uid !== FALSE){
						if ($this->chatroom->delete_member($this->chatId,$uid)){
							//success
							$this->push();
							//push channel message
							$members = $this->chatroom->get_members($this->chatId);
							$channel_content= array(
								'deleteUID' => $uid,
								'chatId'    => $this->chatId
							);
							$this->channelPush($members,$channel_content);
						}else{
							$this->code    = $this->chatroom->err_code;
							$this->message = $this->chatroom->err_msg;
							$this->push();
						}
					}else{
						$this->code = '404';
						$this->message = 'Nothing received';
						$this->push();
					}
				break;
				case 'edit':
					$updates = array();
					//check whether the user is the owner
					if ($this->chatroom->is_owner($this->chatId))
						$items = array('name','topics','capacity');
					else
						$items = array('topics','capacity');
					foreach ($items as $item){
						if ($this->input->post($item)!==FALSE){
							$updates[$item]= $this->input->post($item);
						}
					}
					
					if ($this->chatroom->edit($this->chatId,$updates)){
						//success
						$this->push();
						//push channel message
						$members = $this->chatroom->get_members($this->chatId);
						$channel_content= array(
							'chatId'  => $this->chatId,
							'updates' => $updates
						);
						$this->channelPush($members,$channel_content);
					}else{
						$this->code = $this->chatroom->err_code;
						$this->message = $this->chatroom->err_msg;
						$this->push();
					}
				break;
				case 'name':
					//codes below change a chatroom's name
					$name=$this->input->post('name');
					$update=array('name' => $name);
					if ($this->chatroom->edit($this->chatId,$update)){
						//success
						$this->push();
						//push channel message
						$members = $this->chatroom->get_members($this->chatId);
						$channel_content= array(
							'chatId'          => $this->chatId,
							'changeFieldName' => 'name',
							'content'         => $name
						);
						$this->channelPush($members,$channel_content);
					}else{
						$this->code = $this->chatroom->err_code;
						$this->message = $this->chatroom->err_msg;
						$this->push();
					}
				break;
				case 'topics':
					//codes below change a chatroom's topic
					$topics=$this->input->post('topics');
					$update=array('topics' => $topics);
					if ($this->chatroom->edit($this->chatId,$update)){
						//success
						$this->push();
						//push channel message
						$members = $this->chatroom->get_members($this->chatId);
						$channel_content= array(
							'chatId'          => $this->chatId,
							'changeFieldName' => 'topics',
							'content'         => $topics
						);
						$this->channelPush($members,$channel_content);
					}else{
						$this->code = $this->chatroom->err_code;
						$this->message = $this->chatroom->err_msg;
						$this->push();
					}
				break;
				case 'type':
					//codes below change a chatroom's type
					$type=$this->input->post('type');
					$update=array('type' => $type);
					if ($this->chatroom->edit($this->chatId,$update)){
						//success
						$this->push();
						//push channel message
						$members = $this->chatroom->get_members($this->chatId);
						$channel_content= array(
							'chatId'          => $this->chatId,
							'changeFieldName' => 'type',
							'content'         => $type
						);
						$this->channelPush($members,$channel_content);
					}else{
						$this->code = $this->chatroom->err_code;
						$this->message = $this->chatroom->err_msg;
						$this->push();
					}
				break;
				case 'capacity':
					//codes below change a chatroom's capacity
					$capacity=$this->input->post('capacity');
					$update=array('capacity' => $capacity);
					if ($this->chatroom->edit($this->chatId,$update)){
						//success
						$this->push();
						//push channel message
						$members = $this->chatroom->get_members($this->chatId);
						$channel_content= array(
							'chatId'          => $this->chatId,
							'changeFieldName' => 'type',
							'content'         => $type
						);
						$this->channelPush($members,$channel_content);
					}else{
						$this->code = $this->chatroom->err_code;
						$this->message = $this->chatroom->err_msg;
						$this->push();
					}
				break;

				//@todo more options is waiting
				default:
					show_404();
				break;
			}
		}else{
			show_404();
		}
	}
	
	//test for chatroom
	public function chatroom_test()
	{
		$this->layout='test';
		$this->push('chatroom/chatroom_test');
	}
						
}