<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*Course Controller by StevenY.*/
class Course extends SAE_Controller {
	
	var $uid;
	
	function __construct()
	{
		parent::__construct();
		$this->uid = $this->user->id;
		$this->load->model('aca');
	}
	
	function get($mid = NULL)
	{

		if (isset($mid))//get particular course information
		{
			if (! is_numeric($mid)){
				show_404();
			}
			
			$a['info']=end($this->aca->get_course_list(array('aca.id'=>$mid),0,1,0));
			$a['info']['appendix']=unserialize($a['info']['appendix']);
			$a['cid']=$mid;
			$a['gid']=$this->aca->get_gid($mid);
			
			//@todo 
			$this->load->model('group');
			if ($this->group->is_owner($a['gid']))
				$a['role']='teacher';
			else
				$a['role']='everyone';
				
			$a['themes']=array(
							  'page'     => array('url'=>'course/'.$mid.'/page',
							  					  'data'=>$this->db->get_where('aca_page',array('cid'=>$mid),10,0)->result_array(),
												  'count'=>$this->db->get_where('aca_page',array('cid'=>$mid))->num_rows()
							  				),
							  'question' => array('url'=>'course/'.$mid.'/question',
							  					  'data'=>$this->db->get_where('aca_question',array('cid'=>$mid),10,0)->result_array(),
												  'count'=>$this->db->get_where('aca_question',array('cid'=>$mid))->num_rows()
							  				),
							  'netdisk'  => array('url'=>'netdisk/#course/'.$mid,
							  					  'data'=>FALSE
							  				),
							  'calender' => array('url'=>'calendar/'.$a['gid'],
							  					  'data'=>FALSE
							  				),
							  );
			foreach ($a['themes'] as $i=>$item)
			{
				$a['themes'][$i]['imgurl'] = $a['info']['appendix']['themes'][$i]['imgurl'];
				$a['themes'][$i]['intro']  = $a['info']['appendix']['themes'][$i]['intro'];
			}

			$this->push('course/course_main',$a);
		}
		else//defaut,get course list
		{

			foreach (array('type','subtype','teacher','year') as $item)
			{
				if ($this->input->get($item))
					$a[$item]=$this->input->get($item);
				else
					$a[$item]=0;
			}

			if ($this->input->get("fetch"))//choose course
			{
				$where=array();
				foreach (array('type'=>'type','subtype'=>'subtype','teacher'=>'auth','year'=>'year') as $i=>$item)
				{
					if ($a[$i]!=0)
						$where['aca.'.$item]=$a[$i];
				}
				if ($this->input->get('off'))
					$off=$this->input->get('off');
				else
					$off=0;
				$a['course']=$this->aca->get_course_list($where,0,10,$off);
				$this->push('course/serch_course',$a);
			}
			else//default's default push index page
			{
				$a['subtypes']=$this->aca->get_subtypes();
				$a['types']=$this->aca->get_types();
				$a['teachers']=$this->aca->get_teachers();

				$this->load->model('group');
				$sub=$this->group->get_sub();
				
				$a['my_course']=array();
				$gids=array();
				$a['mycount']=0;
				foreach ($sub as $item)
				{
					array_push($gids,$item['gid']);
					try
					{
						$course_info=$this->aca->get_course_list(array('aca.gid'=>$item['gid']),0,1,0);
						array_push($a['my_course'],end($course_info));
						$a['mycount']=$a['mycount']+1;
					}
					catch(Exception $e)
					{
						array_push($a['my_course'],$e->getMessage());
					}
				}
				
				//see if it's an academic teacher
				if (in_array(2,$gids))
				{
					$a['role']='teacher';
					//@todo cache it?
				}
				else
				{
					$a['role']='everyone';
					//@todo cache it?
				}
				
				try{
					$course_list=$this->aca->get_course_list(0,0,100,0);
				}catch(Exception $e){
					$a['err']=$e->getMessage();
				}

				$a['course_list']=array();
				if (isset($course_list) && is_array($course_list))
				{
					foreach ($course_list as $item)
					{
						if (! in_array($item['gid'],$gids))
						{
							array_push($a['course_list'],$item);
						}
					}
				}
				
				if (isset($a['course_list']) && is_array($a['course_list']))
					$a['count']=count($a['course_list']);
				
				//get course invitation
				$a['uid']=$this->uid;
				$invi=$this->group->get_invitation('user',$this->uid);
				if (count($invi)>0)
					foreach ($invi as $item)
					{
						$a['invitation'][$item['gid']]=$item;
					}
				else
					$a['invitation']=array();
					
				$this->push('course/index',$a);
			}
		}
	}
	
