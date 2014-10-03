<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*user model by StevenY.*/
class User extends CI_Model {
	
	var $err_code=0;
	var $err_msg='';
	var $id = FALSE;
	var $login = FALSE;

	function __construct()
	{
		parent::__construct();
		
		//check login
		if ($this->session->userdata('login')===TRUE)
		{
			// 已经成功登陆
			$this->id = $this->session->userdata('uid');
						
			//创建SAE
			$this->load->library('channel',array('uid'=>$this->id));
			//$this->channel->createChannel(CHANNEL_PREFIX.$this->id,3600);

			//set a coockie to save this url
			$cookie = array(
				'name'   => 'channelURL',
				'value'  => $this->channel->getChannelURL(),
				'expire' => $this->channel->getChannelDuration(),
				'prefix' => 'hfi_',
				//'secure' => TRUE @bug jquery.cookie.js can't read secure cookie value
			);
			$this->input->set_cookie($cookie);
			
			//check permission
			$this->load->library('auth',$this->id);
			if (! $this->auth->get_permission())
			{
				throw new MY_Exception('No Permission',403,'home/main',3);
			}
		}
		else // not logged in
		{			
			//check if the user request the login page
			$request_login = $this->uri->segment(1)=='account' && $this->uri->segment(1)===FALSE;
			
			//check whether the page request satisfy the overide condition
			$this->config->load('users');
			if (is_array($this->config->item('overrides')))
			{
				$override_urls  = $this->config->item('overrides');
				$login_override = FALSE;
				foreach ($override_urls as $item)
				{
					if ($this->uri->segment(1) == $item)
					{
						$login_override = TRUE;
					}
				}
			}
			else
			{
				$login_override = FALSE;
			}
			
			//if the request satisfy either condition, we don't need to redirect the page back to account
			if(! ($login_override || $request_login ))
			{
				redirect(site_url('account'));//回到登陆页面
			}
		}
	}
	
	/*******************************
	* Return Ramdom Key(For login) 	返回随机数列
	* Set the session
	* @return str Random key
	*******************************/
	function get_key()
	{
		$this->load->helper('string');
		$key = random_string('alnum',32);
		
		//设置session记录验证随机数列
		$this->session->set_userdata('key', $key);
		
		//返回验证随机数列
		return $key;
	}
	
	/*login functions*/		
	/*******************************
	* Login Function 登陆验证
	* @param str $uname Username that's going to login
	* @param str $str Crypted Password
	* @return bol whether success
	*******************************/
	function login($uname,$str)
	{
		//取出随机数列
		$login_key=$this->session->userdata('key');		
		$this->session->unset_userdata('key');

		/*获得用户信息*/
		if ($this->db->get_where('user',array('uname'=>$uname))->num_rows() == 1)
		{
			$q=$this->db->get_where('user',array('uname'=>$uname))->row_array();
			
			//检查用户是否已经过期
			if (isset($q['expire_time']) && ($q['expire_time'] < time()))//验证待激活用户是否过期，过期则删除，并返回登陆失败
				if ($this->delete($q['id']))
					return $this->err(101,'Expired User!');
				else
					return FALSE;
		
			/*检验用户是否已经激活*/
			if (! $q['active'])	return $this->err(102,'Please activate the user first');
						
			/*产生服务器端的hash数据*/
			$ser=sha1($q['pword'].$login_key);
			
			/*验证*/
			if ($str == $ser)
			{
				/*更新最后登陆时间以及登陆状态*/
				$this->db->where('id',$q['id']);
				$this->db->update('hfi_user',array('latest_login'=>date('y-m-d H-i-s',time())));
				
				//更新session
				$s_data=array('login'=>TRUE,'uid'=>$q['id']);
				$this->session->set_userdata($s_data);
				
				//set security session
				$this->session->set_userdata('security',TRUE);
				$this->session->set_userdata('sec_time',time()+60);

	
				//设置登陆数据
				$this->id=$q['id'];
				return $this->err(0,0); /*登陆成功*/
			}
			else
				return $this->err(104,'Wrong user name and password combination.');
		}
		else
			return $this->err(104,'Wrong user name and password combination.');
	} 
	
	/*******************************
	* Logout 退出登陆
	* Delete the session
	* @return bol TRUE
	*******************************/
	function logout()
	{		
		//清除session
		if ($this->session->userdata('login'))
			$this->session->unset_userdata('login');
		$this->session->unset_userdata('uid');
		$this->session->unset_userdata('key');
		return $this->err(0,0);
	}
	
