<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Aca_page
 * 
 * HFICampus的课程部分（Aca）的页面（Page）模型类
 * 从Aca模型类中提取出来
 * 
 * @package model
 * @author halfcoder
 * @copyright One Technology Team
 * @version $Id$
 * @access public
 */
class Aca_page extends SAE_Model {
    public function __construct() {
        parent::__construct();
        $this->_options['tableName'] = 'aca_page';
        $this->_options['subContent']['tableName'] = 'aca_page_comment';
        $this->_options['subContent']['associatedKey'] = 'pid';
    }
    
    /**
     * Get page list
     *
     * don't need to check permission since everybody can see a list
     * 
     * @param array $where:where coundition
     * @param string $order order
     * @param integer $lim limit
     * @param integer $off offset
     */
    public function get_list($where, $order, $lim, $off) {
        //check data and create SQL query
        if (! is_numeric($lim+$off)) {
            $this->errorhandler->setError(702, 'Wrong Input');
            return FALSE;
        }
		
		$this->db->join('aca','aca.id='.$this->_options['tableName'].'.cid');
		$this->db->join('user_info','user_info.uid='.$this->_options['tableName'].'.auth');
        $result = parent::get_list($where, 'aca.name,aca.gid,
											user_info.enn,user_info.cnfn, user_info.cnln,'
											.$this->_options['tableName'].'.title,'
											.$this->_options['tableName'].'.created_time,'
											.$this->_options['tableName'].'.latest_update,'
											.$this->_options['tableName'].'.cid,'
											.$this->_options['tableName'].'.auth,'
											.$this->_options['tableName'].'.id',
								   $order, $lim, $off);
        if ($result) {
            return $result;
        }
        else {
            $this->errorhandler->setError(703, 'Database Error');
            return FALSE;
        }
    }

    /**
     * Add course page
     * @warning this function can send the notice or email, please use /model/mes.php
     * 
     * @param integer $cid the course id that's going to add to
     * @param string $title the title of the course
     * @param string $text the content
     * @param boolean $not whether to send as a notice
     * @param boolean $email whether to send as a email
     * @return boolean whether success
     */
    public function create($cid, $title, $text, $not, $email)
    {            
        //xss security clean
		$this->load->library('security');
		$title=$this->security->xss_clean($title);
		$text=$this->security->xss_clean($text);
		//escape html real body
		//$title = htmlentities($title,ENT_QUOTES,"UTF-8");
		//$text =  htmlentities($text,ENT_QUOTES,"UTF-8");
		
        //update the database
        $result = parent::create(array(
			'auth'=>$this->user->id,
            'cid'=>$cid,
            'title'=>$title,
            'text'=>$text,
            'not'=>is_int($not)?$not:0,
            'email'=>is_bool($email)?$email:FALSE,
            'created_time'=>date('y-m-d H:i:s',time())
        ));
        if ($result)
        {
            //$this->err(0,0);
            return $result;
        }
        else {
            //return $this->err(502,'Database error');
            $this->errorhandler->setError(502,'Database error');
            return FALSE;
        }
    }

    /**
     * Edit course page
     * @param string $title: updated title
     * @param string $text: updated content
     */
    public function update($pid,$title,$text)
    {
        //update database
		$this->load->library('security');
        if (is_string($title)) 
		{
			//check input
			$title=$this->security->xss_clean($title);
			//$title = htmlentities($title,ENT_QUOTES,"UTF-8");
			$a['title']=$title;
		}
        if (is_string($text))
		{
			//check input
						//return var_dump($text);

			$text=$this->security->xss_clean($text);
						//return var_dump($text);
			//$text = htmlentities($text,ENT_QUOTES,"UTF-8");
			$a['text']=$text;
		}
        if (isset($a))
            if (parent::update($a, array('id'=>$pid))) {
                return TRUE;
            }
            else {
                //return $this->err(602,'Database Error');
                $this->errorhandler->setError(602,'Database Error');
                return FALSE;
            }
        else {
            return TRUE;
        }
    }

    /**
     * Get specific course page
     * @param int $id: page id
     * @return FALSE if fail
               array(
                    'page'=>page infor,
                    'comment'=>all comments
                    )
     */
    public function read($id) {
        if (is_numeric($id))//check input
        {
			$this->db->select('aca.name, aca.gid,'
							  .$this->_options['tableName'].'.*,'
							  .'user_info.cnfn, user_info.cnln, user_info.enn');
			$this->db->join('aca','aca.id='.$this->_options['tableName'].'.cid');
			$this->db->join('user_info','user_info.uid='.$this->_options['tableName'].'.auth');
			$result['page']=parent::read(array($this->_options['tableName'].'.id' => $id));
			
			$this->db->select($this->_options['subContent']['tableName'].'.*,'
							  .'user_info.cnfn, user_info.cnln, user_info.enn');
			$this->db->join('user_info','user_info.uid='.$this->_options['subContent']['tableName'].'.auth');
			$result['comment']=parent::get_subContent_list(array(
                    ($this->_options['subContent']['associatedKey']) => $id));
/*			$result = array(
                'page' => parent::read(array('id' => $id)),
                'comment' => parent::get_subContent_list(array(
                    ($this->_options['subContent']['associatedKey']) => $id
                ))
            );
*/
            if (count($result['page'])==0) {
                //return $this->err(803, 'Nothing gotten');
                $this->errorhandler->setError(803, 'Nothing gotten');
                return FALSE;
            }
            else {
				//deal with the output
				//$result['page']['text']=
                return $result;
            }
        }
        else {
            //return $this->err(802, 'Wrong INput');
            $this->errorhandler->setError(802, 'Wrong INput');
            return FALSE;
        }
            
    }

    /*******************************
    * Delete course page
    * @warning Remember to delete associated files
    * @param int $pid: id of the page
    *******************************/
    public function delete($pid)
    {
        //delete database
        if ($this->db->delete('aca_page',array('id'=>$pid))) {
            //return $this->err(0,0);
            return TRUE;
        }
        else {
            //return $this->err(502,'Database Error');
            $this->errorhandler->setError(502,'Database Error');
            return FALSE;
        }
    }
	
	/**
     * comment on a specific page
	 *
     * @param int $id is the id of a page
     * @param string $text is the comment content
     * @return whether success
     */
	function comment($id,$text)
	{		
        //xss security clean
		$this->load->library('security');
		$text=$this->security->xss_clean($text);
		//escape html real body
		//$text =  htmlentities($text,ENT_QUOTES,"UTF-8");
		$data=array(
					'pid'=>$id,
					'auth'=>$this->user->id,
					'time'=>date('y-m-d H:i:s',time()),
					'text'=>$text
					);
        $this->_options['subContent']['tableName'] = 'aca_page_comment';
        $this->_options['subContent']['associatedKey'] = 'pid';
		$add=parent::add_subContent($data);
		if ($add!==FALSE)
			return $add;
		else
			return $this->errorhandler->setError(601,'Something Wrong');
	}
}

/* End of file aca_page.php */
/* Location: ./application/models/aca_page.php */