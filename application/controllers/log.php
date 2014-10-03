<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*Log.php controller by StevenY.*/
class Log extends CI_Controller {
	
	var $a=array();
	
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login'))
			$this->uid = $this->session->userdata('uid');// 已经成功登陆
		else
			redirect('account');
		$this->load->model('devlog');
	}
	
	public function index()
	{
		if (is_int($this->input->get('page')))
			$off=($this->input->get('page')-1)*10;
		else
			$off=0;
		$a['ulist']=$this->user->get_user_list(0,10,0,0);
		$a['list']=$this->devlog->get_list(10,$off);
		if ($a['list'] === FALSE)
			redirect('home');
		else
			$this->load->view('log/index',$a);
	}

	public function add_log()
	{
		$this->load->library('form_validation');
		$rule=array(
				array(
					'field'   => 'title', 
					'label'   => 'Title', 
					'rules'   => 'trim|required'
				),
				array(
					'field'   => 'content', 
					'label'   => 'Content', 
					'rules'   => 'required'
				),  
			);

		$this->form_validation->set_rules($rule);

		if ($this->form_validation->run() === FALSE)
			redirect('log');
		else
		{
			if ($this->devlog->add_log($this->input->post('title'),$this->input->post('content')))
				$a['err']=0;
			else
				echo $this->devlog->get_err_msg();
			redirect('log');
		}
	}
	
	public function add_comment()
	{
		$this->load->library('form_validation');
		$rule=array(
				array(
					'field'   => 'lid', 
					'label'   => 'Log id', 
					'rules'   => 'trim|required|is_numeric'
				),
				array(
					'field'   => 'content', 
					'label'   => 'Content', 
					'rules'   => 'trim|required|max_length[256]'
				),  
			);

		$this->form_validation->set_rules($rule);

		if ($this->form_validation->run() === FALSE)
			redirect('log');
		else
		{
			if ($this->devlog->add_comment($this->input->post('lid'),$this->input->post('content')))
				$a['err']=0;
			else
				$a['err']=$this->devlog->get_err_msg();
			redirect('log');
		}
	}
	function json(){
		$a['ulist']=$this->user->get_user_list(0,10,0,0);
		$this->load->library('json');
		echo $this->json->encode($a['ulist']);
	}
}
