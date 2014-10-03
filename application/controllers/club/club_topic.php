<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Club_topic
 *
 * HFICampus的Club_topic控制器类
 *
 * @package controller
 * @author halfcoder
 * @copyright One Technology Team
 * @version $Id$
 */
class Club_topic extends SAE_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('clubs_topic');
		throw new MY_Exception('Club is coming! Please wait~',0,'home',10);
    }
	
    public function get($mid = NULL, $nid =NULL, $attach = '') {
         if($mid) {
            if(!is_numeric($mid)) {
                throw new MY_Exception("Wrong Club Id");
            }
			$this->_output['cid']=$mid;
			$this->_output['tid']=$nid;
            //检查输入的mid的club是否存在，顺便取得club数据
            $this->load->model('clubs');
            $this->_output['club'] = $this->clubs->readByPk($mid, 'name');
            if($nid) {
                if(!is_numeric($nid)) {
                    throw new MY_Exception("Wrong Page Id");
                }
                $this->_output = array_merge($this->_output, $this->clubs_topic->read($nid));
                $this->push('club/topic_view', $this->_output);
            }
            else {
                var_dump($this->clubs_topic->get_list());
            }
        }
        else {
            throw new MY_Exception('The club is not specified.');
        }
    }

    public function delete($mid = NULL, $nid =NULL, $attach =NULL) {
        if($mid) {
            if(!is_numeric($mid)) {
                throw new MY_Exception("Wrong Club Id");
            }
            //检查输入的mid的club是否存在，顺便取得club数据
            $this->load->model('clubs');
            $this->_output['club'] = $this->clubs->readByPk($mid, 'name');
            if($nid) {
                if(!is_numeric($nid)) {
                    throw new MY_Exception("Wrong Page Id");
                }
                if($attach) {

                }
                else {
                    $this->clubs_topic->delete($nid);
                    redirect('club/' . $mid . '/topic/edit');
                }
            }
            else {
                throw new MY_Exception('The topic article is not specified.');
            }
        }
        else {
            throw new MY_Exception('The club is not specified.');
        }
    }

    public function create($mid = NULL, $nid =NULL, $attach =NULL) {
        if($mid) {
            //检查输入的mid的club是否存在，顺便取得club数据
            $this->load->model('clubs');
            $club = $this->clubs->readByPk($mid, 'name');
            if($this->input->server('REQUEST_METHOD') === 'GET') {
                $this->push('club/topic_create', array(
                    'club' => $club,
                    'cid' => $mid
                ));
            }
            else {
                if($nid && $attach) {
                    if(!is_numeric($mid)) {
                        throw new MY_Exception("Wrong Club Id");
                    }
                    switch ($attach) {
                        case 'comment':
                            //@todo 数据有效性检查
                            if($this->clubs_topic->reply($nid, $this->input->post('content', TRUE))) {
                                redirect('club/' . $mid . '/topic/' . $nid);
                            }
                            else {
                                throw new MY_Exception('Commenting Failed.', 1, 'club/' . $mid . '/topic/' . $nid, 3);
                            }
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }
                else {
                    //@todo 数据有效性检验
                    $newBlogId = $this->clubs_topic->create($mid, $this->input->post('title', TRUE), $this->input->post('content', TRUE));
                    redirect('club/' . $mid . '/topic/' . $newBlogId);
                }
            }
        }
        else {
            throw new MY_Exception('The club is not specified.');
        }
    }

    public function edit($mid = NULL, $nid =NULL, $attach =NULL) {
        if($mid) {
            //检查输入的mid的club是否存在，顺便取得club数据
            $this->load->model('clubs');
            $club = $this->clubs->readByPk($mid, 'name');
            if($nid) {
                if(!is_numeric($mid)) {
                    throw new MY_Exception("Wrong Course Id");
                }
                if($attach) {
                    switch ($attach) {
                        case 'comment':
                            $this->_getEditComment($mid, $nid);
                            break;
                        default:
                            # code...
                            break;
                    }
                }
                else {
                    if($this->input->server('REQUEST_METHOD') === 'GET') {
                        $this->push('club/topic_edit', array_merge(array(
                            'club' => $club,
                            'cid' => $mid,
                            'tid' => $nid
                        ), $this->clubs_topic->read($nid)));
                    }
                    else {
                        //@todo 数据有效性检验
                        $this->clubs_topic->edit($nid, $this->input->post('title', TRUE), $this->input->post('content', TRUE));
                        redirect('club/' . $mid . '/topic/' . $nid);
                    }
                }
            }
            else {

            }
        }
        else {
            throw new MY_Exception('The club is not specified.');
        }
    }

    function _getEditComment($mid, $nid) {
        var_dump($this->clubs_topic->readByPk($nid));
    }
}

/* End of file club_topic.php */
/* Location: ./application/controllers/club/club_topic.php */