<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* notice.php by StevenY*/
class Notice extends SAE_Controller {
	
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
			if (is_numeric($mid))//get particular notice from a group
			{
				if ($this->input->get('off'))
					$off=$this->input->get('off');
				else
					$off=0;
					
				if ($this->input->get('lim'))
					$lim=$this->input->get('lim');
				else
					$lim=1;
				
				$a['message']=$this->mes->get_msg($mid,TRUE,"*",$lim,$off,'id desc');
				//$a['query']=$this->db->last_query();
				$this->push(NULL,$a);
			}
			else
			{
			 	show_404();
			}
		}
		else//get list
		{
			$this->load->model('group');
			$a['mysub']=$this->group->get_sub();
			$this->db->where('group.auth',$this->uid);
			$a['mygroup']=$this->group->get_sub();
			
			$a['notice']=array();
			foreach ($a['mysub'] as $item)
			{
				$a['notice'][$item['gid']]=$this->mes->get_msg($item['gid'],TRUE,"*",1,0,'id desc');
			}
			
			$this->push('notice/index',$a);
		}
	}
	
	public function delete($mid = NULL)
	{
		if (isset($mid))
		{
			if ($this->input->get('id'))
			{
				if ($this->mes->delmsg($this->input->get('id'),TRUE))
					$a['status']=TRUE;
				else
					$a['error']=$this->errorhandler->popupErrorMessage();
				$this->push(NULL,$a);
			}
			elseif ($this->input->get('list'))
			{
				trim($this->input->get('list'),',');
				$temp=explode(',',$this->input->get('list'));
				$a['error']=array();
				$a['status']=TRUE;
				foreach ($temp as $item)
				{
					if (is_numeric($item))
					{
						if (! $this->mes->delmsg($item,TRUE))
						{
							$a['error']='ID='.$item.':'.$this->errorhandler->popupErrorMessage();
							$a['status']=FALSE;
							break;
						}
					}
					else
					{
						$a['error']='Invalid Input!';
						$a['status']=FALSE;
						break;
					}
				}
				$this->push(NULL,$a);
			}
			else
				show_404();
		}
		else
			show_404();
	}
	
	public function create($mid = NULL)
	{
		if (isset($mid))
		{
			if (is_numeric($mid))//send particular notice
			{
				$this->load->library('form_validation');
				$rules=array(
							array(
								'field'   => 'text', 
								'label'   => 'text', 
								'rules'   => 'trim|required|xss_clean|htmlspecialchars'
								  ),
							array(
								'field'   => 'title', 
								'label'   => 'title', 
								'rules'   => 'trim|required|max_length[68]'
								  )
							);
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run()===FALSE)
				{
					$a['error']=validation_errors();
					$this->push(NULL,$a);
				}
				else
				{
					$id=$mid;
					$title=$this->input->post('title');
					$text=$this->input->post('text');
					$a['status']=$this->mes->sendto($id,$title,$text,TRUE);
					if ($a['status'])
					{
						$a['error']=TRUE;
						$this->push(NULL,$a);
					}
					else
					{
						$a['error']=$this->mes->get_err_msg();
						$this->pusth(NULL,$a);
					}
				}
			}
			else
				show_404();
		}
		else
		{
			show_404();
		}
	}
	
	public function edit($mid = NULL)
	{
		if (is_numeric($mid))
		{
			$this->load->library('form_validation');
			$rules=array(
						array(
							'field'   => 'id', 
							'label'   => 'notice id', 
							'rules'   => 'trim|required|is_numeric'
							  ),
						array(
							'field'   => 'text', 
							'label'   => 'text', 
							'rules'   => 'trim|xss_clean|htmlspecialchars'
							  ),
						array(
							'field'   => 'title', 
							'label'   => 'title', 
							'rules'   => 'trim|max_length[68]'
							  )
						);
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run()===FALSE)
			{
				$a['error']=validation_errors();
			}
			else
			{
				$id=$this->input->post('id');
				if ($this->input->post('text'))
					$text=$this->input->post('text');
				else
					$text=NULL;
				if ($this->input->post('title'))
					$title=$this->input->post('title');
				else
					$title=NULL;
				if ($this->mes->edit($id,$title,$text))
					$a['status']=TRUE;
				else
					$a['error']=$this->errorhandler->popupErrorMessage();
				$this->push(NULL,$a);
			}
		}
	}
}