<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Club
 *
 * HFICampus的Club控制器类
 *
 * @package controller
 * @author halfcoder
 * @copyright One Technology Team
 * @version $Id$
 */
class Club extends SAE_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('clubs');
		throw new MY_Exception('Club is coming! Please wait~',0,'home',10);
    }

    public function get($mid = NULL) {
        if($mid) {
            //显示指定mid的社团，可能需要读取社团首页的信息
            var_dump($this->clubs->readByPk($mid));
        }
        else {
            switch ($this->input->get('type', TRUE)) {
                case 'all':
                //显示全部的社团，可能需要分页
                    var_dump($this->clubs->get_list());
                    break;

                case 'mine':
                default:
                //默认动作，显示用户所属的社团
                    //
                    $this->load->model('group');
                    $sub = $this->group->get_sub();
                    break;
            }
        }
    }

    public function delete($mid = NULL) {
        if($mid) {
            if(!is_numeric($mid)) {
                throw new MY_Exception("Wrong Course Id");
            }
            if ($this->user->sec_chk()) {
                $result = $this->clubs->readByPk($mid, 'gid');
                $gid = $result[0]['gid'];
                if ($this->club->deleteByPk($mid)) {
                    $this->load->model('group');
                    $this->group->delete($gid);
                    
                    redirect('club');
                }
                else {
                    throw new MY_Exception('Delete Error');
                }
            }
            else {
                $this->session->set_userdata('sec_url','club/' . $this->input->get('cid') . '/delete');
                redirect('account/security');
            }
        }
        else {
            throw new MY_Exception('The club to be deleted is not specified.');
        }
    }

    public function create($mid = NULL) {
        if($mid) {
            if(!is_numeric($mid)) {
                throw new MY_Exception("Wrong Course Id");
            }
            $course_info = $this->clubs->readByPk($mid);
        }
        else {
            if($this->input->server('REQUEST_METHOD') === 'GET') {
                $this->push('club/create');
            }
            else {
                $this->_postCreate();
            }
        }
    }

    public function edit($mid = NULL) {
        if($mid) {
            if(!is_numeric($mid)) {
                throw new MY_Exception("Wrong Course Id");
            }
            if($this->input->server('REQUEST_METHOD') === 'GET') {
                $this->push('club/edit', array(
                    'data' => $this->clubs->readByPk($mid)
                ));
            }
            else {
                $this->_postEdit($mid);
            }
        }
        else {
            //输出club的整体管理列表
            $output = array(
                'clubs' => $this->clubs->get_list()
            );
            print_r($output);
        }
    }

    function _postCreate() {
        //@todo 数据有效性检验
        //取得数据
        $data = $this->input->post(NULL, TRUE);
        //建立group
        $this->load->model('group');
        $data['gid'] = $this->group->create('club_' . $data['name']);
        //建立club
        $newClubId = $this->clubs->create($data);
        redirect('club/' . $newClubId . '/');
    }

    function _postEdit($mid) {
        //@todo 数据有效性检验
        //取得数据
        $data = $this->input->post(NULL, TRUE);
        $this->clubs->updateByPk($data, $mid);
        redirect('club/' . $mid) ;
    }
}
/* End of file club.php */
/* Location: ./application/controllers/club/club.php */