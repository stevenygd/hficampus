<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Aca_question
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
class Aca_question extends SAE_Model {
    
	public function __construct() {
        parent::__construct();
        $this->_options['tableName'] = 'aca_question';
        $this->_options['subContent']['tableName'] = 'aca_question_comment';
        $this->_options['subContent']['associatedKey'] = 'qid';
    }

    /*Basic Question functions*/
    /**
     * Post a question
     * @param int $cid course id
     * @param string $text question
     * @return boolean whether success
     */
    function add_question($cid,$title,$text)
    {
        //check input
        if ($this->db->get_where('aca',array('id'=>$cid))->num_rows!=1) {
            //return $this->err(1101,'Wrong Input');
            $this->errorhandler->setError(1101,'Wrong Input');
            return FALSE;
        }
                  
        //database operation
        $data=array(
                    'auth'=>$this->user->id,
                    'cid'=>$cid,
                    'title'=>$title,
                    'text'=>$text,
                    'created_time'=>date('y-m-d H:i:s',time())
                    );
        if (parent::create($data))
        {
            //$this->err(0,0);
            return $this->db->insert_id();
        }
        else {
            //return $this->err(1104,'Database error');
            $this->errorhandler->setError(1104,'Database error');
            return FALSE;
        }
    }

    /**
     * Get specific question page
     * @param int $id: page id
     * @return FALSE if fail
               array(
                    'question'=>page infor,
                    'comment'=>all comments
                    )
     */
    function get_question($qid)
    {
        if (is_numeric($qid))//check input
        {
			$this->db->select('aca.name,aca.gid,'
							  .$this->_options['tableName'].'.*,'
							  .'user_info.cnfn, user_info.cnln, user_info.enn');
			$this->db->join('aca','aca.id='.$this->_options['tableName'].'.cid');
			$this->db->join('user_info','user_info.uid='.$this->_options['tableName'].'.auth');
			$result['question']=parent::read(array($this->_options['tableName'].'.id'=>$qid));
			
			$this->db->select($this->_options['subContent']['tableName'].'.*,'
							  .'user_info.cnfn, user_info.cnln, user_info.enn');
			$this->db->join('user_info','user_info.uid='.$this->_options['subContent']['tableName'].'.auth');
            $result['comment'] = parent::get_subContent_list(array(
                    ($this->_options['subContent']['associatedKey']) => $qid
                ));
/*			$result = array(
                'question' => parent::read(array('id'=>$qid)),
                'comment'=> parent::get_subContent_list(array(
                    ($this->_options['subContent']['associatedKey']) => $qid
                ))
            );
*/
            if (count($result['question'])>0) {
                return $result;
            }
            else {
                //return $this->err(1203,"Something Wrong I don't know");
                $this->errorhandler->setError(1203,"Something Wrong I don't know");
                return FALSE;
            }
        }
        else {
            //return $this->err(1202,'Wrong Input');
            $this->errorhandler->setError(1202,'Wrong Input');
            return FALSE;
        }
    }

    /**
     * Get question list
     * @param array $where:where coundition
     * @param string $order order
     * @param int $lim limit
     * @param int $off offset
     * @auth don't need to check permission since everybody can see a list
     */
    function get_question_list($where,$order,$lim,$off)
    {
        //check data and create SQL query
        if (! is_numeric($lim+$off)) {
            //return $this->err(702,'Wrong Input');
            $this->errorhandler->setError(702,'Wrong Input');
            return FALSE;
        }
		$this->db->join('aca','aca.id='.$this->_options['tableName'].'.cid');
		$this->db->join('user_info','user_info.uid='.$this->_options['tableName'].'.auth');
        $return = parent::get_list($where, 'aca.name, aca.gid,'
						  				   .$this->_options['tableName'].'.*,'
						  				   .'user_info.cnfn, user_info.cnln, user_info.enn', 
									$order, $lim, $off);
		
        if (! $return) {
            //return $this->err(703,'Database Error');
            $this->errorhandler->setError(703,'Database Error');
            return FALSE;
        }
        else
            return $return;
    }
    
	/**
     * comment on a specific question
	 *
     * @param int $id is the id of the question
     * @param string $text is the comment content
     * @return whether success
     */
	function comment($id,$text)
	{		
		$data=array(
					'qid'=>$id,
					'auth'=>$this->user->id,
					'time'=>date('y-m-d H:i:s',time()),
					'text'=>$text
					);
        $this->_options['subContent']['tableName'] = 'aca_question_comment';
        $this->_options['subContent']['associatedKey'] = 'qid';
		if (parent::add_subContent($data))
			return TRUE;
		else
			return $this->errhandler->setError(1205,'Database error');
	}
		
}

/* End of file aca_question.php */
/* Location: ./application/models/aca_question.php */