<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Group.php by StevenY. */
class Group extends CI_Model{

	var $err_code = 0;
	var $err_msg  = '';
	var $uid      = FALSE;
	var $kv       = FALSE;
	
    function __construct()
    {
		parent::__construct();
		$this->uid= $this->user->id;
    }

	/**
	 * Create group
	 *
	 * @param string $gname is the name of the group
	 * @return FALSE: not success
	 		   course_id success
	 */
	public function create($gname)
	{
		if (is_string($gname))
		{
			$this->db->trans_begin();
			
			//create the group
			$data1=array(
						'auth'=>$this->uid,
						'name'=>$gname
						);
			$this->db->insert('group',$data1);
			$gid=$this->db->insert_id();
			
			//add the group owner to subjection
			$data2=array(
						'uid'=>$this->uid,
						'gid'=>$gid
						);
			$this->db->insert('user_sub',$data2);
			
			//trans submit
			if ($this->db->trans_status() === FALSE)
			{
    			$this->db->trans_rollback();
				return $this->err(103,'Databsae error');
			}
			else
			{
    			$this->db->trans_commit();
				$this->err(0,0);
				return $gid;
			}
		}
		else
			return $this->err(102,'Wrong INput');
	}
		 	 		
	/**
	 * Delete the group
	 * @param int $gid group id
	 */
	function delete($gid)
	{
		//only group_owner can delete a group
		if (($this->db->get_where('group',array('auth'=>$this->uid,'id'=>$gid))->num_rows()!=1) && ($this->uid != 0))
			return $this->err(403,'No permission');
		
		$this->db->trans_begin();
		$members= $this->db->get_where('user_sub',array('gid'=>$gid))->result_array();
		foreach ($members as $item)
			$this->kick($item['uid'],$gid,TRUE);

		$this->db->delete('auth',array('gid'=>$gid));
		$this->db->delete('group',array('id'=>$gid));
			
		if ($this->db->trans_status() === FALSE)//@bug....what if I really failed....
		{
			$this->db->trans_rollback();
			return $this->err(203,"Database error");
		}
		else
		{
			$this->db->trans_commit();
			return $this->err(0,0);
		}
	}
	
	/**
 	 * Add user to the group(accept)
	 * @param int $uid is the user id
	 * @param int $gid is the group id
	 * @return boolean whether success
	 */
	function add($uid,$gid)
	{
		//check whether it is in waitlist
		if ($this->db->get_where('waitlist_group',array('uid'=>$uid,'gid'=>$gid))->num_rows()==1)
			$this->db->delete('waitlist_group',array('uid'=>$uid,'gid'=>$gid));//@improve
			
		if ($this->db->get_where('user_sub',array('uid'=>$uid,'gid'=>$gid))->num_rows==1)
			return $this->err(0,0);
		elseif ($this->db->insert('user_sub',array('uid'=>$uid,'gid'=>$gid)))//insert success, now update the permission
		{
			//syn the permission
			$this->load->library('permission');
			if ($this->permission->syn($uid))
				return $this->err(0,0);
			else
			{
				log_message('error','Database error in user system');
				return FALSE;
			}
		}
		else
			return $this->err(303,'Database error');
	}
	
	/**
 	 * Delete user from the group
	 * @param int $uid is the user id
	 * @param int $gid is the group id
	 * @return boolean whether success
	 */
	function kick($uid,$gid, $chekced = FALSE)//@todo
	{
		if (! $chekced)
		{
			//check permission(only the group owner can do it!)
			if ($this->db->get_where('group',array('id'=>$gid,'auth'=>$this->uid))->num_rows() == 0)
				if ($this->uid!=0)
					return $this->err(403,'No permission');
			
			if ($this->db->get_where('user_sub',array('uid'=>$uid,'gid'=>$gid))->num_rows == 0)
				return $this->err(0,0);
			elseif ($uid == end($this->db->get_where('group',array('id'=>$gid))->result())->auth)//不能删除改组所有者
				return $this->err(404,'No permission');
		}
		
		//delete database record
		$this->db->delete('user_sub',array('uid'=>$uid,'gid'=>$gid));
		
		//delete permission
		$this->load->library('permission');
		if ($this->permission->syn($uid))
			return $this->err(0,0);
		else
		{
			log_message('error','Something Wrong in the syn!');
			return FALSE;
		}
	}
		
