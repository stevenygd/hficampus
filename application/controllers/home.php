<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*HOME CONTROLLER BY STEVEN YANG*/
class Home extends SAE_Controller {
	 
	var $uid;
	 
	function __construct()
	{
		parent::__construct();
		$this->uid = $this->user->id;
	}
	 
	public function index()
	{
		$user_info=$this->user->get_info($this->uid);
		$a = $user_info;
		
		//adjust variables names to the front end requirement
		$a['ename']=$user_info['user_info']['enn'];
		$a['cname']=ucfirst($user_info['user_info']['cnln']).', '.ucfirst($user_info['user_info']['cnfn']);
		
		/* 2014-1-11 for ulibrary project only */
		if (isset($user_info['user_info']['appendix']['ulib']['comp_id']))
		{
			$a['ulib']=$user_info['user_info']['appendix']['ulib']['comp_id'];
		}
		else
		{
			$a['ulib']=FALSE;
		}

		$a=$this->user->get_info($this->user->id);
		
		//get user subjection
		$sub=$this->db->get_where('user_sub',array('uid'=>$this->uid))->result_array();
		$gids=array();
		foreach($sub as $item)
		{
			array_push($gids,$item['gid']);
		}
		
		//get course,club, and notice number
		$info=array(
					'aca'=>'course',
					'club'=>'club',
					'not'=>'notice',
					'calender_event'=>'event'
					);
					
		if (count($gids)>0)
			foreach ($info as $table_name=>$var_name)
			{
				$this->db->where_in('gid',$gids);
				$a['num']['my'.$var_name]=$this->db->get($table_name)->num_rows();
			}
			
		//get exact event number
		$this->db->where('auth',$this->uid);
		$this->db->where('gid',0);
		$a['num']['myevent']=$a['num']['myevent']+$this->db->get('calender_event')->num_rows();
		
		//get message
		$a['num']['mymessage']=$this->db->get_where('msg',array('to'=>$this->uid,'read'=>FALSE))->num_rows();
		
		//set output boxes info
		$a['boxes']=$info;
		$a['boxes']['msg']='message';

		$this->push('home/main',$a);
	}
	
	public function hello()
	{
		$this->load->library('form_validation');
		$a=$this->user->get_info($this->uid);
		$this->push('home/hello',$a);
	}
	
	public function welcome()
	{
		$this->load->library('form_validation');
		$rules=array(
					array(
						'field'=>'uname',
						'label'=>'user name',
						'rules'=>'min_length[5]|is_unique[user.uname]'
						),
					array(
						'field'=>'pw',
						'label'=>'password',
						'rules'=>'trim'
						),
					array(
						'field'=>'email',
						'label'=>'security email',
						'rules'=>'valid_email'
						),
					array(
						'field'=>'cnfn',
						'label'=>'Chinese first name',
						'rules'=>'required'
						),
					array(
						'field'=>'cnln',
						'label'=>'Chinese last name',
						'rules'=>'required'
						),
					array(
						'field'=>'enn',
						'label'=>'English name',
						'rules'=>'required'
						),
					);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()===FALSE)
			$this->push('home/hello');
		else
		{
			$this->db->trans_begin();
			if ($this->input->post('uname'))
				$this->db->update('user',array('uname'=>$this->input->post('uname')),array('id'=>$this->uid));
			if ($this->input->post('pw'))
				$this->db->update('user',array('pword'=>sha1($this->input->post('pw'))),array('id'=>$this->uid));
			if ($this->input->post('email'))
				$this->db->update('user',array('email'=>$this->input->post('email')),array('id'=>$this->uid));
			$data=array(
						'cnfn'=>$this->input->post('cnfn'),
						'cnln'=>$this->input->post('cnln'),
						'enn'=>$this->input->post('enn')
						);
			$this->db->update('user_info',$data,array('uid'=>$this->uid));
			if ($this->db->trans_status()===FALSE)
			{
				$this->db->trans_rollback();
				$a=$this->user->get_info($this->uid);
				$this->push('home/hello',$a);
			}
			else
			{
				$this->db->trans_commit();
				$this->push('home/team');
			}
		}
	}
	
	public function api($option){
		switch ($option){
			case "channel_connected":
				$channelName = $this->input->post('from');
			    //从mc中读取channel列表
				$channelList = $this->channel->rChannelOnline();
				//客户端连接
		        $channelList[$channelName] = time();
		        $this->channel->wChannelOnline($channelList);

				$kv = new SaeKV();
				$kvinit = $kv->init();
				//update KVDB record
				if ($kv->add($this->uid . ':channel:last_connected_time', time()) === FALSE){
					$kv->replace($this->uid . ':channel:last_connected_time', time());
				}
				$this->data['test']=$channelList;
				$this->push();
			break;
			case "channel_closed":
				$channelName = $this->input->post('from');
			    //从mc中读取channel列表
				$channelList = $this->channel->rChannelOnline();
				//客户端断开
				unset($channelList[$channelName]);
				$this->channel->wChannelOnline($channelList);
				
				$kv = new SaeKV();
				$kvinit = $kv->init();
				//update KVDB record
				if ($kv->add($this->uid . ':channel:last_closed_time', time()) === FALSE){
					$kv->replace($this->uid . ':channel:last_closed_time', time());
				}
				//创建SAE
				$this->data['channelURL'] = $this->channel->createChannel(CHANNEL_PREFIX . $this->uid, 3600);//channel default expire time 3600
	
				//set a coockie to save this url
				$cookie = array(
					'name'   => 'channelURL',
					'value'  => $this->channel->getChannelURL(),
					'expire' => '3600',
					'prefix' => 'hfi_',
					//'secure' => TRUE @bug jquery.cookie.js can't read secure cookie value
				);
				$this->input->set_cookie($cookie);
				$this->push();//@todo
			break;
			case 'channel_message':
				
			break;
			default:
				show_404();
			break;
		}
	}

	
}
/* End of file home.php */
/* Location: ./application/controllers/home.php */