<?php
class ulibrary extends SAE_Controller {

	var $uid;

	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		if (time()-mktime(0,0,0,4,9,2014) < 0){
			$this->load->view('ulibrary/countdown');
		}else{
			$this->load->library('user_agent');
			if ($this->agent->is_mobile()){
				$this->load->view('ulibrary/ulregister_mobile');
			}else{
				$this->load->view('ulibrary/ulregister');
			}
		}
	}
	
	public function test()
	{
		if ($this->input->get('secret') == 'viewtest'){
			$this->load->view($this->input->get('view'));
		}else{
			$this->load->view('ulibrary/ulverified');
		}
	}

	public function signup()
	{
		//创建两个变量
		$leader  = array();
		$members = array();
		
		//先吧leader变量取到
		if ($this->input->post('sid') && $this->input->post('ulemail')){
			$leader['sid']=$this->input->post('sid');
			$leader['email']=$this->input->post('ulemail');
		}
		else{//如果没有收到的话爆错
			$a['error']='In order to sign up, some one has to be the leader.';
			$this->load->view('ulibrary/ulregister',$a);
			return;
		}
		
		//整理变量
		$num = 1;
		
		//set the rules for the leader fields
		$rules= array(
				array('field'   => 'sid', 
                      'label'   => "Leader's Student ID", 
                      'rules'   => 'required'
				),
				array('field'   => 'ulemail', 
                      'label'   => "Leader's Contact Email", 
                      'rules'   => 'required|valid_email|is_unique[ulib_compet.email]'//@todo 2014-1-14
				)
		);
		
        foreach(array('1','2','3','4') as $item)
		{
			if ($this->input->post('sid'.$item))//因为未必每一个member都有email
			{
				$temp=array('email' => $this->input->post('ulemail'.$item),
							'sid'   => $this->input->post('sid'.$item)
							);
				array_push($members,$temp);
				
				//adjust the rules
				$temp_rule_sid = array(
					  'field'   => 'sid'.$item, 
                      'label'   => "Member{$item} Student ID", 
                      'rules'   => 'required'
				);
				$temp_rule_email = array(
					  'field'   => 'email'.$item, 
                      'label'   => "Member{$item} email", 
                      'rules'   => 'valid_email'
				);

				array_push($rules,$temp_rule_sid);
				array_push($rules,$temp_rule_email);
				
				//add number
				$num++;
			}
		}
		
		//echo var_dump($rules);
		
		//验证表单
		$this->load->library('form_validation');
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == FALSE)
		{
			$a['error'] =  validation_errors();
			$this->load->view('ulibrary/ulregister',$a);
			return;
		}
		else
		{
			//use model to register
			$this->load->model('ulib');
			$return = $this->ulib->compet_signin($leader,$members);
			if ($return === FALSE){
				//something wrong, check the error
				$err_code = $this->errorhandler->popupErrorCode();
				switch($err_code){
					case 101://@todo
					/*
						$a['error'] = $this->errorhandler->popupErrorMessage().';Please try again or contect developers:hficampus@sina.cn';
						$this->load->view('ulibrary/ulregister',$a);
					break;*/
					case 103://@todo
						$a['error'] = $this->errorhandler->popupErrorMessage().'Please contact developers:hficampus@sina.cn!';
						$this->load->view('ulibrary/ulregister',$a);
						return;
					break;
					case 102://@todo
						$a['error'] = $this->errorhandler->popupErrorMessage().'Please contact developers:hficampus@sina.cn!';
						$this->load->view('ulibrary/ulveried',$a);
						return;
					break;
					case 104:
					/*
						$a['error'] = 'Some unknown errors are encountered. Please contact developers:hficampus@sina.cn!';
						$this->load->view('ulibrary/ulregister',$a);
					break;*/
					default:
						$a['error'] = 'Some unknown errors are encountered. Please contact developers through:hficampus@sina.cn!';
						$this->load->view('ulibrary/ulregister',$a);
						return;
					break;
				}
			}else{
				//成功了！发送邮件吧~
				$members_included = $members;
				array_push($members_included,$leader);
				$output = TRUE;
				
				//this is the email content
				$content = '';
				
				//发邮件给每一个参赛者
				$this->load->library('email');
				$title = 'U Library Design Competition Signin Confirmation';
				
				foreach ($members_included as $i => $item){
					$info = $this->ulib->sidsearch($item['sid']);
					
					if ($info['code']==0){
						//prepare for the email sent to ULibrary
						$members_included[$i]['info']=$info;
						if (isset($item['email']) && ($this->email->valid_email($item['email']))){
							//email address is validated, so construct the email
							$content = 'Dear '.ucfirst(strtolower($info['en'])).'</br>You have been successfully join the Library Design Competition! Please register an account in the following link using your HFI Student ID ('.$item['sid'].') to check the competition status:'.site_url('register').'</br>We will keep in touch with you through this email shortly and send you further materials about the library and the competition.';
							
							$send = $this->email->quick_send($item['email'],$title,$content,'hficampus@sina.cn','hficampus');
							if (! $send){
								$this->errorhandler->setError(1001,$this->email->print_debugger());
								$output = FALSE;
							}
						
						}else{
							$this->errorhandler->setError($info['code'],$info['msg']);
							$output = FALSE;
						}
					}else{
						$members_included[$i]['info']= array(
							'en'    => "Can't locate that person",
							'fn'    => "Can't locate that person",
							'ln'    => "Can't locate that person",
							'msg'   => "Can't locate that person",
							'code'  => 0,
							'regi'  => FALSE,
							'class' => "Can't locate that person"
						);
					}
				}
				
				//send an email to ulibrary
				$email = 'hfi_books@126.com';
				$title = 'SignIn Information. Group:'.$return;
				$content = 'Following students sign up as a group to join the competition:</br>';
				
				foreach ($members_included as $item){
					$name = strtoupper($item['info']['ln']).','.strtoupper($item['info']['fn']).'('.ucfirst(strtolower($item['info']['en'])).')';
					if (isset($item['leader']) && ($item['leader']===TRUE)){
						$content=$content.'Leader:'.$name.'   email:'.$item['email'].'    class:'.$item['info']['class'].'</br>';
					}
					else{
						$content=$content.$name.'   email:'.$item['email'].'    class:'.$item['info']['class'].'</br>';
					}
				}
				
				$send = $this->email->quick_send($email,$title,$content,'hficampus@sina.cn','hficampus');
				if (! $send){
					$this->errorhandler->setError(1001,$this->email->print_debugger());
					$output = FALSE;
				}
				
				if ($output===TRUE){
					$this->push('ulibrary/ulverified');
					return;
				}else{
					$a['error'] = "Some emails haven't been sent properly, we will fix this later! Thank you for supporting U Library!";
					$this->push('ulibrary/ulverified',$a);
					return;
				}
			}
		}
	}
	
	public function welcome()
	{
		//check login
		if ($this->session->userdata('login')===TRUE){
			$this->uid = $this->session->userdata('uid');// 已经成功登陆
		}else {// not logged in
			redirect(site_url('account'));//回到登陆页面
		}
		
		$ulib = $this->user->get_ap($this->uid,'ulib');
		if (isset($ulib['comp_id']) && is_numeric($ulib['comp_id'])){
			//get competition information
			$this->load->model('ulib');
			$compet_info = $this->ulib->get_compet_info($ulib['comp_id']);
			
			//deal with the competition information
			if ($compet_info['members']!='N/A'){
				$compet_info['teammates'] = explode($compet_info['members'],',');
			}else{
				$compet_info['teammates'] = $compet_info['members'];
			}
			
			$compet_info['appendix'] = unserialize($compet_info['appendix']);
			$compet_info['members'] = $compet_info['appendix']['members'];

			foreach ($compet_info['members'] as $i=>$item){
				$compet_info['members'][$i]['info'] = $this->ulib->sidsearch($item['sid']);
			}
			
			$a['compet_info']=$compet_info;
			$this->load->view('ulibrary/ulwelcome',$a);
			
		}else{
			throw new MY_Exception('You are not found as a participant. Please contact developer:hficampus@sina.cn',403,'home/main',10);
		}
	}
	
	/**
	 * api
	 */
	public function api($op){
		switch($op){
			/*
			 * url: /ulibrary/api/sidcheck
			 * input name=sid  student id user have typed	
			 */
			case 'sidcheck':
				if ($this->input->get('sid')){
					$sid = $this->input->get('sid');
					
					$this->load->model('ulib');
					$output = $this->ulib->sidsearch($sid);
					
					$this->push(NULL,$output);
				}
				else{
					$this->push(NULL,array('msg'=>'Nothing Received','code'=>404));
				}
			break;
			default:
				show_404();
			break;
		}
	}
	
	
	function send_email($email,$content,$config = FALSE)
	{
		//load files
		$this->load->library('email');
		
		//check input
		if (! $this->email->valid_email($email)) return $this->err(1501,'Wrong Input');
		
		//initialize the email library
		if (! is_array($config)) 
		{
/*
			$mail = new SaeMail();
			$email->setOpt( array('from'=>'hficampus@sina.cn',
										'to'  =>$email,
										'subject' =>'Vertify Email of your Hficampus account',
										'content' =>$content,
										'smtp_username' => 'hficampus@sina.cn',
										'smtp_password' =>'hficampus',
										'content_type'=>'HTML'));
			$send = $email->send();
	
*/							
			$send = $this->email->quick_send($email, 
											 'Vertify Email of your signing up for ulibrary design competition',
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
			$this->email->subject('Vertify Email of your signing up for ulibrary design competition');
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
}