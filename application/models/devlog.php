<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*Developer log model by StevenY.*/
class Devlog extends CI_Model {
	
	var $err_code=0;
	var $err_msg='SUCCESS';
	var $uid;
	
	/*******************************
	* Construct function
	*******************************/
	function __construct()
	{
		parent::__construct();
		$CI=get_instance();
		if ($this->session->userdata('login'))
		{
			// 已经成功登陆
			$this->uid = $this->session->userdata('uid');
			if ($this->db->get_where('user_sub',array('uid'=>$this->uid,'gid'=>1))->num_rows()!=1)
			{
				$this->uid=FALSE;
				return FALSE;
			}
		}
	}
	
	/*******************************
	* Add log
	* @param str $title log title
	* @param str $content log's content
	* @return bol whether success
	*******************************/
	function add_log($title,$content)
	{
		//check permission
		if (! $this->uid) return $this->err(100,'Wrong permission');
		
		$comment=serialize(array('num'=>0));
		$data=array(
				'uid'=>$this->uid,
				'title'=>$title,
				'content'=>$content,
				'time'=>date('Y-m-d H-i-s',time()),
				'comment'=>$comment
				);
		if ($this->db->insert('log',$data))
			return $this->err(0,0);
		else
			return $this->err(101,'Database error');
	}
	
	/*******************************
	* Add comment
	* @param int $lid log id
	* @param str $content comment's content
	* @return bol whether success
	*******************************/
	function add_comment($lid,$content)
	{
		//check permission
		if (! $this->uid) return $this->err(200,'Wrong permission');
		
		//add comment
		$comment=unserialize(end($this->db->get_where('log',array('id'=>$lid))->result())->comment);
		$comment[$comment['num']+1]=array(
									'uid'=>$this->uid,
									'content'=>$content,									
									'time'=>date('Y-m-d H-i-s',time())
									);
		$comment['num']=$comment['num']+1;
		$comment_update=serialize($comment);
		$data=array(
				'id'=>$lid,
				'comment'=>$comment_update
				);
		if ($this->db->update('log',$data,array('id'=>$lid)))
			return $this->err(0,0);
		else
			return $this->err(201,'Database error');
	}
	
	/*******************************
	* Get list
	* @param int $lim limit
	* @param int $off offset
	* @return $a=array([$lid]=>array('uid','title','content','time',
									 'comment'=>array('num'=>number of comments,
									 					[comment_id]=>array('uid','content','time'))));
	*******************************/
	function get_list($lim,$off)
	{
		//check permission
		if (! $this->uid) return $this->err(300,'Wrong permission');
		$this->db->order_by('id','desc');
		$a=$this->db->get('log',$lim,$off)->result_array();
		foreach ($a as $i=>$item) $a[$i]['comment']=unserialize($a[$i]['comment']);
		return $a;
	}
	

	/*******************************
	* Error functions
	* @access private, only use within this class
	* @param int $code is the error code
	* @param str $msg is the error message
	* @return bol whether the function success
	*******************************/
	private function err($code,$msg)
	{
		if ($code!=0)
		{
			$this->err_code=$code;
			$this->err_msg=$msg;
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