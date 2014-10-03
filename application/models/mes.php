<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*message model by StevenY.*/
class Mes extends CI_Model {
	
	var $err_code;
	var $err_msg;
	var $uid=FALSE;
	
	/*******************************
	* Construct function
	*******************************/
	function __construct()
	{
		parent::__construct();
		$this->uid = $this->user->id;
	}
		
	/*******************************
	* Send personal message or notice
	* @param int $id reciever's user id; or reciever group's id
	* @param str $title title of the message
	* @param str $text content of the message
	* @param bol $not whether the message is a notice
	* @return bol whether the success
	********************************/
	function sendto($id,$title,$text,$not)
	{
		if ($not) 
			$table='not';
		else 
			$table='msg';
		
		//检查id是否有效
		if ($not)
		{
			if ($this->db->get_where('group',array('id'=>$id,'auth'=>$this->uid))->num_rows()!=1)
				return $this->err(101,'接受用户组ID错误'.$table);
		}
		else
		{
			if (($this->db->get_where('user',array('id'=>$id))->num_rows() != 1) 
				|| ($this->uid==$id))
				return $this->err(101,'接受者用户ID错误'.$table);
		}
		//将$text的信息过滤
		$this->load->library('security');
		$text=$this->security->xss_clean($text);
		$title=$this->security->xss_clean($title);
		$text = htmlentities($text,ENT_QUOTES,"UTF-8");//形成HTML实体（转义）
		$title = htmlentities($title,ENT_QUOTES,"UTF-8");//形成HTML实体（转义）

		/*$title=preg_replace('/<script.*?>.*?<\/script>/s','',$title);*/
		//将信息记录进数据库		
		$data=array('auth'=>$this->uid,
					'title'=>$title,
					'msg'=>$text,
					'time'=>date('y-m-d H:i:s',time()),
					);
		if ($not)
			$data['gid']=$id;
		else 
			$data['to']=$id;
		$this->db->set($data);
		if(! $this->db->insert($table))
			return $this->err(102,'写入数据库错误...');
		else  
			return $this->err(0,0);
	}
	
	/*******************************
	* Get message between recent user and the objected user ($eid)
	* @warning Can't get message and notice at the same time.
	* @param int $eid Reciever's user id
	* @param bol $not Whether the message is a notice
	* @param bol $read The read status of the recieving message
	* @param int $lim Limit
	* @param int $off Offset
	* @param str $order The order of the message. 'column_name acs/decs'
	* @return arr array([id]=>message content)
	********************************/
	function get_msg($eid,$not,$read,$lim,$off,$order)
	{
		if (! is_bool($not)) return $this->err(200,'Wrong input');
		if (!(is_numeric($lim) && is_numeric($off))) return $this->err(200,'Wrong input');
		//check permission @todo
		if ($not)
		{
			//检查用户组ID
			if ($this->db->get_where('user_sub',array('uid'=>$this->uid,'gid'=>$eid))->num_rows()!=1)
				return $this->err(201,'You have to belong to the group in order to get its notice');
			$table='not';
		}
		else
		{
			//检查$eid是否有效
			if ($this->db->get_where('user',array('id'=>$eid))->num_rows() != 1)
				return $this->err(202,'WRONG ID');
			$table='msg';
		}
		
		//获取信息
		if (is_bool($read)) $this->db->where('read',$read);
		if ($not)
			$this->db->where('gid',$eid);
		else
		{
			$aids=array($this->uid,$eid);
			$this->db->where_in('auth',$aids);
			$this->db->where_in('to',$aids);
		}

		$this->db->order_by($order);
		$message=$this->db->get($table,$lim,$off)->result_array();
		if ($message)
		{
			$this->err(0,0);
			return $message;
		}
		else 
			return $this->err(203,"Don't hack meT.T");
	}
	
	//$fid 发送者id,如果不是数字的话则默认为全部; $tid:接受者id; $read:是否已读; $not:是否是通知;
	/******************************
	* get number of qulified message 获取符合条件的信息的数量
	* @param int $fid the id of the user who sends there
	* @param int $tid the id of the user to whom send
	* @param bol $not whether it's notice(if it's not bol, default will be FALSE)
	* @param bol $read whether it is read;(if it's not bol, default will be FALSE)
	* @return int number
	******************************/
	function get_num($fid,$tid,$not,$read,$order)
	{
		//@todo
		if (is_numeric($fid)) $this->db->where('auth',$fid);
		if (! is_numeric($tid)) return $this->err(2001,'Something Wrong');
		if (! is_bool($not)) return $this->err(2001,'Damn you! Wrong parament');
		if ($not)
		{
			$this->db->where('gid',$tid);
			$table='not';
		}
		else
		{
			$table='msg';
			$this->db->where('to',$tid);
		}
		if (is_bool($read)) $this->db->where('read',$read);		
		if (is_string($order))$this->db->order_by($order);
		return $this->db->count_all_results($table);
	}
	
