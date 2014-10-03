<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*Calender_event Controller by StevenY.*/
class Calendar_event extends SAE_Controller {

	var $uid;
	
	function __construct()
	{
		parent::__construct();
		$this->uid = $this->user->id;
		$this->load->model('cal');
	}
	
	function get($mid = NULL, $nid = NULL , $attach = NULL)
	{
         if(isset($mid))
		 {
            if(!is_numeric($mid)) 
                throw new MY_Exception("Wrong Group Id");
			
			if (isset($nid))//get specific event
			{
				$a['event']=$this->cal->get_event($nid);
				$this->load->library('calendar');
				$this->push('calendar/event',$a);
			}
			else//get list
			{
				if ($this->input->get('start') && $this->input->get('end'))
				{
					$start=$this->input->get('start');
					$end=$this->input->get('end');
				}
				else
				{
					$start=mktime(0,0,0,date("m"),1,date("Y"));
					$end=mktime(0,0,0,date("m")+1,0,date("Y"));
				}
				
				if ($mid == 0)//get all events
					$a=$this->cal->get_events($start,$end);	
				else
					$a=$this->cal->get_events($start,$end,$mid);
				$this->load->library('calendar');
				$this->push('calendar/index.php',$a);
			}
		 }
		 else
			 throw new MY_Exception('Group is not specific');
	}
	
	function delete($mid = NULL, $nid = NULL , $attach = NULL)
	{
         if($mid) 
		 {
            if(!is_numeric($mid)) 
                throw new MY_Exception("Wrong Group Id");
			if ($this->input->get('id'))
			{
				if ($this->cal->delete($this->input->get('id')))
				{
					echo 0;
				}
				else
					echo 'Something Wrong';
			}
			else
				echo "Nothing recieve";
		 }
		 else
			 throw new MY_Exception('Group is not specific');
	}
	
	function create($mid = NULL, $nid = NULL , $attach = NULL)
	{
		if(isset($mid))
		{
			if(!is_numeric($mid)) 
				throw new MY_Exception("Wrong Group Id");
			
			$this->load->library('form_validation');
			$rules=array(
					array(
						'field'=>'name',
						'label'=>'event name',
						'rules'=>'required'
					),
					array(
						'field'=>'description',
						'label'=>'description',
						'rules'=>'required'
					),
					array(
						'field'=>'start',
						'label'=>'start time',
						'rules'=>'required'
					),
					array(
						'field'=>'end',
						'label'=>'end time',
						'rules'=>'required'
					),
					array(
						'field'=>'type',
						'label'=>'event type',
						'rules'=>'required'
					)
			);
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run()===FALSE)
				echo validation_errors();
			else
			{
				$name=$this->input->post('name');
				$description=$this->input->post('description');
				$start=strtotime($this->input->post('start'));
				$end=strtotime($this->input->post('end'));
				$type=$this->input->post('type');
				if ($this->cal->create($name,$description,$mid,$start,$end,$type))
				{
					$this->push('calendar/'.$mid,0,TRUE);
				}
				else
					echo 'Somthing Wrong';
			}
		}
		else
			throw new MY_Exception('Group is not specific');
	}
	
	function edit($mid = NULL, $nid = NULL , $attach = NULL)
	{
         if($mid) 
		 {
            if(!is_numeric($mid)) 
                throw new MY_Exception("Wrong Group Id");
			switch($attach)
			{
				case 'addint':
					$this->load->library('form_validation');
					$rules=array(
								array(
									  'field'=>'id',
									  'label'=>'event name',
									  'rules'=>'required'
									  ),
								array(
									  'field'=>'start',
									  'label'=>'start time',
									  'rules'=>'required'
									  ),
								array(
									  'field'=>'end',
									  'label'=>'end time',
									  'rules'=>'required'
									  )
								);
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run()===FALSE)
							echo validation_errors();
						else
						{
							$id=$this->input->post('id');
							$start=strtotime($this->input->post('start'));
							$end=strtotime($this->input->post('end'));
							if ($this->cal->addinterval($id,$start,$end))
							{
								echo 0;
							}
							else
							{
								echo 'Somthing wrong';
							}
						}
				break;
				case 'delint':
					$this->load->library('form_validation');
					$rules=array(
								array(
									  'field'=>'id',
									  'label'=>'event name',
									  'rules'=>'required'
									  ),
								array(
									  'field'=>'start',
									  'label'=>'start time',
									  'rules'=>'required'
									  ),
								array(
									  'field'=>'end',
									  'label'=>'end time',
									  'rules'=>'required'
									  )
								);
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run()===FALSE)
							echo validation_errors();
						else
						{
							$id=$this->input->post('id');
							$start=strtotime($this->input->post('start'));
							$end=strtotime($this->input->post('end'));
							if ($this->cal->deleteinterval($id,$start,$end))
							{
								echo 0;
							}
							else
							{
								echo 'Somthing wrong';
							}
						}
				break;
				default:
				break;
			}
		 }
		 else
			 throw new MY_Exception('Group is not specific');
	}
}