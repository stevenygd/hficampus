<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*Course_setting Controller by StevenY.*/
class Course_setting extends SAE_Controller {
	
	var $uid;
	
	function __construct()
	{
		parent::__construct();
		$this->uid = $this->user->id;
		$this->load->model('aca');
	}
	
	function get($mid = NULL, $nid =NULL, $attach =NULL)
	{
		if (! isset($mid))
			show_404();
		
		if (isset($nid))
		{
			//temporary
			show_404();
			switch($attach)
			{
				default:
				break;
			}
		}
		else
		{
			//check permission if it's the group owner
			$this->load->model('group');
			$gid=$this->aca->get_gid($mid);
			if ($this->group->is_owner($gid))
			{
				//prepare members infomation
				$a=$this->group->get_member($gid);
				$a['cid']=$mid;
				$a['gid']=$gid;
				$uids=array();
				foreach($a['waitlist'] as $i=>$item)
				{
					array_push($uids,$item['uid']);
				}
				foreach ($a['sub'] as $i=>$item)
				{
					array_push($uids,$item['uid']);
				}
				$a['user_info']=$this->user->get_user_list(array('uid'=>$uids),20,0,0);
				$a['uids']=$uids;
				$a['uid']=$this->uid;
				$a['course_info']=$this->aca->get_course_list(array('aca.id'=>$mid),0,1,0);
								
				$this->push('course/course_setting',$a);
			}
			else
				redirect('course/'.$mid);
		}
	}
	
	function delete($mid = NULL, $nid =NULL, $attach =NULL)
	{
		show_404();
	}
	
	function create($mid = NULL, $nid =NULL, $attach =NULL)
	{
		show_404();
	}
	function edit($mid = NULL, $nid =NULL, $attach =NULL)
	{
		show_404();
	}
	
}
