<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*msg.php by StevenY*/
class Message extends SAE_Controller {
	
	var $uid;
	
	function __construct()
	{
		parent::__construct();
		$this->uid = $this->user->id;
		$this->load->model('mes');
	}
	
	public function get($mid = NULL)
	{
		if (isset($mid))
		{
			if (is_numeric($mid))//$mid=user_id
			{
				switch($this->input->get('execution'))
				{
					case 'fetchmsg'://get messages
						if ($this->input->get('lid'))
						{
							//get message from eid to uid
							$list1=array();
							$where1=array('id >'=>$this->input->get('lid'),
										  'auth'=>$mid,
										  'to'=>$this->uid
										  );
							$list1=$this->mes->get_list($where1,'id desc',FALSE,'*',10,0);
							//get message from uid to eid
							$list2=array();
							$where2=array('id >'=>$this->input->get('lid'),
										  'auth'=>$this->uid,
										  'to'=>$mid
										  );
							$list2=$this->mes->get_list($where2,'id desc',FALSE,'*',10,0);
							$return=array();
							if (is_array($list1))
								foreach ($list1 as $i=>$item) $return[$item['id']]=$item;
							if (is_array($list2))
								foreach ($list2 as $i=>$item) $return[$item['id']]=$item;
							ksort($return);
							$return['length']=count($return);
							//generate json data for js
							$this->push(NULL,$return);
						}
						else 
							echo 'Nothing recieved';
					break;
					default://conver
						//get the most recent messages
						$a['conver']=$this->mes->get_msg($mid,FALSE,'*',20,0,'id desc');
						//put the messages back to time other(from the oldest to the most recent)
						$a['uinfo']=$this->user->get_user_list(array('uid'=>array($mid,$this->uid)),10,0,0);
						$a['uid']=$this->uid;
						$a['eid']=$mid;
						$a['err_msg']=$this->mes->get_err_msg();
						$a['lid']=$a['conver'][0]['id'];
						$this->load->helper('form');
						$this->push('msg/conver',$a);
					break;
				}
			}
			else //$mid = some special options
			{
				switch ($mid)
				{
					case 'main'://get main page
						$a['uid']=$this->uid;	
						//uids, from the user list
						$a['fid']=$this->user->get_ap($this->uid,'mes_user_list');
						$a['invitation']=$this->user->get_invitation('*',$this->uid,10,0);
						if ($a['fid']==0)
						{
							$a['finfo']=0;
							$a['msg_list']=0;
							$this->push('msg/msg',$a);
						}
						else
						{
							//user_information_list
							$a['finfo']=$this->user->get_user_list(array('uid'=>$a['fid']),10,0,0);
							$a['msg_list']=array();
					
							foreach ($a['finfo'] as $i => $item)
							{
								if ($i !=$this->uid)
									if (($this->mes->get_num($i,$this->uid,FALSE,'*','id desc')+
										 $this->mes->get_num($this->uid,$i,FALSE,'*','id desc')) != 0) 
										{
											$a['msg_list'][$i]=$this->mes->get_msg($i,FALSE,'*',1,0,'id desc');
											if (! $a['msg_list'][$i]) 
											{
												$a['msg_list'][$i]['err_code']=$this->mes->get_err_code();
												$a['msg_list'][$i]['err_msg']=$this->mes->get_err_msg();
											}
											else
												$a['msg_list'][$i]['num_new_msg'] = 
												$this->mes->get_num($i,$this->uid,FALSE,FALSE,'id desc');
										}
							}
							$a['uid']=$this->uid;
							$this->push('msg/msg',$a);
						}
					break;
					case 'search'://search for friends
						if ($this->input->get('fsearch'))
						{
							$flist=$this->user->search(array('cnln','cnfn','enn'),
													   $this->input->get('fsearch'),
													   'cnln desc',20,0);
							if ($flist===FALSE)
								echo $this->user->get_err_msg();
							else
							{
								$this->push(NULL,$flist);//only ajax
								/*
								$this->load->library('json');
								echo $this->json->encode($flist);
								*/
							}
						}
						else
							echo 'Nothing Recieved';
					break;
					case 'getflist'://get friend list
						$fid=$this->user->get_ap($this->uid,'mes_user_list');
						if ($this->input->get('off'))
							$off=10*($this->input->get('off'));
						else
							$off=0;
						$flist=$this->user->get_user_list(array('uid'=>$fid),10,$off,0);
						if ($flist!=FALSE)
						{
							$this->push(NULL,$flist);
						}
						else
							echo $this->user->get_err_msg();
					break;
					case 'getuserinfo'://get user info
						if (is_int($this->input->get('lid')))
							$off=$this->input->get('lid')*20;
						else
							$off=1;
						$ulist=$this->user->get_user_list('*',20,$off,'cnln desc');
						$ulist['length']=count($ulist);
						$this->push(NULL,$ulist);
					break;
					case 'invitation':
						$this->db->select('user_info.cnfn,user_info.cnln,user_info.enn,waitlist_friend.*');
						$this->db->join('user_info','user_info.uid=waitlist_friend.send');
						$a['invitation']=$this->user->get_invitation('*',$this->uid,10,0);
						$this->push('msg/invitation',$a);
					break;
					default:
						show_404();
					break;
				}
				
			}
			
		}
		else//index page
		{
			$a['uid']=$this->uid;
			$this->push('msg/index',$a);
		}
	}
	public function delete($mid = NULL)
	{
		if (isset($mid))
		{
			if ($this->input->get('uid'))
			{
				$data=$this->user->get_ap($this->uid,'mes_user_list');
				if (! in_array($this->input->get('uid'),$data))
					echo 0;
				else
				{
					array_push($data,$this->input->get('uid'));
					$data2=array();
					foreach ($data as $item)
					{
						if ($item!=$this->input->get('uid')) 
						{
							array_push($data2,array_shift($data));
						}
						else
							$del=array_shift($data);
					}
					if ($this->user->unset_ap($this->uid,'mes_user_list',$data))
						echo 0;
					else
						echo $this->user->get_err_msg();
				}
			}
			else
				echo 'Nothing recieved';
		}
		else
		{
		}
	}
	public function create($mid = NULL)
	{
		if (isset($mid))
		{
			if(is_numeric($mid))//send to somebody
			{
					//验证是否获得信息
					$this->load->library('form_validation');
					$this->load->helper('form');
					$rule=array(
							array(
								'field'   => 'to', 
								'label'   => 'to', 
								'rules'   => 'trim|required|is_numeric'
								),
							array(
								'field'   => 'text', 
								'label'   => 'text', 
								'rules'   => 'trim|required'
								),
						);
						
					$this->form_validation->set_rules($rule);
					if ($this->form_validation->run() == FALSE)
					{
						echo validation_errors();	//@todo
					}
					else
					{
						$to=$this->input->post('to');
						$title=$this->input->post('title');
						$text=$this->input->post('text');
						$fback=$this->mes->sendto($to,$title,$text,FALSE);
						if ($fback)	
						{
							//SUCCESS, and redirect to conversation page
							redirect('message/'.$to.'/get#last');
						}
						else
						{
							echo $this->mes->get_err_msg();
						}
					}
			}
			else//some other execution
			{
				switch ($mid)
				{
					case 'friends'://add friend, want to add friend
						if ($this->input->post('to') && ($this->input->post('to')!=$this->uid))
						{
							if ($this->input->post('text'))
								$comment=$this->input->post('text');
							else
								$comment='';
								
							$data=$this->user->get_ap($this->uid,'mes_user_list');
							
							if ($data==0)
							{
								$new=array();
								array_push($new,$this->input->post('to'));
								if ($this->user->set_ap($this->uid,'mes_user_list',$new) &&
									$this->user->invite($this->input->post('to'),$comment))
									echo 0;
								else
									echo $this->user->get_err_msg();
							}
							else
							{
								if (! in_array($this->input->post('to'),$data))						
								{
									array_push($data,$this->input->post('to'));
									if ($this->user->set_ap($this->uid,'mes_user_list',$data)&&
										$this->user->invite($this->input->post('to'),$comment))
									{
										echo 0;
									}
									else
										echo $this->user->get_err_msg();
								}
								else
									echo 0;
							}
						}
						else
							echo 'Nothing recieved';
					break;
				}
			}
		}
		else
		{
			show_404();
		}
	}
	public function edit($mid = NULL)
	{
		if (isset($mid))
		{
			switch($mid)
			{
				case 'read':
					if (is_numeric($this->input->post('oid')))
					{
						if ($this->mes->read($this->input->post('oid')))
							$this->push(NULL,0);//only accept ajax
						else
							echo $this->mes->get_err_msg();
					}
					else
						echo 'Wrong data';
				break;
				case 'accept'://accept friend request
					if ($this->input->post('oid'))
					{
						if ($this->user->accept($this->input->post('oid')))
						{
							$data=$this->user->get_ap($this->uid,'mes_user_list');
							if ($data==0)
							{
								$new=array();
								array_push($new,$this->input->post('oid'));
								if ($this->user->set_ap($this->uid,'mes_user_list',$new))
									echo 0;
								else
									echo $this->user->get_err_msg();
							}
							else
							{
								if (! in_array($this->input->post('oid'),$data))						
								{
									array_push($data,$this->input->post('oid'));
									if ($this->user->set_ap($this->uid,'mes_user_list',$data))
										echo 0;
									else
										echo $this->user->get_err_msg();
								}
								else
									echo 0;
							}
						}
						else
							echo "Nothing recieved";
					}
					else
						echo 'nothing recieved';
				break;
				default:
					
				break;
			}
		}
		else
		{
			show_404();
		}
	}
	
	//invitation page
	public function invitation()
	{
		if ($this->input->get('off'))
			$off=10*($this->input->get('off'));
		else
			$off=0;
		$a['invitation']=$this->user->get_invitation('*',$this->uid,10,$off);
		foreach ( $a['invitation'] as $item)//@todo
		{
			
		}
		$this->push('msg/invitation',$a);
	}	
	
}

/* End of file msg.php */
/* Location: ./application/controllers/msg.php */