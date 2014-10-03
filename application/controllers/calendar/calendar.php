<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*Calender Controller by StevenY.*/
class Calendar extends SAE_Controller {

	var $uid;
	
	function __construct()
	{
		parent::__construct();
		$this->uid = $this->user->id;
		$this->load->model('cal');
	}
	
	function get($mid = NULL)
	{
		if (isset($mid))
		{
			if (! is_numeric($mid))
				show_404();

			$a['gid']=$mid;
			$a['gname']=$this->db->get_where('group',array('id'=>$mid))->row()->name;
			$this->load->library('calendar');
			$this->push('calendar/index',$a);
		}
		else
		{
			$this->load->model('group');
			$a['gids']=$this->group->get_sub();
			$a['uid']=$this->uid;
			$a['gid']=0;
			$a['own']=$this->db->get_where('group',array('auth'=>$this->uid))->result_array();
			$this->load->library('calendar');
			$this->push('calendar/index',$a);
		}
	}
	
	function delete($mid = NULL)//not yet available
	{
		show_404();
	}
	
	function create($mid = NULL)
	{
		if (isset($mid))
		{
			if (! is_numeric($mid))
				throw new MY_Exception('The group is not specified!');
			$a['gid']=$mid;
			$this->push('calendar/event_handler',$a);
		}
		else
			show_404();
	}
	
	function edit($mid = NULL)//not yet available
	{
		show_404();
	}
		
}
