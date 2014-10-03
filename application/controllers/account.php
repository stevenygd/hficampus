<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Entrance.php by StevenY.(Originally)
 * Modified to account.php by halfcoder
 */
class Account extends SAE_Controller {
	
	var $uid;
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->uid = $this->user->id;
<<<<<<< HEAD
        $this->layout = 'account';
=======
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
	}
	
	/*
	 * Main Entrance
	 * 入口主页:如果登陆则跳出用户主页,否则则跳出登陆页面
	 */
	public function index()
	{
		if($this->uid !== FALSE) {
			//登陆了就转跳吧...
			redirect('home');
		}
		else {
			$this->push('account/index',array(
				'login_key'=> $this->user->get_key()
			));
		}
	}
	
	/*
	* 登陆接口 Login Port
	* 将登陆信息post到这个接口
	* @post:uname=user_name pw=password(sha1(pw_plain.login_key))
	*/
	public function login()
	{
		//表单验证规则已移到config/form_validation.php中
		if ($this->form_validation->run() === FALSE)
			$this->index();
		else
		{
			$uname = $this->input->post('uname',TRUE);

			if($this->user->login($uname,$this->input->post('pw',TRUE)))
			{
				$this->uid=$this->user->id;
				//just for jeremy
/*				
				if (($uname=='jeremy') && 
				    (end($this->db->get_where('user',array('id'=>4))->result())->create_time==NULL))
				{
					redirect('home/hello');
				}
*/
				if (end($this->db->get_where('user',array('uname'=>$uname))->result())->latest_login==NULL)
				{
					redirect('home/hello');//load welcome page
				}
				else
					$this->index();
			}
			elseif ($this->user->get_err_code() == 102)//user was not activated
			{
				//@todo
				$a['headline']='You are almost complete!';//headline was the sentence below header
				$a['uname']=$uname;//@todo get user name
				$this->push('account/veriemail',$a);
			}
			else 
			{
				$a['err_code']=$this->user->get_err_code();
				$a['err_msg']=$this->user->get_err_msg();
				$a['login_key']=$this->user->get_key();	
				$this->push('account/index',$a);
			}
		}
	}
	
	/*注销登陆接口*/
	public function logout()
	{
		$this->user->logout();
		redirect(site_url('account'));
	}
	
	/*注册页面*/
	public function register()
	{
		//rsa加密的mudulus
		$this->load->library('rsa');
		$a['n']=$this->rsa->get_modulus();
		$this->push('account/register',$a);
	}
		
	/*
	* 注册接口(confirm之后发注册信息到这里)
	* @post: uname=user_name; pw=password: rsa(sha1(password_plain)); email=user_email_address;
	* @post: cngname=Chinese_first_name; cnfname=Chinese_last_name; y=year_enrolled
	*/
	public function regi()
	{
		//表单验证规则已移到config/form_validation.php中
		//验证注册信息
		if ($this->form_validation->run() === FALSE)
		{
			$this->load->library('rsa');
			$a['n']=$this->rsa->get_modulus();
			$this->push('account/register',$a);
		}
		else
		{
			//RSA decryption
			//加载rsa类
			$this->load->library('rsa');
			$this->rsa->load_privatekey();
			$this->rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
			//密码解密			
			$pword = $this->rsa->decrypt(base64_decode($this->input->post('pw')));
			//获取其他信息
			$uname = $this->input->post('uname');
			$email = $this->input->post('email');
			$id     = $this->input->post('id');
			$cnfn  = $this->input->post('cngname');
			$cnln  = $this->input->post('cnfname');
			$ck=$this->user->regi_chk($uname,$pword,$cnfn,$cnln,$id,$email);
			if ($ck === FALSE)//register fail
			{
				$a['err_code']=$this->user->get_err_code();
				$a['err_msg']=$this->user->get_err_msg();
				$this->load->library('rsa');
				$a['n']=$this->rsa->get_modulus();
				$this->push('account/register',$a);
			}
			else//register success,$ck=email veri url
			{
				//prepare the data of validation email
				$veri_url=$this->user->regi_url('account/email_veri/register',$uname);
				$emaildata='Please click this link to activate your account:<a href="'.$veri_url.'">'.$veri_url.'</a>';
				
				//send validation email
				if (! $this->user->send_email($email,$emaildata,'*'))
					$a['email_err']=$this->user->get_err_msg();	//if fail, then record the error.
				else 
					$a['email_err']=TRUE;
				
				$a['headline']='You are almost complete!';//headline was the sentence below header
				$a['uname']=$uname;
				$this->push('account/veriemail',$a);
			}
		}
	}
	
	//Lost 页面
	public function lost()
	{
		$this->push('account/lost');
	}
	
	/*
	* 重新发送邮件 resend email
	* @param $str $case two options:'register' or 'lostpw'
	* register: @get: uname=user_name(not encrypted)
	* lostpw: return false information(don't allow to resend lost password email for security consideration)
	* @todo: add loas_name resend?
	*/
	public function resend($case)
	{
		switch ($case)
		{
			case 'register'://resend register email, get uname=user name
				if ($this->input->get('uname'))
				{
					if ($this->user->regi_resend($this->input->get('uname')))//@todo
					{
						$a['headline']='Email has been resent.';
						$a['email_err']=TRUE;
						$a['uname']=$this->input->get('uname');
					}
					else
					{
						$a['headline']='We fail to resend the varification email. Please try again.';
						$a['email_err']=$this->user->get_err_msg();
						$a['uname']=$this->input->get('uname');
					}
					$this->push('account/veriemail',$a);
				}
				else
				{
					$a['err_msg']="Sorry, we fail to resend the varification email.";
					$a['login_key']=$this->user->get_key();	
					$this->push('account/index',$a);
				}
				break;
			case 'lostpw'://resend @todo
				$a['lost_pw']['err_msg']='Sorry, we cannot simply resend the password recieve email. Please enter the information.';
				$this->push('account/lost',$a);	
				break;
			default:
		}
	}
	
	
	/*
	* 邮箱验证接口
	* @param $str $case tow options:"reset" or "register"
	* risger:@get: u=user_name(AES encrypted)
	* register:@get:d=time(AES encrypted); u=user_name(AES encrypted); r=key(AES encrypted)
	*/
	public function email_veri($case)
	{
		switch($case)
		{
			case 'register'://email_veri for register
				if ($this->input->get('u'))
				{
					if ($this->user->regi_veri($this->input->get('u')))
					{
						redirect('home');
					}
					else //($this->user->get_err_code() == 311)
					{//@todo
						$err['err_code']=$this->user->get_err_code();
						$err['err_msg']=$this->user->get_err_msg();
						$err['login_key']=$this->user->get_key();
						$this->push('account/index',$err);
					}
				}
				else
				{
					$err['err_code']=403;
					$err['err_msg']="sorry I didn't get anything";
					$err['login_key']=$this->user->get_key();
					$this->push('account/index',$err);
				}			
			break;
			case 'reset'://@todo
				if ($this->input->get('d') && $this->input->get('u') && $this->input->get('r'))
					if (! $this->user->lost_verti($this->input->get('u'),$this->input->get('d'),$this->input->get('r')))
					{
						$a['headline']=$this->user->get_err_msg();
						$this->push('account/lost_status',$a);
					}
					else
						$this->push('account/reset');
				else
				{
					$err['lost_pw']['err_code']=100;
					$err['lost_pw']['err_msg']="sorry I didn't get anything";
					$this->push('account/lost',$err);
				}
			break;
			default:
				show_404();
			break;
		}
	}
	
	/*
	* 找回密码提交处
	* post: uname=user_name email=email_address
	*/
	public function lost_pw()
	{
		if ($this->input->post('uname') && $this->input->post('email'))
		{
			$url=$this->user->lost_get_url($this->input->post('uname'),$this->input->post('email'));
			if ($url === FALSE )
			{
				$err['lost_pw']['err_code']=$this->user->get_err_code();
				$err['lost_pw']['err_msg']=$this->user->get_err_msg();
				$this->push('account/lost',$err);
			}
			elseif ($url)
			{
				$emaildata='Please click this link to jump into password reset page:<a href="'.$url.'">'.$url.'</a>';
				if ($this->user->send_email($this->input->post('email'),$emaildata,'*'))
				{
					$a['headline']='An email containing your account information has been sent.';
					$this->push('account/lost_status',$a);
				}
				else
				{
					$err['lost_pw']['err_code']=$this->user->get_err_code();
					$err['lost_pw']['err_msg']=$this->user->get_err_msg();
					$this->push('account/lost',$err);
				}
			}
		}
		else
		{
			$err['lost_pw']['err_code']=10101;
			$err['lost_pw']['err_msg']='Nothing Recieved';
			$this->push('account/lost',$err);
		}
	}
	
	/*
	* 找回用户名
	* @bugs
	*/
	public function lost_name()
	{
		//表单验证规则已移到config/form_validation.php中
		if ($this->form_validation->run() === FALSE)
			$this->push('account/lost');
		else
		{
			$u=$this->user->lost_get_uname($this->input->post('cnln'),$this->input->post('cnfn'),$this->input->post('y'),$this->input->post('email'));
			if ($u === FALSE)//fail
			{
				$err['lost_name']['err_code']=$this->user->get_err_code();
				$err['lost_name']['err_msg']=$this->user->get_err_msg();
				$this->push('account/lost',$err);
			}
			else
			{
				$emaildata='The user name for your account is:'.$u['uname'].'</br>You can click this link to login:<a href="'.site_url().'">'.site_url().'</a>';
				if ($this->user->send_email($u['email'],$emaildata,'*'))
				{
					$a['headline']='An email containing your account information has been sent.';
					$this->push('account/lost_status',$a);
				}
				else
				{
					$err['lost_name']['err_code']=$this->user->get_err_code();
					$err['lost_name']['err_msg']=$this->user->get_err_msg();
					$this->push('account/lost',$err);
				}
			}
		}
	}
	
	/*
	* 找回密码中创建新密码连接
	* @post: npw=new_pass_word(RSA(sha1(plain)) encrypted)
	*/
	public function pwreset()
	{
		if ($this->input->post('npw'))
		{
			$this->load->library('rsa');
			$this->rsa->load_privatekey();
			$this->rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
			$crypt=$this->rsa->dncrypt(base64_decode($this->input->post('npw')));
		
			//$crypt=sha1($this->input->post('npw'));//for test
			if ($this->user->lost_setnp($crypt))
				redirect('home');
			else
			{
				$a['lost_pw']['err_code']=$this->user->get_err_code();
				$a['lost_pw']['err_msg']=$this->user->get_err_msg();
				$this->push('account/lost',$a);
			}
		}
		else
		{
			$a['lost_pw']['err_msg']='Nothing received.';
			$this->push('account/lost',$a);
		}
	}
	
	//Security page, when changing important information, using this page to verify
	public function security()
	{
		//check if it's logined.
		if ($this->session->userdata('login')!==TRUE)
			redirect(site_url('account'));

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
		if ($this->session->userdata('login')!==TRUE)
			redirect(site_url('account'));
		
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
		//check if it's logined.
		if ($this->session->userdata('login')!==TRUE)
			redirect(site_url('account'));

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
	
	/**
	 * Application
	 * Apply to join some group
	 */
	public function apply()
	{
		if ($this->input->post('gid') && $this->input->post('message'))
		{
			
		}
		else
		{
			
		}
	}
	/**
	 * Get User Information
	 */
	public function search()
	{
		//check if it's logined.
		if ($this->session->userdata('login')!==TRUE)
			redirect(site_url('account'));

		if ($this->input->get('search'))
		{
			$flist=$this->user->search(array('cnln','cnfn','enn','uid'),
									   $this->input->get('search'),
									   'cnln desc',20,0);
			if ($flist===FALSE)
				echo $this->user->get_err_msg();
			else
			{
				$this->push(NULL,$flist);//only ajax
			}
		}
		else
			echo 'Nothing Recieved';
	}
	
	/**
	 * Get User Information
	 */
	public function fetch()
	{
		//check if it's logined.
		if ($this->session->userdata('login')!==TRUE)
			redirect(site_url('account'));

		if (is_numeric($this->input->get('off')))
			$off=$this->input->get('off')*20;
		else
			$off=0;
		
		if (is_numeric($this->input->get('lim')))
			$lim=$this->input->get('lim');
		else
			$lim=20;
		
		$wherein=array();
		if ($this->input->get('id'))
		{
			trim($this->input->get('id'),',');
			$uid=explode(',',$this->input->get('id'));
			$wherein['uid']=$uid;
		}
				
		if (count($wherein) == 0)
			$where='*';
			
		$ulist=$this->user->get_user_list($wherein,$lim,$off,'cnln desc');
		$ulist['length']=count($ulist);
		$this->push(NULL,$ulist);
	}
<<<<<<< HEAD
	
=======

>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */