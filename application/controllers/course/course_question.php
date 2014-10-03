<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*Course_page Controller by StevenY.*/
class Course_question extends SAE_Controller {
	
	var $uid;
	
	function __construct()
	{
		parent::__construct();
		$this->uid = $this->user->id;
		$this->load->model('aca_question');
	}
	
	function get($mid = NULL, $nid =NULL, $attach = '')
	{
		if (! isset($mid))
			show_404();
		
		if (isset($nid))//get particular question
		{
			switch($attach)
			{
				case 'editor':
					if ($nid==0)
					{
						$a['info']=FALSE;
						$this->load->model('aca');
						$cinfo    = $this->aca->get_course_list(array('aca.id'=>$mid),0,1,0);
						$a['gid'] = $this->aca->get_gid($mid);
						$a['course_name'] = $cinfo[0]['name'];
					}
					else
					{
						$info=$this->aca_question->get_question($nid);
						$a['info'] = $info['question'];
						$a['gid']  = $info['question']['gid'];
						$a['course_name']=$info['question']['name'];
					}
					$a['cid']=$mid;
					$this->push('course/editquestion',$a);
				break;
				default://no attachment:just get particular question
					if (is_numeric($nid))
					{
						$info=$this->aca_question->get_question($nid);
						if ($info===FALSE)
							show_404();
						else
						{
							$a=$info;
							$a['cid']=$mid;
							$a['gid']=$info['question']['gid'];
							$a['qid']=$nid;
							$a['course_name']=$info['question']['name'];
							$this->push('course/question',$a);
							//$a['question'],$a['comment']
						}
					}
					else
						show_404();
				break;
			}
		}
		else//get question list
		{
			$a['questions']=$this->aca_question->get_question_list(array('cid'=>$mid),0,10,0);
			$a['gid']=$a['questions'][0]['gid'];
			$a['cid']=$mid;
			$a['course_name']=$a['questions'][0]['name'];
			$this->push('course/question_list',$a);
		}
	}
	
	function delete($mid = NULL, $nid =NULL, $attach =NULL)
	{
		if (! isset($mid))
			show_404();
		
		if (isset($nid))//delete particular question
		{
			switch($attach)
			{
				default:
					if ($this->aca_question->deleteByPk($nid) && $this->aca_question->del_subContent(array('qid'=>$nid)))
						$this->push('course/'.$mid.'/question/'.$nid,0,TRUE);
					else
						echo "somethign wrong!";
				break;
			}
		}
		else//delete question list
		{
		}
	}
	
	function create($mid = NULL, $nid =NULL, $attach =NULL)
	{
		if (! isset($mid))
			show_404();
		
		if (isset($nid))//add something to particular question
		{
			switch($attach)
			{
				case 'answer':
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
						if ($this->aca_question->comment($nid,$text))
							$this->push('course/'.$mid.'/question/'.$nid,0,TRUE);
						else
							echo $this->errorhandler->popupErrorMessage();
					}
				break;
				default:
				break;
			}
		}
		else//add question
		{
			$this->load->library('form_validation');
			$rule=array(
					array(
						'field'	  => 'title',
						'label'   => 'question title',
						'rules'   => 'trim|required'
						),
					array(
						'field'	  => 'text',
						'label'   => 'question',
						'rules'   => 'trim|required'
						)
				);
			$this->form_validation->set_rules($rule);
			if ($this->form_validation->run() === FALSE)
			{
				throw new MY_Exception(validation_errors(),100,'course/'.$mid.'/question/'.$nid,5);
			}
			else
			{
				$cid=$mid;
				$text=$this->input->post('text');
				$title=$this->input->post('title');
				$a=$this->aca_question->add_question($cid,$title,$text);
				if ($a !== FALSE)
				{
					//var_dump($this->aca_question->get_question($a));
					redirect('course/'.$mid.'/question/'.$a);
				}
				else
					echo $this->aca_question->get_err_msg();
			}
		}
	}
	
	function edit($mid = NULL, $nid =NULL, $attach =NULL)
	{
		if (! isset($mid))
			show_404();
		
		if (isset($nid))
		{
			switch($attach)
			{
				default:
					show_404('Not yet available!');
				break;
			}
		}
		else
		{
			show_404();
		}
	}
	
}