	/******************************
	* get message list
	* @param int $eid: requested object user id;
	* @param arr $like: match condition:array('field_name'=>'match')
	* @param bol $not: whether it is notice; 
	* @param bol $read: whether it is read;
	* @param int $lim: limit
    * @param int $off: offset
	* @return array()
	******************************/
	function get_list($where,$order,$not,$read,$lim,$off)
	{
		//Check variables
		if (! is_numeric($lim+$off)) return $this->err(901,'SHIT YOU SHIT YOU SHIT YOU !');
		if (! is_bool($not)) $table='msg';
		if ($not) 
			$table='not';
		else 
			$table='msg';
		if (! is_bool($read)) $read= FALSE;
		if (is_array($where)) $this->db->where($where);
		$this->db->order_by($order);
		$this->db->where('read',$read);
		$set=$this->db->get($table,$lim,$off);
		if ($set->num_rows()>0)
			return $set->result_array();
		else
			return FALSE;
	}
		
	//change the read status of messages
	//$time is the newest reading time of $uid
	//$oid is the id of the person you are talking to, can't be a group id
	function read($oid)
	{
		//database operation
		$this->db->set('read',TRUE);
		$this->db->where('auth',$oid);
		$this->db->where('to',$this->uid);
		$this->db->where('read',FALSE);
		if ($this->db->update('msg'))
			return $this->err(0,0);
		else
			return $this->err(802,'Database Error');
	}
	
	//delete message
	//$mid is the id of the message; $not is a bool whether it is a notice
	//@todo
	function delmsg($mid,$not)
	{
		//Check the variables
		if (! is_numeric($mid)) return $this->err(901,'SHIT YOU REALLY!');
		if (! is_bool($not)) $not=FALSE;

		//if it's notice or not
		if ($not)
		{
			//check the authority@todo
			$gid=$this->db->get_where('not',array('id'=>$mid))->row()->gid;
			if ($this->db->get_where('group',array('id'=>$gid,'auth'=>$this->user->id))->num_rows()!=1)
				return $this->err(902,'No permission~~~Sorry...');
			$table='not';
		}
		else
		{
			//check authority
			if ($this->db->get_where('msg', array('id'=>$mid,'auth'=>$this->uid,'notice'=>$not))->num_rows() !=1)				
				return $this->err(903,'TOO BAD SO SAD...');
			$table='msg';
		}
		
		if ($this->db->delete($table,array('id'=>$mid)))
			return $this->err(0,0);
		else
			return $this->err('902','I DONT KNOW WHAT THE HELL DID YOU DO!');
	}
	
	/**
	 * Edit Notice, of course you can't edit ...message...
	 *
	 * @param int $nid notice id
	 * @param str $title modified title of the message
	 * @param str $text modified text
	 * @return bol whether success
	 */
	function editnot($nid,$title = NULL,$text = NULL)
	{
		//check input
		if (!is_numeric($nid))
			return $this->err(1301,'Wrong input...again!!!');
		//check permission
		$gid=$this->db->get_where('not',array('id'=>$nid))->row()->gid;
		if ($this->db->get_where('group',array('id'=>$gid,'auth'=>$this->uid))->num_rows()!=1)
			return $this->err(1302,'NO permission~~~Tobad sosad~');
			
		//@todo sanity check?!!!
		if (isset($title))
			$data['title']=$title;
		if (isset($text))
			$data['text']=$text;
		
		if ($this->db->update())
			return $this->err(0,0);
		else
			return $this->err(1303,'Database error');
	}
		
	/*******************************
	* Email the message
	* @param int $mid the message id that's going to add to
	* @param int $to group id, it will mail to all members in the group
	*        arr $to list of email address 
	* @return array()
	* @todo
	*******************************/
	function email()
	{
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
			$this->errorhandler->setError($code,$msg);
			return FALSE;
		}
		else
		{
			$this->err_code=0;
			$this->err_msg='SUCCESS';			
			return TRUE;
		}
	}
	
	//返回错误代码
	function get_err_code()
	{
		return $this->err_code;
	}
	
	//返回错误信息
	function get_err_msg()
	{
		return $this->err_msg;
	}
}
/*END OF FILE MSG.PHP */