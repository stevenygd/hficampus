<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Academic model by StevenY originally.
 * Now it's adapted by halfcoder.
 */
class Aca extends SAE_Model {
	function __construct()
	{
		parent::__construct();
		$this->tableName = 'aca';
	}
	
	/*Basic course actions*/
	/*******************************
	* Create Courses
	* @warning please first create a user group with the name of 'aca_'+course name
	* @param int $gid is the id of the created user group
	* @param int $year is the academic year of the course
	* @param int $type is the type of the course, view $config from /config/aca.php
	* @param str $description is the description of the course
	* @return int (if success) return the new course id
			  bol (if fail) FALSE
	*******************************/
	function create($name,$gid,$year,$type,$description)
	{
		$appendix=array("themes"=>array("page"=>array(
													  "intro"=>"Page is the places where teachers or TAs share their thoughts with others!",
													  "imgurl"=>"/images/course/page.jpg"
										),
										"question"=>array(
														  "intro"=>"Everybody can throw out questions, no matter how wired they sound!",
														  "imgurl"=>"/images/course/question.jpg"
										),
										"netdisk"=>array(
														  "intro"=>"The netdisk of the course contains the teaching materials such as class handouts, syllubus, slideshows and etc.",
														  "imgurl"=>"/images/course/netdisk.jpg"
										),
										"calender"=>array(
														  "intro"=>"Here are the course schedule! Make sure you fellow~",
														  "imgurl"=>"/images/course/calender.jpg"
										)
						),
						"config"=>array(
										"head_img"=>"images/course/head.jpg"
						)
		);
		return parent::create(array(
				'auth' => $this->user->id,
				'gid' => $gid,
				'name' => $name,
				'subtype' => $type,
				'year' => $year,
				'description' => $description,
				'created_time' => date('y-m-d H:i:s',time()),
				'appendix'=>serialize($appendix)
		));
	}
	
	/*******************************
	* Delete Course
	* @warning please delete the group first
	* @param int $cid id of the course
	* @return bol whether the function success
	* @todo
	*******************************/
	function delete($cid)
	{		
		//get gid
		//$gid=end($this->db->get_where('aca',array('id'=>$cid))->result())->gid;
				
		//database operation
		$this->db->trans_begin();
		$this->db->delete('aca',array('id'=>$cid));//delete the basic course
		
		$pages=$this->db->get_where('aca_page',array('cid'=>$cid))->result_array();
		$this->db->delete('aca_page',array('cid'=>$cid));//delete all pages
		
		$questions=$this->db->get_where('aca_question',array('cid'=>$cid))->result_array();
		$this->db->delete('aca_question',array('cid'=>$cid));//delete all questions
		
		foreach ($pages as $item)//delete all comments
		{
			$this->db->delete('aca_page_comment',array('pid'=>$item['id']));
		}
		
		foreach ($questions as $item)//delete all answers
		{
			$this->db->delete('aca_question_comment',array('qid'=>$item['id']));
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			throw new MY_Exception('Database Error', 204);
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}
	
	/*******************************
	* Get Course Info
	* @param arr $where: where conditions
	* @param int $lim: course id
	* @param int $off: course id
	*******************************/
	function get_course_list($where,$order,$lim,$off)
	{
		if (is_array($where)) $this->db->where($where);
		if (is_array($order)) $this->db->order_by($order);
		$this->db->select('group.id, group.auth, 
						   user_info.cnfn, user_info.cnln, user_info.enn, 
						   aca.*,aca_subtype.subtype_name,aca_type.type_name');
		$this->db->join('aca_subtype','aca_subtype.id=aca.subtype');
		$this->db->join('aca_type','aca_type.id=aca_subtype.type_id');
		$this->db->join('group','group.id=aca.gid','inner');
		$this->db->join('user_info','user_info.uid=group.auth','inner');
		//get info from database
		$return=$this->db->get('aca',$lim,$off)->result_array();
		if ($return)
			return $return;
		else {
			//return FALSE;
			throw new MY_Exception('Can\'t get data:'.serialize($return), 1203);
		}
	}	
		
	/**
	 * Get gid from certain cid
	 *
	 * @param int $cid course id
	 * @return boolean FALSE if failed
	 		   int $gid if success
	 */
	function get_gid($cid)
	{
		$info=$this->db->get_where('aca',array('id'=>$cid));
		if ($info->num_rows()!=1) {
			throw new MY_Exception('wrong input', 2101);
		}
		else
			return $gid=end($info->result())->gid;
	}
	
	function get_types()
	{
		return $this->db->get('aca_type')->result_array();
	}
	
	function get_subtypes()
	{
		return $this->db->get('aca_subtype')->result_array();
	}
	
	function get_teachers()
	{
		$this->db->select('user_sub.uid, user_sub.gid, user_info.cnfn, user_info.cnln, user_info.enn');
		$this->db->join('user_info','user_info.uid=user_sub.uid');
		return $this->db->get_where('user_sub',array('user_sub.gid'=>3))->result_array();
	}
}

/* End of file aca.php */
/* Location: ./application/models/aca.php */