	/**
	 * Add special user
	 * 
	 * @param string $type 'aca','office','developer'
	 * @param string $uname username
	 * @param string $pw password sha1(plain_text)
	 * @param string $email security email
	 * @return wheter success
	 */
	function add($type,$uname,$pw,$email)
	{
		$this->db->trans_begin();
		$data=array(
					'uname'=>$uname,
					'pword'=>$pw,
					'email'=>$email,
					'active'=>TRUE,
					'create_time'=>date('y-m-d H:i:s',time())
					);
		$this->db->insert('user',$data);
		$uid=$this->db->insert_id();
		$data=array(
					'uid'=>$uid,
					'cnfn'=>'',
					'cnln'=>'',
					'enn'=>'',
					'appendix'=>serialize(array())
					);
		$this->db->insert('user_info',$data);
		$gid=FALSE;
		switch($type)
		{
			case "aca":
				//check permission (not auth functions since it concerns with user and sub tables)
				$this->db->where_in('gid',array(0,1,2,3));//group ids that have permission
				$this->db->where('uid',$this->id);
				if ($this->db->get_where('user_sub')->num_rows()!=0)
					$gid=2;
				else
				{
					$this->db->trans_rollback();
					return $this->err(40404040,'database error');
				}
			break;
			case "office":
				//check permission (not auth functions since it concerns with user and sub tables)
				$this->db->where_in('gid',array(0,1,3));//group ids that have permission
				$this->db->where('uid',$this->id);
				if ($this->db->get_where('user_sub')->num_rows()!=0)
					$gid=3;
				else
				{
					$this->db->trans_rollback();
					return $this->err(40404040,'database error');
				}
			break;
			case "developer":
				//check permission (not auth functions since it concerns with user and sub tables)
				$this->db->where_in('gid',array(0,1));//group ids that have permission
				$this->db->where('uid',$this->id);
				if ($this->db->get_where('user_sub')->num_rows()!=0)
					$gid=1;
				else
				{
					$this->db->trans_rollback();
					return $this->err(40404040,'database error');
				}
			break;
			default:
				$this->db->trans_rollback();
				return $this->err(40404040,'database error');
			break;
		}
		$this->db->insert('user_sub',array('uid'=>$uid,'gid'=>$gid));
		$this->db->insert('user_sub',array('uid'=>$uid,'gid'=>5));
		
		//syn permission
		$this->load->library('permission');
		try{
			$this->permission->syn($uid);
		}catch(Exception $e){
			$this->db->trans_rollback();
			return $this->err(101010101,$e->getMessage());
		}
		
		if ($this->db->trans_status()===FALSE)
		{
			$this->db->trans_rollback();
			return $this->err(40404040,'database error');
		}
		else
		{
			$this->db->trans_commit();
			return $this->err(0,0);
		}
	}
	
