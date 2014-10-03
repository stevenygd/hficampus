<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*test.php by StevenY*/
class TestChannel extends SAE_Controller {
		
	public $channel;
	
	function __construct(){
		parent::__construct();
		$this->channel = new SaeChannel();
		$this->load->model('chatroom');
	}
	
	//function that open a channel
	public function index(){
		$this->load->helper('string');
		$random= random_string('nozero',10);
		$connection_url = $this->channel->createChannel('test'.$random,3600);
		$chatRooms_list = $this->chatroom->get_list();
		$data =array('url'=>$connection_url, 'channelId'=>'test'.$random, 'chatRooms' => $chatRooms_list);
		$this->load->view('test/channel',$data);
	}
	
	//function that deal with the message received
	public function api($op){
		$ret_mod = array(
			'errcode' => 0,
			'errmsg'  => '',
			'data'    => ''
		);
		
		if ($this->input->post('channelId')){
			$channelId = $this->input->post('channelId');
			$id = ltrim($channelId,'test');
		}else{
			//create a channelId/refresh the page
			$channel = new SaeChannel();
			$random= 1;
			$connection_url = $this->channel->createChannel('test'.$random,3600);
			$this->load->view('test/channel',array('url'=>$connection_url));
			return FALSE;
		}
		
		switch($op){
			case 'create_chatroom':
				$data = array('members'=>$id);
				echo $this->chatroom->create($data);
				return TRUE;
			break;
			
			case 'join_chatroom':
				if ($this->input->post('chatId')){
					//get the members of this chatroom
					$chatId  = $this->input->post('chatId');
					$members = $this->chatroom->readByPk($chatId, 'members');
					$members = $members['members'];
					
					$members_array = explode(',',$members);
					
					//check if one is already in the room
					if (! in_array($id,$members_array)){
						//if not in, add in and return true
						array_push($members_array,$id);
						$members = implode(',',$members_array);
						$this->chatroom->updateByPk(array('members'=>$members), $chatId);
					}
					echo TRUE;
					return TRUE;
				}else{
					echo 'No Chatroom ID received';
					return FALSE;
				}
			break;
			
			case 'quit_chatroom':
				if ($this->input->post('chatId')){
					//get the members of this chatroom
					$chatId  = $this->input->post('chatId');
					$members = $this->chatroom->readByPk($chatId, 'members');
					$members = $members['members'];
					$members_array = explode(',',$members);
					
					//check if one is already in the room
					if (in_array($id,$members_array)){
						//if not in, delete from the value
						$key = array_search($id,$members_array);
						$members_array_new = array();
						foreach ($members_array as $i=>$item){
							if ($i != $key){
								array_push($members_array_new,$item);
							}
						}
						
						//update the new value
						$members = implode(',',$members_array_new);
						$this->chatroom->updateByPk(array('members'=>$members), $chatId);
					}
					echo TRUE;
					return TRUE;
				}else{
					echo 'No Chatroom ID received';
					return FALSE;
				}
			break;
			
			case 'push':
				if ($this->input->post('data') && $this->input->post('chatId')){
					$chatId = $this->input->post('chatId');
					$members = $this->chatroom->readByPk($chatId, 'members');
					$members = $members['members'];
					$members_array = explode(',',$members);
					
					$msg = $this->input->post('data');
					$ret['data'] = array( 'id'=>$id, 'msg' => $msg, 'chatId' => $chatId);
					$message_content = json_encode($ret);
					
					foreach ($members_array as $item){
						$temp_channelId = 'test'.$item;
						$this->channel->sendMessage($temp_channelId,$message_content);
					}
					
					echo TRUE;
					return TRUE;
					
				}else{
					echo 'I got nothing stupid!';
					return;
				}
			break;
			default:
				throw new MY_Exception();
			break;
		}
	}

}