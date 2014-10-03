<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Clubs_page
 * 
 * HFICampus的社团页面模型类
 * 
 * 
 * @package model
 * @author halfcoder
 * @copyright One Technology Team
 * @version $Id$
 * @access public
 */

class Clubs_page extends SAE_Model {
    public function __construct() {
        parent::__construct();
        $this->_options['tableName'] = 'club_page';
        $this->_options['subContent']['tableName'] = 'club_page_comment';
        $this->_options['subContent']['associatedKey'] = 'pid';
    }
	
	public function create($cid,$title,$content){
		//check input
		if (! is_numeric($cid)){
            throw new InvalidArgumentException('Invalid Argument: $cid');
		}
		//clean up the data
		$this->load->library('security');
		$data=array(
					'cid'           => $cid,
					'title'         => htmlentities($this->security->xss_clean($title)),
					'content'       => htmlentities($this->security->xss_clean($content)),
					'author_id'     => $this->user->id,
					);
		
		return parent::create($data);
	}
	
	public function delete($pid){
		//check input
		if (! is_numeric($pid)){
            throw new InvalidArgumentException('Invalid Argument: $pid');
		}
		
		return (parent::deleteByPk($pid) && 
				parent::del_subContent(array($this->_options['subContent']['tableName'].'.'.$this->_options['subContent']['associatedKey']=>$pid))
				);
	}
	
	public function edit($pid, $title = NULL, $content = NULL){
		//check input
		if (! is_numeric($pid)){
            throw new InvalidArgumentException('Invalid Argument: $pid');
		}
		
		$data=array();
		//clean up the data
		$this->load->library('security');
		if (isset($title) && is_string($title))
			$data['title'] = htmlentities($this->security->xss_clean($title));
		if (isset($content) && is_string($content))
			$data['content'] = htmlentities($this->security->xss_clean($content));
			
		return parent::update($data,array($this->_options['tableName'].'.id'=>$pid));
	}
	
	public function read($pid){
		//check input
		if (! is_numeric($pid)){
            throw new InvalidArgumentException('Invalid Argument: $pid');
		}
		
		$return['page']=parent::readByPk($pid);
		$return['comments']=parent::get_subContent_list(
									array($this->_options['subContent']['tableName'].'.'.$this->_options['subContent']['associatedKey']=>$pid
									));
		if ($return['page']){
			return $return;
		}
		else{
			throw new HTTPException(404);
		}
	}
	
	public function comment($pid,$content){
		//check input
		if (! is_numeric($pid)){
            throw new InvalidArgumentException('Invalid Argument: $pid');
		}
		
		//clean up~
		$this->load->library('security');
		$data=array(
					'content'   => htmlentities($this->security->xss_clean($content)),
					'pid'       => $pid,
					'author_id' => $this->user->id
					);
		
		//insert into database
		return parent::add_subContent($data);
	}
}

/* End of file clubs_page.php */
/* Location: ./application/models/clubs_page.php */