	/*register functions*/
	/*******************************
	* Check Register Infor and Send valified email
	* @WARNING Remember to type in the sha1()
	* @param str $uname username
	* @param str $pword sha1ed password(not RSA encrypted)
	* @param str $cnfn Chinese First Name
	* @param str $cnln Chinese Last Name
	* @param int $member_id the member id the user have in school
	* @param str $email The registered email
	* @return bol whether success
	*******************************/
	function regi_chk($uname,$pword,$cnfn,$cnln,$member_id,$email)
	{
		//用户自动审核:哪一届+中文名,表名:hfi_user_veri
		$veri=$this->db->get_where('user_veri',	array( 'cnfn'=>strtoupper($cnfn),
													   'cnln'=>strtoupper($cnln),
													   'member_id'=>$member_id));
		if ($veri->num_rows() != 1) return $this->err(201,'Wrong User Info');	
	
		/*验证用户名*/
		if ($this->db->get_where('user',array('uname'=>$uname))->num_rows() > 0)
			return $this->err(202,'User Name Already Exist');
		
		/*验证邮箱*/
		if ($this->db->get_where('user',array('email'=>$email))->num_rows()>0)
			return $this->err(203,'Email has already been registered.');

		$this->load->helper('email');
		$this->load->library('email');
		if (! valid_email($email))
			return $this->err(204,'Invalid email adress');
	  
	  
		/*将未激活账户存进数据库*/
		$this->db->trans_begin();
		
		//将未激活用户保存在user表
	  	//$etime=time()+24*3600;//待激活用户24小时后过期
	  	$a=array(
			'member_id'=>$member_id,
			'uname'=>$uname,
			'pword'=>$pword,/*密码明文sha1后保存,前端hash过之后发送*/
			'email'=>$email,
			'active'=>FALSE,
			'create_time'=>date('y-m-d H:i:s'),
			'expire_time'=>time()+24*3600//待激活用户24小时后过期
			);
		$this->db->set($a);
		$this->db->insert('user');
		
		//将veri表的信息转移到user_info表
		//$q=$this->db->get_where('user',array('uname'=>$uname))->row_array();
		$qid      = $this->db->insert_id('user',array('uname'=>$uname));
		$enn      = end($veri->result())->enn;
		$appendix = end($veri->result())->appendix;
		$a=array(
				'cnfn'     => strtoupper($cnfn),
				'cnln'     => strtoupper($cnln),
				'enn'      => strtoupper($enn),
				'uid'      => $qid,
				/* 2014-1-11 adopt the situation if the user is used before registered */
				'appendix' => $appendix 
				);
		$this->db->set($a);
		$this->db->insert('user_info');
		
		//数据库query检查完毕
		if($this->db->trans_status() === FALSE)
		{
			//错误则滚回
			$this->db->trans_rollback();
			return $this->err(206,"Can't insert in database.");
		}
		else  
		{
			$this->db->trans_commit();
			return $this->err(0,0);
		}  
	}
	
	/*******************************
	* Create verification url for registration 产生注册验证url
	* @param str $url the starting url of the link, not including the beginning root url 
			 (eg. 'entrance/login')
	* @param arr $a the array('field_name'=>$a(the contain that's going to be encrypted))
	* @return str the encrypted string
	*******************************/
	function regi_url($url,$uname)
	{
		//check input
		$this->load->library('aes');
		$this->config->load('email');
		$this->aes->setkey($this->config->item('email_key'));
		$veri_url=site_url($url).'?u='.urlencode(base64_encode($this->aes->encrypt($uname)));
		$this->err(0,0);
		return $veri_url;
	}

