<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*msg.php by StevenY*/
class Msg extends CI_Controller {
	
	var $uid;
	
	function __construct()
	{
		parent::__construct();
		if(! $this->session->userdata('login'))
			redirect(site_url('account'));
		$this->load->model('mes');
		$this->load->helper('form');
		$this->uid=$this->session->userdata('uid');
	}
	
	public function index()
	{
		$a['uid']=$this->uid;
		$this->load->view('msg/index',$a);
	}
	
	//信息页面主页
	public function flist()
	{
		$a['uid']=$this->uid;	
		//uids, from the user list
		$a['fid']=$this->user->get_ap($this->uid,'mes_user_list');
		$a['invitation']=$this->user->get_invitation('*',$this->uid,10,0);
		if ($a['fid']==0)
		{
			$a['finfo']=0;
			$a['msg_list']=0;
			$this->load->view('msg/msg',$a);
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
			$this->load->view('msg/msg',$a);
		}
	}
		
	//与某人对话页面
	public function conver($eid)
	{
		//get the most recent messages
		$a['conver']=$this->mes->get_msg($eid,FALSE,'*',20,0,'id desc');
		//put the messages back to time other(from the oldest to the most recent)
		$a['uinfo']=$this->user->get_user_list(array('uid'=>array($eid,$this->uid)),10,0,0);
		$a['uid']=$this->uid;
		$a['eid']=$eid;
		$a['err_msg']=$this->mes->get_err_msg();
		$a['lid']=$a['conver'][0]['id'];
		//user_information_list
		$this->load->view('msg/conver',$a);
	}
	
	//invitation page
	public function invitation()
	{
		if ($this->input->get('off'))
			$off=10*($this->input->get('off'));
		else
			$off=0;
		$a['invitation']=$this->user->get_invitation('*',$this->uid,10,$off);
		foreach ( $a['invitation'] as $item)
		{
			
		}
		$this->load->view('msg/invitation',$a);
	}
	
	//发送信息
	//post 发送
	//$op='not' or 'msg'
	public function send($op)
	{
		//验证是否获得信息
		$this->load->library('form_validation');
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
			if ($op=='msg') $fback=$this->mes->sendto($to,$title,$text,FALSE);
			elseif ($op=='not') $fback=$this->mes->sendto($to,$title,$text,TRUE);
			else $fback=FALSE;
			if ($fback)
			{
				//SUCCESS, and redirect to conversation page
				redirect(site_url('msg/conver/'.$to.'#last'));
			}
			else
			{
				//FAIL AND RETURN CODE NUMBER
				$a['err_code']=$this->mes->get_err_code();
				$a['err_msg']=$this->mes->get_err_msg();
				$this->load->view('msg/conver',$a);
			}
		}
	}
	
	public function update($op)
	{
		switch($op)
		{
			case 'read':	//change read status with user id=$oid
				if (is_numeric($this->input->get('oid')))
				{
					if ($this->mes->read($this->input->get('oid')))
						echo 0;
					else
						echo $this->mes->get_err_msg();
				}
				else
					echo 'Wrong data';
			break;
			case 'getmsg':
				if ($this->input->get('eid') && $this->input->get('lid'))
				{
					//get message from eid to uid
					$list1=array();
					$where1=array('id >'=>$this->input->get('lid'),
								  'auth'=>$this->input->get('eid'),
								  'to'=>$this->uid
								  );
					$list1=$this->mes->get_list($where1,'id desc',FALSE,'*',10,0);
					//get message from uid to eid
					$list2=array();
					$where2=array('id >'=>$this->input->get('lid'),
								  'auth'=>$this->uid,
								  'to'=>$this->input->get('eid')
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
					$this->load->library('json');
					echo $this->json->encode($return);
				}
				else 
					echo 'Nothing recieved';
			break;
			default:
				show_404();
			break;
		}
	}
	
	public function ulist($op)
	{
		switch($op)
		{
			case 'add'://add friend, want to add friend
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
							array_push($data,$this->input->get('to'));
							if ($this->user->set_ap($this->uid,'mes_user_list',$data)&&
						    	$this->user->invite($this->input->post('to'),$comment))
								echo 0;
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
			case 'del'://delete friend
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
			break;
			case 'accept'://accept friend request
				if ($this->input->get('oid'))
				{
					if ($this->user->accept($this->input->get('oid')))
					{
						$data=$this->user->get_ap($this->uid,'mes_user_list');
						if ($data==0)
						{
							$new=array();
							array_push($new,$this->input->get('uid'));
							if ($this->user->set_ap($this->uid,'mes_user_list',$new))
								echo 0;
							else
								echo $this->user->get_err_msg();
						}
						else
						{
							if (! in_array($this->input->get('uid'),$data))						
							{
								array_push($data,$this->input->get('uid'));
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
						$this->load->library('json');
						echo $this->json->encode($flist);
					}
				}
				else
					echo 'Nothing Recieved';
			break;
			case 'get'://get user info
				if (is_int($this->input->get('lid')))
					$off=$this->input->get('lid')*20;
				else
					$off=1;
				$ulist=$this->user->get_user_list('*',20,$off,'cnln desc');
				$ulist['length']=count($ulist);
				$this->load->library('json');
				echo $this->json->encode($ulist);
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
					$this->load->library('json');
					echo $this->json->encode($flist);
				}
				else
					echo $this->user->get_err_msg();
			break;
			default:
				show_404();
			break;
		}
	}
}

/* End of file msg.php */
/* Location: ./application/controllers/msg.php */