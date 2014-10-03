<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Clubs_topic
 * 
 * HFICampus的社团话题模型类
 * 
 * 
 * @package model
 * @author halfcoder
 * @copyright One Technology Team
 * @version $Id$
 * @access public
 */

class Clubs_topic extends SAE_Model {
    public function __construct() {
        parent::__construct();
        $this->_options['tableName'] = 'club_topic';
        $this->_options['subContent']['tableName'] = 'club_topic_reply';
        $this->_options['subContent']['associatedKey'] = 'tid';
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
					'reply_count'   => 0,
					'click_count'   => 0
					);
		
		return parent::create($data);
	}
	
	public function delete($tid){
		//check input
		if (! is_numeric($tid)){
            throw new InvalidArgumentException('Invalid Argument: $tid');
		}
		
		return (parent::deleteByPk($tid) && 
				parent::del_subContent(array($this->_options['subContent']['tableName'].'.'.$this->_options['subContent']['associatedKey']=>$tid))
				);
	}
	
	public function edit($tid, $title = NULL, $content = NULL){
		//check input
		if (! is_numeric($tid)){
            throw new InvalidArgumentException('Invalid Argument: $tid');
		}
		
		//clean up the data
		$this->load->library('security');
		if (isset($title) && is_string($title))
			$data['title'] = htmlentities($this->security->xss_clean($title));
		if (isset($content) && is_string($content))
			$data['content'] = htmlentities($this->security->xss_clean($content));
			
		return parent::update($data,array($this->_options['tableName'].'.id'=>$tid));
	}
	
	public function read($tid){
		//check input
		if (! is_numeric($tid)){
            throw new InvalidArgumentException('Invalid Argument: $tid');
		}
		
		//get database record
		$return['topic']=parent::readByPk($tid);
		$return['comments']=parent::get_subContent_list(array($this->_options['subContent']['tableName'].'.tid'=>$tid));
		
		if (($return['topic']!==FALSE) && ($return['comments']!==FALSE)){
			//succuss then change the click number
			parent::update(array('click_count'=>$return['topic']['click_count']+1),
						   array($this->_options['tableName'].'.id'=>$tid));
			
			return $return;
		}
		else{
			return FALSE;
		}
	}
	
	public function reply($tid,$content){
		//check input
		if (! is_numeric($tid)){
            throw new InvalidArgumentException('Invalid Argument: $tid');
		}
		
		//clean up~
		$this->load->library('security');
		$data=array(
					'content'   => htmlentities($this->security->xss_clean($content)),
					'tid'       => $tid,
					'author_id' => $this->user->id
					);
		
		//insert into database
		$reply_id=parent::add_subContent($data);
		if ($reply_id !== FALSE){
			//change number @todo
			parent::update(array('reply_count'=>'reply_count'+1),
						   array($this->_options['tableName'].'.id'=>$tid));
			return $reply_id;
		}
		else{
			return FALSE;
		}
	}
}

/* End of file clubs_topic.php */
/* Location: ./application/models/clubs_topic.php */