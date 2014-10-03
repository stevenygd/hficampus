<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Club_blog
 *
 * HFICampus的Club_blog控制器类
 *
 * @package controller
 * @author halfcoder
 * @copyright One Technology Team
 * @version $Id$
 */
class Club_blog extends SAE_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('clubs_blog');
		throw new MY_Exception('Club is coming! Please wait~',0,'home',10);
    }
    public function get($mid = NULL, $nid =NULL, $attach = '') {
         if($mid) {
            if(!is_numeric($mid)) {
                throw new MY_Exception("Wrong Course Id");
            }
            //检查输入的mid的club是否存在，顺便取得club数据
            $this->load->model('clubs');
            $this->_output['club'] = $this->clubs->readByPk($mid, 'name');
            if($nid) {
                if(!is_numeric($nid)) {
                    throw new MY_Exception("Wrong Blog Id");
                }
                $this->_output = array_merge($this->_output, $this->clubs_blog->read($nid));
                $this->push('club/blog_view', $this->_output);
            }
            else {
                var_dump($this->clubs_blog->get_list());
            }
        }
        else {
            throw new MY_Exception('The club is not specified.');
        }
    }

    public function delete($mid = NULL, $nid =NULL, $attach =NULL) {
        if($mid) {
            if(!is_numeric($mid)) {
                throw new MY_Exception("Wrong Course Id");
            }
            //检查输入的mid的club是否存在，顺便取得club数据
            $this->load->model('clubs');
            $this->_output['club'] = $this->clubs->readByPk($mid, 'name');
            if($nid) {
                if(!is_numeric($nid)) {
                    throw new MY_Exception("Wrong Blog Id");
                }
                if($attach) {

                }
                else {
                    $this->clubs_blog->delete($nid);
                    redirect('club/' . $mid . '/blog/edit');
                }
            }
            else {
                throw new MY_Exception('The blog article is not specified.');
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
                $this->push('club/blog_create', array(
                    'club' => $club,
                    'cid' => $mid
                ));
            }
            else {
                if($nid && $attach) {
                    if(!is_numeric($mid)) {
                        throw new MY_Exception("Wrong Course Id");
                    }
                    switch ($attach) {
                        case 'comment':
                            //@todo 数据有效性检查
                            if($this->clubs_blog->comment($nid, $this->input->post('content', TRUE))) {
                                redirect('club/' . $mid . '/blog/' . $nid);
                            }
                            else {
                                throw new MY_Exception('Commenting Failed.', 1, 'club/' . $mid . '/blog/' . $nid, 3);
                            }
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }
                else {
                    //@todo 数据有效性检验
                    $newBlogId = $this->clubs_blog->create($mid, $this->input->post('title', TRUE), $this->input->post('content', TRUE));
                    redirect('club/' . $mid . '/blog/' . $newBlogId);
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
                        $this->push('club/blog_edit', array_merge(array(
                            'club' => $club,
                            'cid' => $mid,
                            'bid' => $nid
                        ), $this->clubs_blog->read($nid)));
                    }
                    else {
                        //@todo 数据有效性检验
                        $this->clubs_blog->edit($nid, $this->input->post('title', TRUE), $this->input->post('content', TRUE));
                        redirect('club/' . $mid . '/blog/' . $nid);
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
        var_dump($this->clubs_blog->readByPk($nid));
    }
}

/* End of file club_blog.php */
/* Location: ./application/controllers/club/club_blog.php */