	function delete($mid = NULL)
	{
		if (isset($mid))//delete course
		{
			if ($this->user->sec_chk())
			{
				//@todo do something about the permission
				$gid=$this->aca->get_gid($mid);
				if ($this->aca->delete($mid))
				{
					$this->load->model('group');
					$this->group->delete($gid);
					
					$this->load->model('netdisks');
					$currentPath='course';
					$objectName=$mid;
					$this->netdisks->delete($currentPath, $objectName);
					
					//delete indi permission
					$this->load->library('permission');
					$this->db->delete('permi_history',array('uid'=>$this->uid,'mtype'=>'course','mid'=>$mid,'ntype'=>'all','nid'=>0));
					if (! $this->permission->syn($this->uid))
						throw new MY_Exception('Permission Error5');


					redirect('course');
				}
				else
					echo $this->aca->get_err_msg();
			}
			else
			{
				$this->user->security_redir('course/'.$mid.'/delete');
			}
		}
		else//delete course list
		{
			//not yet available
			show_404();
		}
	}

	function create($mid = NULL)
	{
		if (isset($mid))//add something to the course
		{
			if (! is_numeric($mid))
			{
				show_404();
			}
			
			switch($this->input->post('execution'))
			{
				case 'invite'://add member to the class
					if ($this->input->post('uid'))
					{
						$gid=$this->aca->get_gid($mid);
						$this->load->model('group');
						if ($this->group->add($this->input->post('uid'),$gid))	
							$this->push('course/'.$mid.'/setting',0,TRUE);
						else
							echo $this->group->get_err_msg();
					}
					else
						echo 'Nothing Recieved';
				break;
				default://not yet available
					show_404();
				break;
			}
		}
		else//add course
		{
			$this->load->library('form_validation');
			$rule=array(
					array(
						'field'   => 'cname', 
						'label'   => 'Course Name', 
						'rules'   => 'trim|required'
						),
					array(
						'field'	  => 'type',
						'label'   => 'types',
						'rules'   => 'trim|required|is_numeric'
						),
					array(
						'field'	  => 'y',
						'label'   => 'Course Year',
						'rules'   => 'trim|required|is_numeric'
						),
					array(
						'field'	  => 'description',
						'label'   => 'Course Description',
						'rules'   => 'trim|required'//@todo  clean
						),
				);
			$this->form_validation->set_rules($rule);
			if ($this->form_validation->run() === FALSE)
				echo validation_errors();
			else
			{
				$cname=$this->input->post('cname');
				$y=$this->input->post('y');
				$type=$this->input->post('type');
				$description=$this->input->post('description');
				
				//create an academic group
				$this->load->model('group');
				$gname='Course:'.$cname;
				$gid=$this->group->create($gname);
				if ($gid===FALSE)
					throw new MY_Exception('Error Message:'.$this->group->get_err_msg(),$this->group->get_err_code(),'course',10);
					
				$cid = $this->aca->create($cname,$gid,$y,$type,$description);
				if ($cid === FALSE)
				{
					//clean the group
					$this->group->delete($gid);
					throw new MY_Exception('Error Message:'.$this->group->get_err_msg(),$this->group->get_err_code(),'course',10);
				}
				else
				{
					//update permission
					$this->load->library('permission');
					if (! $this->permission->addto_group($gid,'l--r-','course',$cid,'all',0))
						throw new MY_Exception('Permission Error1');
						
					if (! $this->permission->addto_group($gid,'lc-r-','course',$cid,'question',0))
						throw new MY_Exception('Permission Error2');
						
					if (! $this->permission->addto_group($gid,'l--r-','calendar',$gid,'all',0))
						throw new MY_Exception('Permission Error3');
						
					if (! $this->permission->addto_indi($this->uid,'lcurd','course',$cid,'all',0,'create course function'))
						throw new MY_Exception('Permission Error4');
						
					$this->load->model('netdisks');
					$currentPath = 'course';
					$objectName  = $cid;
					$this->netdisks->createDirectory($currentPath, $objectName);

					redirect('course/'.$cid);
				}
			}
		}
	}

	function edit($mid = NULL)
	{
		if (isset($mid))//change course info
		{
			if (! is_numeric($mid))
			{
				show_404();
			}
			switch($this->input->post('execution'))
			{
				case 'kick'://@todo//kick people out
					if ( $this->input->post('uid'))
					{
						$gid=$this->aca->get_gid($mid);
						$this->load->model('group');
						if ($this->group->kick($this->input->post('uid'),$gid))
							echo '0';
						else
							echo $this->group->get_err_msg();
					}
					else
						echo 'Nothing Recieved';
				break;
				case 'apply'://@todo? want to add a member to the class
					if ($this->input->post('gid'))
					{
						$this->load->model('group');
						if ($this->group->apply($this->input->post('gid'),$this->input->post('description')))	
							echo 0;
						else
							echo $this->group->get_err_msg();
					}
					else
						echo 'Nothing Recieved';
				break;
				default:
				break;
			}
		}
		else//change course list
		{
			
		}
	}
						
}