	/*******************************
	* Check Veri Info of the email 验证验证邮件并注册
	* @param str $crypt the returned crypted 
	* @return bol Whether success
	*******************************/
	function regi_veri($crypt)
	{
		/*解密用户名*/  
		$this->load->library('aes');
		$this->config->load('email');
		$this->aes->setkey($this->config->item('email_key'));
		$uname=$this->aes->decrypt(base64_decode($crypt));
				
		/*获取数据库中记录*/
		if ($this->db->get_where('user',array('uname'=>$uname))->num_rows() != 1) 
			return $this->err(311,'The link has been expired, please try again.');

		$da=$this->db->get_where('user',array('uname'=>$uname))->row_array();
				
		//检查激活信息
		if ($da['active'])
			return $this->err(301,'The user has already been activated');
		
		if ($this->db->get_where('user',array('uname'=>$uname))->num_rows()!=1)
			return $this->err(310,'what the hell!');
		
		//验证待激活用户是否过期，过期则删除，并返回链接已过期
		if (! (isset($da['expire_time']) && ($da['expire_time']>time())))
		{
		  //删除信息
		  $this->db->delete('user_info',array('uid'=>$da['id']));
		  $this->db->delete('user',array('id'=>$da['id']));
		  return $this->err(302,'The teperory user had been expired. Please register again.');
		}
	
		//通过验证，将用户信息写入数据库
		$this->db->trans_begin();
		
		//修改用户激活信息
		$this->db->where('id',$da['id']);
		$this->db->update('user',array('active'=>TRUE,'expire_time'=>NULL));
		
		//确认用户隶属信息
		$a=array(
				'uid'=>$da['id'],
				'gid'=>4 //学生用户组的
				);
		$this->db->set($a);
		$this->db->insert('user_sub');
		
		$a=array(
				'uid'=>$da['id'],
				'gid'=>5 //everybody组
				);
		$this->db->set($a);
		$this->db->insert('user_sub');
		
		//将veri表该信息删除，不可重复验证
		$d=$this->db->get_where('user_info',array('uid'=>$da['id']))->row_array();
		$this->db->delete('user_veri',array('cnln'=>$d['cnln'],'cnfn'=>$d['cnfn'],'member_id'=>$da['member_id']));
						
		//同步权限
		$this->load->library('permission');
		$this->permission->syn($da['id']);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return $this->err(304,'Fail!');
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_userdata('login',TRUE);
			$this->session->set_userdata('uid',$da['id']);
			return $this->err(0,0);//激活成功
		}
	}
	
	/*******************************
	* Resend verification email for registration. 重新发送注册邮件
	* @param str $uname the user name that's going to request to resend the email
	* @bugs can be bugs 
	* @return bol Whether success
	*******************************/
	function regi_resend($uname)
	{
		//check input data
		if ($this->db->get_where('user',array('uname'=>$uname,'active'=>FALSE))->num_rows() != 1)
			$this->err(1701,'Wrong Input');
		
		//constitude email data
		$email=end($this->db->get_where('user',array('uname'=>$uname,'active'=>FALSE))->result())->email;
		$veri_url=$this->regi_url('account/email_veri/register',$uname);
		$emaildata='Please click this link to activate your account:'.$veri_url;
		
		if ($this->send_email($email,$emaildata,'*'))
			return $this->err(0,0);
		else
			return FALSE;//error code and error messsage has already been set in $this->send_email();
	}
		
	/*Lost functions*/
	/*******************************
	* Get the veri url for lost password 返回找回密码的验证链接
	* @param str $uname username
	* @param str $email security email of the user
	* @bugs
	*******************************/
	function lost_get_url($uname,$email)
	{
		$d=$this->db->get_where('user',array('uname'=>$uname,'email'=>$email));
		if ($d->num_rows()==1)	
		{
			$da=$d->row_array();
			//用AES加密
			$this->load->library('aes');
			$this->config->load('email');
			$this->aes->setKey($this->config->item('email_key'));//密匙为发起请求的IP地址和随机数列
			$random=random_string('alnum',16);//邮箱验证随机数
			$url=site_url('entrance/email_veri/reset').'?'.'u='.urlencode(base64_encode($this->aes->encrypt($uname))).
							'&d='.urlencode(base64_encode($this->aes->encrypt(time()))).
							'&r='.urlencode($random);//用当前时间和用户名来加密
			//将$random储存如appendix
			$q=$this->db->get_where('user_info',array('uid'=>$da['id']))->row_array();
			$appendix_a=unserialize($q['appendix']);
			$appendix_a['reset_key']=$random;
			$appendix_s=serialize($appendix_a);
			$this->db->update('user_info',array('appendix'=>$appendix_s),array('uid'=>$da['id']));
			$this->err(0,0);				
			return $url;
		}
		else
			return $this->err(1102,"Wrong user information.");
	}

	/*******************************
	* Verify the email link to reset password 验证找回密码链接
	* @param str $u encrypted string for user_name(AES)
	* @param str $d encrypted string for request time(AES)
	* @param str $r encrypted string for reset key(AES)
	*@bugs
	*******************************/
	function lost_verti($u,$d,$r)
	{
		//AES解密
		$this->load->library('aes');
		$this->config->load('email');
		$this->aes->setKey($this->config->item('email_key'));
		$time=$this->aes->decrypt(base64_decode($d));
		$uname=$this->aes->decrypt(base64_decode($u));
		
		//验证用户名
		if ($this->db->get_where('user',array('uname'=>$uname))->num_rows()!=1)	return $this->err(1202,'Invalid link.');
		$d=$this->db->get_where('user',array('uname'=>$uname))->row_array();
		$q=$this->db->get_where('user_info',array('uid'=>$d['id']))->row_array();
		$appendix_a=unserialize($q['appendix']);
		
		//验证随机数是否有效(即链接是否有效)
		if ( (! isset($appendix_a['reset_key'])) || ($appendix_a['reset_key'] === FALSE) || ($appendix_a['reset_key']!=$r)) 
		return $this->err(1203,'Invalid link.');
		
		//验证链接是否过期,有效期为5分钟
		if ($time<(time()-300)) 
		{
			$q=$this->db->get_where('user_info',array('uid'=>$d['id']))->row_array();
			$appendix_a=unserialize($q['appendix']);
			//处理数据库
			$appendix_a['reset_key']=FALSE;
			$appendix_s=serialize($appendix_a);
			return $this->err(1201,'Expried link.');
		}
		
		//处理session
		$this->session->set_userdata('pwreset',TRUE);
		$this->session->set_userdata('uid',$d['id']);
		//处理数据库
		$appendix_a['reset_key']=FALSE;
		$appendix_s=serialize($appendix_a);
		$this->db->update('user_info',array('appendix'=>$appendix_s),array('uid'=>$d['id']));
		
		//返回成功
		return $this->err(0,0);
	}
	
	/*******************************
	* Reset password after lost
	* @WARNING it's better to use RSA encryption in the front-end
	* @WARNING make sure to decrypt the password in the controller
	* @param str $npword the hashed (sha1()) new password
	* @bugs
	* @return bol whether success
	*******************************/
	function lost_setnp($npword)
	{	
		if ($this->session->userdata('pwreset'))
		{
			$this->session->unset_userdata('pwreset');
			$this->session->set_userdata('login',TRUE);
			//新密码解密
			/* @tocp
			$this->load->library('rsa');
			$this->rsa->load_privatekey();//载入私钥
			$this->rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
			$d_pword=$this->rsa->decrypt(base64_decode($npword));
			*/
			//保存新密码
			if (! $this->db->update('user',array('pword'=>$npword),array('id'=>$this->session->userdata('uid'))))
				return $this->err(1301,'Fail,retry please');
				
			return $this->err(0,0); 
		}
		else
		{
			$this->session->unset_userdata('pwreset');
			$this->session->unset_userdata('uid');
			return $this->err(1302,'Wrong Input');
		}
	}	
	
	/*******************************
	* Get Lost Username
	* @param str $email the email address of the user
	* @param str $cnln Chinese First name of the user
	* @param str $cnfn Chinese last name of the user
	* @param int $y the year the user enter the school
	* @bugs
	* @return bol FALSE(if fail)
			  str the array('uname'=>username,'email'=>user email for registered)
	*******************************/
	function lost_get_uname($cnln,$cnfn,$y,$email)
	{
		$info=$this->db->get_where('user_info',array('cnfn'=>$cnfn,'cnln'=>$cnln,'year'=>is_numeric($y)?$y:0));
		if  ($info->num_rows()!=1) 
			return $this->err(1601,"Sorry, we can't help.Please try again with the correct information or contact the Webmaster");
		$uinfo=$this->db->get_where('user',array('id'=>$info->row()->uid));
		if ($uinfo->num_rows() >0)
		{
			$this->load->helper('email');
			if (valid_email($email) && ($uinfo->row()->email == $email))
			{
				$this->err(0,0);
				$a['uname']=$uinfo->row()->uname;
				$a['email']=$uinfo->row()->email;
				return $a;
			}
			else
				return $this->err(1602,'Wrong Input');
		}
		else 
			return $this->err(1603,"Sorry, we can't help.Please try again with the correct information or contact the Webmaster");
	}
	
	/*******************************
	* Send Validation Email
	* @param str $email the email address that's sending to
	* @param str $a tzhe content of the email
	* @return bol Whether success
	* @todo
	*******************************/
	function send_email($email,$content,$config = FALSE)
	{
		//load files
		$this->load->library('email');
		
		//check input
		if (! $this->email->valid_email($email)) return $this->err(1501,'Wrong Input');
		
		//initialize the email library
		if (! is_array($config)) 
		{						
			$send = $this->email->quick_send($email, 
											 'Vertify Email of your Hficampus account',
											 $content, 
											 'hficampus@sina.cn', 
											 'hficampus');

			if ($send)
				return $this->err(0,0);
			else
				$this->email->print_debugger();
		}
		else
		{
			$this->email->initialize($config);

			$this->email->from('hficampus@sina.cn', 'HFI Project');
			$this->email->to($email); 
			$this->email->subject('Vertify Email of your Hficampus account');
			$this->email->message($a); 
	
			if ($this->email->send())
				return $this->err(0,0);
			else
			{
				$this->email->print_debugger();
				return $this->err(1502,$this->email->print_debugger());
			}
		}
	}
	
	/*******************************
	* Delete User(Not uid) 删除其它用户
	* @param int $eid the user that's going to be deleted
	* @return bol Whether success
	*******************************/
	function delete($uid)
	{		
		//验证权限 @todo
		//only root can delete other user
		if (($uid!=$this->id)&&($this->id !=0))	return $this->err(400,'No permission');
				
		/*删除数据库里面该用户的记录*/
		$this->db->trans_begin();
		
		$this->db->delete('user_sub',array('uid'=>$uid));
		$this->db->delete('user_info',array('uid'=>$uid));
		$this->db->delete('user',array('id'=>$uid));
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return $this->err(401,'Fail to Delete the user');
		}
		else
		{
			$this->db->trans_commit();
			return $this->err(0,0);
		}
	}
	
	/*Change info functions 修改用户信息*/
	/*******************************
	//security 验证密码
	* @param str $crypt original password (sha1(original pw)+key) 密码用sha1+随机数列再sha1发送
	* @return bol Whether success 
				  if success, return TRUE, and set security session for 1 minute
	* @debug
	*******************************/
	function security($crypt)
	{
		$check_key=$this->session->userdata('key');
		$this->session->unset_userdata('key');
		//验证旧密码
		$q=$this->db->get_where('user',array('id'=>$this->id))->row_array();
		if (! isset($q['id']))
			return $this->err();//@todo
		
		/*产生服务器端的hash数据*/
		$ser=sha1($q['pword'].$check_key);
			
		/*验证*/
		if (! $crypt == $ser)	return $this->err(502,'Wrong Password and Username Combination');
		
		//处理session
		$this->session->set_userdata('security',TRUE);
		$this->session->set_userdata('sec_time',time()+180);
		return $this->err(0,0);
	}
	
	/*******************************
	* Security check
	* @return bol Whether success
	*******************************/
	function sec_chk()
	{
		if (! (($this->session->userdata('security')=== TRUE) && ($this->session->userdata('sec_time')>= time())))
		{
			$this->session->set_userdata('security',FALSE);
			$this->session->set_userdata('sec_time',0);
			return FALSE;
		}
		else
/*			$this->session->set_userdata('security',FALSE);
			$this->session->set_userdata('sec_time',0);
*/
			return TRUE;
	}
	
	/**
	 * Redirect to do security check
	 * @param string $url the url that, if the security check was passed, the server should redirect to
	 *
	 * @return void
	 */
	function security_redir($url)
	{
		$this->session->set_userdata('sec_url',$url);
		redirect('account/security');
	}

	
	/*******************************
	* Change Current User's Password 用户自己修改密码
	* @param str $npword New Password RSA(sha1(new password))
	* @access public and only able to change the password of the user itself
	* @return bol Whether success
	*******************************/
	function chpw($npword)
	{
		if (! $this->sec_chk())
			return $this->err(501,'Wrong Permission');
		
		//新密码解密
		$this->load->library('rsa');
		$this->rsa->load_privatekey();//载入私钥
		$this->rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$d_pword=$this->rsa->decrypt(base64_decode($npword));
		 
		/*保存新密码*/
		$this->db->where('id',$this->id);
		if($this->db->update('user',array('pword'=>$d_pword),array('id'=>$this->id)))
			return $this->err(0,0);
		else
			return $this->err(504,'Database Error');
	}
	
	/*******************************
	* change user security email 修改用户安全邮箱
	* @param int $uid id of the user that's going to change the password
	* @param str $nemail new email
	* @return bol Whether success
	* @todo
	*******************************/
	function chemail($uid,$nemail)
	{
		//check security
		if (! $this->sec_chk())
			return $this->err(1801,'Wrong Permission');
			
		//check permission
		if ($uid!=$this->id)
		{
			$auth=$this->auth->get('user','w');
			if ($auth === FALSE)
				return FALSE;
			elseif (($auth !==TRUE)&&(! in_array($uid,$auth,TRUE)))
				return $this->err(1802,'Wrong Permission');
		}
		
		//database operation
		if ($this->db->update('user',array('email'=>$nemail),array('id'=>$this->id)))
			return $this->err(0,0);
		else
			return $this->err(1802,'Database Error');
	}
	
	/*******************************
	* 更改其他用户资料, $a包含新用户信息的数组,只能修改非安全类信息
	* @param int $uid id of the user that's going to change
	* @param arr $ninfo=array('cnln'=>Chinese last name,
							  'cnfn'=>Chinese first name,
							  'enn'=>English name,
							)
	* @return whether success
	* @todo
	*******************************/
	function chinfo($uid,$ninfo)
	{
		//check permission
		if ($uid!=$this->id)
		{
			$auth=$this->auth->get('user_info','w');
			if ($auth === FALSE)
				return FALSE;
			elseif (($auth !==TRUE)&&(! in_array($uid,$auth,TRUE)))
				return $this->err(1802,'Wrong Permission');
		}
		
		if (isset($ninfo['appendix']))
			$ninfo['appendix']=serialize($ninfo['appendix']);
			
		if ($this->db->update('user_info',$ninfo,array('uid'=>$uid)))
			return $this->err(0,0);
		else
			return $this->err(1901,'Database Error');
	}
		
	/*Get user information 获取用户信息*/
	/*******************************
	* Get user info
	* @param int $eid is the error code
	* @param int $uid is the error code
	* @return bol FALSE (fail) 
			  array('user'=>array(user basic security info),'user_info'=>array(user other info))
	*******************************/
	function get_info($uid)
	{
		// check permission @todo 
		// invalidate permission check by Steven Yang(who follows Ryan's advice)
		// 2014-4-23
		/*
		if ($uid!=$this->id)
		{
			$this->auth->set('l--r-','all',0,'all',0);
			if (! $this->auth->get_permission())
				return $this->err(700,'No permission!');
		}
		*/

		/*获取信息*/
		$this->db->trans_begin();
		$this->db->select('id,uname,email,create_time,latest_login');
		$p['user']=$this->db->get_where('user',array('id'=>$uid))->row_array();
		$p['user_info']=$this->db->get_where('user_info',array('uid'=>$uid))->row_array();
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return $this->err(701,'Fail to get user information');
		}
		else
		{
			$this->db->trans_commit();
			$p['user_info']['appendix']=unserialize($p['user_info']['appendix']);
			$this->err(0,0);
			return $p;
		}
	} 
	
	/*******************************
	* Get user list
	* @param arr $where_in the where condition (connected by "AND")
				  array('field_name'=>array('value1','value2','value3'...))
	* @param int $lim limit
	* @param int $off offset
	* @return bol FALSE (fail) 
			  array([uid]=>array(user inforamtion))
	*******************************/
	function get_user_list($where_in,$lim,$off,$order)
	{
		if(! is_numeric($lim+$off)) 
		{
			$lim=10;
			$off=0;
		}
		//database operation
		//$this->db->order_by('cnln',"decs");
		if (is_array($where_in))
		{
			$b=TRUE;
			foreach ($where_in as $i=>$item)
			{
				if ($b)	
				{
					if ((count($item)>0) && is_array($item))
					{
						$this->db->where_in($i,$item);
						$b=FALSE;
					}
				}
				else
					$this->db->or_where_in($i,$item);
			}
		}
		$d=$this->db->get('user_info',$lim,$off)->result_array();
		if (is_string($order)) $this->db->order_by($order);
		//organize the data
		$list=array();
		foreach ($d as $i=>$item)
		{
			$list[$item['uid']]['cnfn']=$item['cnfn'];
			$list[$item['uid']]['cnln']=$item['cnln'];
			$list[$item['uid']]['enn']=$item['enn'];
			$list[$item['uid']]['uid']=$item['uid'];
			//$appendix=unserialize($item['appendix']);
			//$list[$item['uid']]['appendix']=$appendix;

		}
		$this->err(0,0);		
		return $list;
	}
	
	/*Appendix Functions*/		
	/**
	 * Get user info from appendix
	 * @param int $uid user id
	 * @param str $value_name label name of appendix
	 * @return bol FALSE (fail) 
	 *		  unserialized value for that particular appendix key 
	 */
	function get_ap($uid,$value_name)
	{
		//check permission @todo
		if ($uid!=$this->id)
		{
			$this->auth->set('l--r-','all',0,'all',0);
			if (! $this->auth->get_permission())
				return $this->err(700,'No permission!');
		}
		
		$appendix=unserialize(end($this->db->get_where('user_info',array('uid'=>$uid))->result())->appendix);
		if (array_key_exists($value_name,$appendix))
			return $appendix[$value_name];
		else
			return 0;
	}
	
	/**
	 * Set user info into appendix
	 * @param int $uid user id
	 * @param str $value_name label name of appendix(key)
	 * @param str $value value of the key
	 * @return bol FALSE (fail) 
	 *		  TRUE 
	 */
	function set_ap($uid,$value_name,$value)
	{
		//check permission @todo
		if ($uid!=$this->id)
		{
			$this->auth->set('l--r-','all',0,'all',0);
			if (! $this->auth->get_permission())
				return $this->err(700,'No permission!');
		}
		
		$appendix=unserialize(end($this->db->get_where('user_info',array('uid'=>$uid))->result())->appendix);
		$appendix[$value_name]=$value;
		$appendix=serialize($appendix);
		if ($this->db->update('user_info',array('appendix'=>$appendix),array('uid'=>$uid)))
			return $this->err(0,0);
		else
			return $this->err(2202,'Database error');
	}
	
	/**
	 * Unset user info in the appendix
	 * @param int $uid user id
	 * @param str $value_name label name of appendix(key)
	 * @return bol FALSE (fail) 
	 * 		       TRUE  (success)
	 */
	function unset_ap($uid,$value_name)
	{
		//check permission @todo
		if ($uid!=$this->id)
		{
			$this->auth->set('l--r-','all',0,'all',0);
			if (! $this->auth->get_permission())
				return $this->err(700,'No permission!');
		}
		
		$appendix=unserialize(end($this->db->get_where('user_info',array('uid'=>$uid))->result())->appendix);
		$appendix[$value_name]='';
		$appendix=serialize($appendix);
		if ($this->db->update('user_info',array('appendix'=>$appendix),array('uid'=>$uid)))
			return $this->err(0,0);
		else
			return $this->err(2202);
	}
	
	/**
	 * Add friend to friend waitlist
	 *
	 * @param int $oid is the object id(whom you want to be friend with)
	 * @return boolean whether success
	 */
	function invite($oid,$description)
	{
		if (! is_numeric($oid)) return $this->err(2301,'Wrong Input'.var_dump($oid));
		$data=array(
					'send'=>$this->id,
					'recieve'=>$oid,
					'description'=>$description
					);
		if ($this->db->insert('waitlist_friend',$data))
			return $this->err(0,0);
		else
			return $this->err(2302,'Database error');
	}
	
	/**
	 * Accept friend from waitlist
	 *
	 * @param int $oid is the object id(whom you want to be friend with)
	 * @return boolean whether success
	 */
	function accept($oid)
	{
		if (! is_numeric($oid)) return $this->err(2301,'Wrong Input');
		$where=array(
					'send'=>$oid,
					'recieve'=>$this->id
					);
		$this->db->where($where);
		if ($this->db->delete('waitlist_friend'))
			return $this->err(0,0);
		else
			return $this->err(2302,'Database error');
	}
	
	/**
	 * Get friend invitation information
	 *
	 * @param int $send invitation sent from which uid
	 * @param int $recieve invitation recieved in which uid
	 * @return FALSE if fail
	 		   array()
	 */
	function get_invitation($send,$recieve,$lim,$off)
	{
		if (is_numeric($send)) $this->db->where('send',$send);
		if (is_numeric($recieve)) $this->db->where('recieve',$recieve);
		$return=$this->db->get('waitlist_friend',$lim,$off);
		if ($return)
			return $return->result_array();
		else
			return $this->err(2401,'Database error');
	}
	
	/**
	 * Add friend list
	 * 
	 * @param string $string search string
	 * @param string $field field name of the table
	 * @return array()
	 */
	function search($field,$string,$order,$lim,$off)
	{
		//@todo check input
		$this->db->where_not_in('id',0);
		if (is_string($order)) $this->db->order_by($order);
		
		if (is_array($field))
		{
			foreach ($field as $item)
			{
				$this->db->or_like($item,$string,'after');
			}
			$return=$this->db->get('user_info',$lim,$off)->result_array();
			return $return;
		}
		else
		{
			$this->db->like($field,$string,'after');
			$return=$this->db->get('user_info',$lim,$off)->result_array();
			return $return;
		}
	}
	
	/**
	 * Error functions
	 * @access private, only use within this class
	 * @param int $code is the error code
	 * @param str $msg is the error message
	 * @return bol whether the function success
	 */
	private function err($id = 500, $msg = 'Internal Server Error')
	{
		if($id!=0)
		{
			if ($id!=500)
				$this->err_code=$id;
			if ($msg != 'Internal Server Error')
				$this->err_msg=$msg;
			$this->errorhandler->setError($id,$msg);
			return FALSE;
		}
		else
		{
			$this->err_code=0;
			$this->err_msg='SUCCESS';
			return TRUE;
		}
	}
	
	/*返回错误码*/
	function get_err_code()
	{
	  return $this->err_code;
	}
	
	/*返回错误信息*/
	function get_err_msg()
	{
	  return $this->err_msg;
	}

}
/*End of file user.php*/

