<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*Course_page Controller by StevenY.*/
class Course_page extends SAE_Controller {
	
	var $uid;
	
	function __construct()
	{
		parent::__construct();
		$this->uid = $this->user->id;
		$this->load->model('aca_page');
	}
	
	function get($mid = NULL, $nid =NULL, $attach = '')
	{
		//@todo
		if (! isset($mid))
			show_404();
		if (isset($nid))//open particular page
		{
			switch($attach)
			{
				case 'editor':
					if ($nid==0)
						$a['info']=FALSE;
					else
					{
						$info = $this->aca_page->read($nid);
						$a['info']= $info['page'];
						$a['gid']=$info['page']['gid'];
						$a['course_name']=$info['page']['name'];
					}
					$a['cid']=$mid;
					//$this->push('course/editpage',$a);
					//test
					$this->push(NULL,$a);
				break;
				default:
					if (is_numeric($nid))
					{
						$info = $this->aca_page->read($nid);
						if ($info===FALSE)
							show_404();
						else
						{
							$a=$info;
							$a['cid']=$mid;
							$a['pid']=$nid;
							$a['gid']=$info['page']['gid'];
							$a['course_name']=$info['page']['name'];
							//test
							$this->push(NULL,$a);
							//$this->push('course/page',$a);
							//$a['page'],$a['comment']
						}
					}
					else
						show_404($nid);
				break;
			}
		}
		else//page list
		{
			$lim=10;
			if ($this->input->get('lim'))
				$lim=$this->input->get('lim');
				
			$off=0;
			if ($this->input->get('off'))
				$off=($this->input->get('off'))*10;
				
			$a['page_list']=$this->aca_page->get_list(array('cid'=>$mid),0,$lim,$off);
			$a['cid']=$mid;
			$a['gid']=$a['page_list'][0]['gid'];
			$a['course_name']=$a['page_list'][0]['name'];
			$this->push('course/page_list',$a);
		}
	}
	
	function delete($mid = NULL, $nid =NULL, $attach =NULL)
	{
		//@todo
		if (! isset($mid))
			show_404();
		
		if (isset($nid))//delete particular page
		{
			switch($attach)
			{
				case 'comment':
					if ($this->aca_page->delete_comment($this->input->get('commentid')))
						$this->push('course/'.$mid.'/page/'.$nid,0,TRUE);
					else
						echo $this->errorHandler->popupErrorMessage();
				break;
				default:
					if ($this->aca_page->delete($nid))
						$this->push('course/'.$mid.'/page',0,TRUE);
					else
						echo $this->aca->get_err_msg();
				break;
			}
		}
		else//delete page_list
		{
		}
	}
	
	function create($mid = NULL, $nid =NULL, $attach =NULL)
	{
		//@todo
		if (! isset($mid))
			show_404();
		
		if (isset($nid))//add things to particular page
		{
			switch($attach)
			{
				case 'comment'://add comment to particular page
					$this->load->library('form_validation');
					$rule=array(
							array(
								'field'	  => 'text',
								'label'   => 'page contant',
								'rules'   => 'trim|required'
								)
						);
					$this->form_validation->set_rules($rule);
					if ($this->form_validation->run() === FALSE)
					{
						echo validation_errors();
					}
					else
					{
						$text=$this->input->post('text');
						if ($this->aca_page->comment($nid,$text))
							$this->push('course/'.$mid.'/page/'.$nid,0,TRUE);
						else
							echo $this->aca_page->get_err_msg();
					}
				break;
				default:
				break;
			}
		}
		else//add page
		{
			$this->load->library('form_validation');
			$rule=array(
					array(
						'field'	  => 'title',
						'label'   => 'types',
						'rules'   => 'trim|required'
						),
					array(
						'field'	  => 'text',
						'label'   => 'page contant',
						'rules'   => 'trim|required'
						),
					array(
						'field'	  => 'not',
						'label'   => 'notice',
						'rules'   => 'trim|required|is_numeric'
						),
					array(
						'field'	  => 'email',
						'label'   => 'email',
						'rules'   => 'trim|required|is_numeric'
						),
				);
			$this->form_validation->set_rules($rule);
			if ($this->form_validation->run() === FALSE)
			{
				echo validation_errors();
			}
			else
			{
				$cid=$mid;
				$title=$this->input->post('title');
				$text=$this->input->post('text');
				$not=$this->input->post('not');
				$email=$this->input->post('email');
				
				$a=$this->aca_page->create($cid,$title,$text,$not,$email);
				if ($a !== FALSE)
					redirect('course/'.$mid.'/page/'.$a);
				else
					echo $this->errorhandler->popupErrorMessage();
			}
		}
	}
	
	function edit($mid = NULL, $nid =NULL, $attach =NULL)
	{
		//@todo
		if (! isset($mid))
			show_404();
		
		if (isset($nid))//edit particular page
		{
			switch($attach)
			{
				default://edit particular page
					if ($this->input->post('title'))
						$title=$this->input->post('title');
					else
						$title=0;
					if ($this->input->post('text'))
						$text=$this->input->post('text');
					else
						$text=0;
					if ($this->aca_page->update($nid,$title,$text))
						redirect('course/'.$mid.'/page/'.$nid);
					else
						echo $this->errorhandler->popupErrorMessage();
				break;
			}
		}
		else//edit particular page list
		{
		}
	}	
}