<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Node
 * 
 * HFICampus的基础数据模型类
 * 
 * 
 * @package model
 * @author halfcoder
 * @copyright One Technology Team
 * @version $Id$
 * @access public
 */
class Node extends CI_Model {
    var $_options = array(
        'tableName' => '',
        'subContent' => array(
            'tableName' => ''
        )
    );

    /**
     * 配置函数
     * @param  array $options 自定义的配置
     * @return boolean 操作是否成功
     */
    public function config($options) {
        foreach ($options as $key => $value) {
            $this->_options[$key] = $value;
        }
        return TRUE;
    }

    public function get_list($filters = null, $columns = null, $order = null, $limit = null, $offset = null) {
        //设置where条件
        if(is_array($filters) || is_string($filters)) {
            $this->db->where($filters);
        }
        else {
            $this->errorhandler->setError(702, 'Wrong Input');
            return FALSE;
        }
        //设置select
        if(is_array($columns)) {
            $this->db->select(implode(',', $columns));
        }
        else if(is_string($columns)) {
            $this->db->select($columns);
        }
        else {
            $this->errorhandler->setError(702, 'Wrong Input');
            return FALSE;
        }
        //设置order
        if(is_string($order)) {
            $this->db->order_by($order);
        }
        
        return $this->db->get($this->_options['tableName'], $limit, $offset)->result_array();
    }

    public function create($data) {

    }

    public function update($data, $filters) {

    }

    public function read($filters, $columns = null) {

    }

    public function delete($filters) {

    }
}

/* End of file node.php */
/* Location: ./application/models/node.php */