	/*Apply functions*/
	/**
 	 * Apply to enter certain group
	 * @param int $gid is the group id
	 * @return boolean whether success
	 */
	function apply($gid,$description)
	{
		if ($this->db->get_where('user_sub',array('uid'=>$this->uid,'gid'=>$gid))->num_rows()==1)
		{
			if ($this->db->get_where('waitlist_group',array('uid'=>$this->uid,'gid'=>$gid))->num_rows()==1)
				$this->db->delete('waitlist_group',array('uid'=>$this->uid,'gid'=>$gid));
			return $this->err(0,0);
		}
		else
		{
			if ($this->db->get_where('waitlist_group',array('uid'=>$this->uid,'gid'=>$gid))->num_rows()==1)
				return $this->err(0,0);
			elseif ($this->db->insert('waitlist_group',
									  array('uid'=>$this->uid,
											'gid'=>$gid,
											'description'=>$description)))
				return $this->err(0,0);
			else
				return $this->err(601,'Database error');
		}
	}
	
	/**
 	 * Get invitation information from the waitlist
	 * @param string $op option:'group' or 'user'
	 * @param int $id if 'group' then group id if 'user' then user id
	 * @return boolean whether success
	 */
	function get_invitation($op,$id)
	{
		switch($op)
		{
			case 'group':
				return $this->db->get_where('waitlist_group',array('gid'=>$id))->result_array();
			break;
			case 'user':
				return $this->db->get_where('waitlist_group',array('uid'=>$id))->result_array();
			break;
			default:
				return $this->err(701,'Wrong InpuT');
			break;
		}
	}
	
	/**
	 * Get members from a group
	 * 
	 * @param integer $gid group id
	 * @return 
	 */
	function get_member($gid)
	{
		//check permission
		if ($this->db->get_where('group',array('auth'=>$this->uid,'id'=>$gid))->num_rows()!=1)
			return $this->err(801,'No permission');
		
		$return['sub']=$this->db->get_where('user_sub',array('gid'=>$gid))->result_array();
		$return['waitlist']=$this->db->get_where('waitlist_group',array('gid'=>$gid))->result_array();
		return $return;	
	}
	
	function is_owner($gid)
	{
		if ($this->db->get_where('group',array('auth'=>$this->uid,'id'=>$gid))->num_rows()==1)
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Get subjection groups(Group id)
	 *
	 * @param int $uid current user id
	 * @return FALSE no full access
	 		   array(array('gid'=>gid,'name'=>group_name,'auth'=>author_id))
	 */
	function get_sub()
	{
		//get user subjection(group id)
		$this->db->select('user_sub.gid, group.name, group.auth');
		$this->db->join('group','group.id=user_sub.gid');
		$gid=$this->db->get_where('user_sub',array('uid'=>$this->uid))->result_array();
		return $gid;
	}
	
	/**
	 * Error functions
	 * @access private, only use within this class
	 * @param int $code is the error code
	 * @param str $msg is the error message
	 * @return bol whether the function success
	 */
	private function err($code,$msg = NULL)
	{
		if ($code!=0)
		{
			$this->err_code=$code;
			$this->err_msg= $msg;
			$this->errorhandler->setError($code,$msg);
			return FALSE;
		}
		else
		{
			//if $code=0, it's the success case
			$this->err_code=0;
			$this->err_msg='SUCCESS';
			return TRUE;
		}
	}
	
	//return error code
	function get_err_code()
	{
		return $this->err_code;
	}
	
	//return error message
	function get_err_msg()
	{
		return $this->err_msg;
	}
}
/*End of file group.php*/