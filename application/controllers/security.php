<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Entrance.php by StevenY.(Originally)
 * Modified to account.php by halfcoder
 */
class Security extends SAE_Controller {
	
	var $uid;
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->uid = $this->user->id;
	}
	
	public function index(){
		
	}
	
	//Security page, when changing important information, using this page to verify
	public function security()
	{
		$info=$this->user->get_info($this->uid);
		$a['uname']=$info['user']['uname'];
		$a['key']=$this->user->get_key();
		$this->push('account/security',$a);//@todo
	}

	//user setting options
	//home/setting         show user information page(main page)
	//../../secchk        verify security check
	//../../chpw           change password
	//../../chemail        change security email
	//../../chinfo         change user information other than security information
	public function setting($op = '')
	{
		//check if it's logined.
		if (! isset($op)) $op='';
		switch($op)
		{
			//Check security
			//security info(sha1(sha1(Plain_password)+random_key)) post to this link
			case 'secchk':
				if ($this->input->post('pw'))
				{
					if ($this->user->security($this->input->post('pw')))
					{
						$url=$this->session->userdata('sec_url');
						$this->session->unset_userdata('sec_url');
						redirect($url);
					}
					else
					{
						$a['err_msg']=$this->user->get_err_msg();
						$a['err_code']=$this->user->get_err_code();
						$a['key']=$this->user->get_key();
						$this->push('account/security',$a);//@todo
					}
				}
				else
					echo "I don't recieve anything";
			break;
			//change password, new password:RSA(sha1(plain_password)) post to this link
			case 'chpw':
				if ($this->input->post('password'))
				{
					if ($this->user->chpw($this->input->post('password')))
						echo 0;
					elseif($this->user->get_err_code()==501)
						redirect('home/security');
					else
						echo $this->user->get_err_msg();
				}
				else
					echo "I don't recieve anything";
			break;
			case 'chemail':
				if ($this->input->post('email'))
				{
					if ($this->input->post('uid'))
						$uid=$this->input->post('uid');
					else
						$uid=$this->uid;
					if ($this->user->chemail($uid,$this->input->post('email')))
						echo 0;
					elseif($this->user->get_err_code()==1801)
						redirect('home/security');
					else
						echo $this->user->get_err_msg();
				}
				else
					echo "I don't recieve anything";
			break;
			case 'chinfo':
				$info=array();
				foreach (array('cnfn','cnln','enn') as $item)
					if ($this->input->post($item))
						$info[$item]=$this->input->post($item);
				if ($this->input->post('uid')) 
					$uid=$this->input->post('uid');
				else
					$uid=$this->uid;
				if ($this->user->chinfo($uid,$info))
					echo 0;
				else
					echo $this->user->get_err_msg();
			break;
			default:
				if (! $this->user->sec_chk())
				{
					$this->session->set_userdata('sec_url','account/setting/');
					redirect('account/security');
				}
				$user_info=$this->user->get_info($this->uid);
				$this->load->library('rsa');
				$a['n']=$this->rsa->get_modulus();
				$a['user']=array(
								"english_name"=>$user_info['user_info']['enn'],
								"name"=>$user_info['user']['uname'],
								"password"=>'',
								"first_name"=>$user_info['user_info']['cnfn'],
								"last_name"=>$user_info['user_info']['cnln'],
								"email"=>$user_info['user']['email']
								);
				$this->push('account/setting',$a);//@todo
			break;
		}
	}
	
	public function admin($op = '')
	{
		if (! isset($op)) $op='';
		switch($op)
		{
			case 'adduser':
				if ($this->form_validation->run()===FALSE)
					echo validation_errors();
				else
				{
					$type=$this->input->post('type');
					$uname=$this->input->post('uname');
					//test version
					$pw=sha1($this->input->post('pw'));
					$email=$this->input->post('email');
					if ($this->user->add($type,$uname,$pw,$email))
						echo 0;
					else
						echo $this->user->get_err_msg();
				}
			break;
			case 'addgroup':
			break;
			default:
				$this->push('account/admin');
			break;
		}
	}


}