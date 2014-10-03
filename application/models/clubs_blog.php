<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Clubs_blog
 * 
 * HFICampus的社团博客模型类
 * 
 * 
 * @package model
 * @author halfcoder
 * @coauthor StevenY.
 * @copyright One Technology Team
 * @version $Id$
 * @access public
 */

class Clubs_blog extends SAE_Model {
    public function __construct() {
        parent::__construct();
        $this->_options['tableName'] = 'club_blog';
        $this->_options['subContent']['tableName'] = 'club_blog_comment';
        $this->_options['subContent']['associatedKey'] = 'bid';
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
					'comment_count' => 0
					);
		return parent::create($data);
	}
	
	public function delete($bid){
		//check input
		if (! is_numeric($bid)){
            throw new InvalidArgumentException('Invalid Argument: $bid');
		}
		return parent::deleteByPk($bid);
	}
	
	public function edit($bid, $title = NULL, $content = NULL){
		//check input
		if (! is_numeric($bid)){
            throw new InvalidArgumentException('Invalid Argument: $bid');
		}
		$data = array();
		//clean up the data
		$this->load->library('security');
		if (isset($title) && is_string($title))
			$data['title'] = htmlentities($this->security->xss_clean($title));
		if (isset($content) && is_string($content))
			$data['content'] = htmlentities($this->security->xss_clean($content));
		return parent::updateByPk($data, $bid);
	}
	
	public function read($bid){
		//return is_numeric($bid);
		//check input
		if (!is_numeric($bid)) {
            throw new InvalidArgumentException('Invalid Argument: $bid');
		}
		//get database records
		$return = array();
		$return['blog']=parent::readByPk($bid);
		$return['comments']=parent::get_subContent_list(array($this->_options['subContent']['tableName'].'.bid'=>$bid));
		
		if ($return['blog']){
			//succuss then change the click number
			/*
			parent::update(array('click_count'=>$return['blog']['click_count']+1),
						   array($this->_option['tableName'].'id'=>$bid));
			*/
			//return data
			return $return;
		}
		else{
			throw new HTTPException(404);
		}
	}
	
	public function comment($bid,$content){
		//check input
		if (! is_numeric($bid)){
            throw new InvalidArgumentException('Invalid Argument: $bid');
		}
		
		//clean up~
		$this->load->library('security');
		$data=array(
					'content'   => htmlentities($this->security->xss_clean($content)),
					'bid'       => $bid,
					'author_id' => $this->user->id
					);
		
		//insert into database
		return parent::add_subContent($data);
	}
}

/* End of file clubs_blog.php */
/* Location: ./application/models/clubs_blog.php */