<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
<<<<<<< HEAD
 * SAE_Model after MY_Model by halfcoder
 * 
 * 增强版Active Record，参考https://github.com/jamierumbelow/codeigniter-base-model
=======
 * MY_Model
 * 
 * 增强版Active Record
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
 * 初期目标是实现Yii Framework中Active Record的功能
 * 参见：http://www.yiiframework.com/doc/guide/1.1/en/database.ar
 * 里程碑目标是实现Yii Framework中Relational Active Record的功能
 * 参见：http://www.yiiframework.com/doc/guide/1.1/en/database.arr
 * 同时参考SpeedPHP、CakePHP甚至Rails on Ruby的相似功能
 * 
 * @package core
 * @author halfcoder
<<<<<<< HEAD
 * @version 0.3.2
 * @access public
 * @edited by Steven 2014/3/18
 * @log: add construct function which is quite, somehow, unneccesary
 * @edited by Steven 2014/3/19
 * @log: add set error msg
 */
class SAE_Model extends CI_Model {
    var $tableName;

    var $primaryKey = 'id';

    var $subContent = array(
            'tableName' => '',
            'primaryKey' => 'id',
            'associatedKey' => ''
        );
	
	public $err_code = 0;
	public $err_msg = '';
	
	function __construct(){
		parent::__construct();
	}
=======
 * @version 0.2
 * @access public
 */
class SAE_Model extends CI_Model {
    var $_options = array(
        'tableName' => '',
        'primaryKey' => 'id',
        'subContent' => array(
            'tableName' => '',
            'primaryKey' => 'id',
            'associatedKey' => ''
        )
    );
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9

    //Methods of mainContent
    /**
     * 取得主内容的列表
     * @param  mixed $filters 过滤器，用于SQL语句中的where部分
     * @param  mixed $columns 需要输出的列，用于SQL的select语句的主体部分
     * @param  string $order   排序方式，用于SQL语句中的orderby部分
     * @param  integer $limit   记录输出总数，表示总共输出几条记录，用于SQL语句中的limit部分
     * @param  integer $offset  起始记录数，表示从数据库中的第几条记录开始输出，用于SQL语句中的limit部分
     * @return mixed          成功时以关联数组形式返回数据集，失败时返回FALSE
     */
<<<<<<< HEAD
    public function find($filters = null, $columns = null, $order = null, $limit = null, $offset = null) {
        return $this->_find($this->tableName, $filters, $columns, $order, $limit, $offset);
=======
    public function get_list($filters = null, $columns = null, $order = null, $limit = null, $offset = null) {
        return $this->_find($this->_options['tableName'], $filters, $columns, $order, $limit, $offset);
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
    }

    /**
     * 添加一条数据记录
     * @param  array $data 要添加的数据记录的内容
     * @return mixed       成功时返回新数据记录的序号，失败时返回FALSE
     */
    public function create($data) {
<<<<<<< HEAD
        return $this->_create($this->tableName, $data);
    }

    public function update($data, $filters) {
        return $this->_update($this->tableName, $data, $filters);
    }

    public function updateByPk($data, $primaryKeyValue) {
        return $this->_update($this->tableName, $data, array(
            ($this->primaryKey) => ($primaryKeyValue)
=======
        return $this->_create($this->_options['tableName'], $data);
    }

    public function update($data, $filters) {
        return $this->_update($this->_options['tableName'], $data, $filters);
    }

    public function updateByPk($data, $primaryKeyValue) {
        return $this->_update($this->_options['tableName'], $data, array(
            ($this->_options['primaryKey']) => ($primaryKeyValue)
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
        ));
    }

    /**
     * 取得一条数据记录的内容
     * @param  mixed $filters 过滤器，用于SQL语句的where部分
     * @param  mixed $columns 需要输出的列，用于SQL的select语句的主体部分
     * @return mixed          成功时以数组形式返回数据，失败时返回FALSE
     */
    public function read($filters, $columns = null) {
<<<<<<< HEAD
        return $this->_read($this->tableName, $filters, $columns);
=======
        return $this->_read($this->_options['tableName'], $filters, $columns);
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
    }

    /**
     * 根据数据表中主键的值来取得一条数据记录的内容。
     * 这是一块语法糖
     * @param  mixed $primaryKeyValue 要查询的主键的值
     * @return  mixed                                   成功时以数组形式返回数据，失败时返回FALSE。参见read()方法。
     */
    public function readByPk($primaryKeyValue, $columns = null) {
<<<<<<< HEAD
        return $this->_read($this->tableName, array(
            ($this->primaryKey) => ($primaryKeyValue)
=======
        return $this->_read($this->_options['tableName'], array(
            ($this->_options['primaryKey']) => ($primaryKeyValue)
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
        ), $columns);
    }

    public function delete($filters) {
<<<<<<< HEAD
        return $this->_delete($this->tableName, $filters);
    }

    public function deleteByPk($primaryKeyValue) {
        return $this->_delete($this->tableName, array(
            ($this->primaryKey) => ($primaryKeyValue)
        ));
    }

    public function count($filters) {
        return $this->_count($this->tableName, $filters);
    }

=======
        return $this->_delete($this->_options['tableName'], $filters);
    }

    public function deleteByPk($primaryKeyValue) {
        return $this->_delete($this->_options['tableName'], array(
            ($this->_options['primaryKey']) => ($primaryKeyValue)
        ));
    }

>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
    //Methods of subContent
    /**
     * 取得附属内容列表
     * @param  array $filter 筛选条件
     * @return array     附属内容的数据集
     */
<<<<<<< HEAD
    public function findSubContent($filters) {
        return $this->_find($this->subContent['tableName'], $filters);
=======
    public function get_subContent_list($filters) {
        return $this->_find($this->_options['subContent']['tableName'], $filters);
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
    }
	
    /**
     * 添加一条附属内容
     * @param  array $data 要添加的数据记录的内容
     * @return mixed       成功时返回新数据记录的序号，失败时返回FALSE
     */
<<<<<<< HEAD
    public function createSubContent($data) {
        return $this->_create($this->subContent['tableName'], $data);
=======
    public function add_subContent($data) {
        return $this->_create($this->_options['subContent']['tableName'], $data);
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
    }
	
    /**
     * 删除附属信息
     * @param  array $filter 筛选条件
     * @return boolean       成功时TRUE，失败时返回FALSE
     */
<<<<<<< HEAD
    public function deleteSubContent($filters) {
        return $this->_delete($this->subContent['tableName'], $filters);
=======
    public function del_subContent($filters) {
        return $this->_delete($this->_options['subContent']['tableName'], $filters);
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
    }

    /// 基础CURD方法
    /**
     * 取得若干条数据记录
     * @access protected
     * @param  string $tableName 要操作的数据表名称
     * @param  mixed $filters 过滤器，用于SQL语句中的where部分
     * @param  mixed $columns 需要输出的列，用于SQL的select语句的主体部分
     * @param  string $order   排序方式，用于SQL语句中的orderby部分
     * @param  integer $limit   记录输出总数，表示总共输出几条记录，用于SQL语句中的limit部分
     * @param  integer $offset  起始记录数，表示从数据库中的第几条记录开始输出，用于SQL语句中的limit部分
     * @return  array          成功时以关联数组形式返回数据集，失败时抛出异常
     */
    protected function _find($tableName, $filters = null, $columns = null, $order = null, $limit = null, $offset = null) {
        //设置where条件
        if($filters) {
            if(is_array($filters) || is_string($filters)) {
                $this->db->where($filters);
            }
            else {
                throw new InvalidArgumentException('Invalid Argument: $filters');
            }
        }
        //设置select
        if($columns) {
            if(is_array($columns)) {
                $this->db->select(implode(',', $columns));
            }
            else if(is_string($columns)) {
                $this->db->select($columns);
            }
            else {
                throw new InvalidArgumentException('Invalid Argument: $columns');
            }
        }
        //设置order
        if($order) {
            if(is_string($order)) {
                $this->db->order_by($order);
            }
            else {
                throw new InvalidArgumentException('Invalid Argument: $order');
            }
        }
        return $this->db->get($tableName, $limit, $offset)->result_array();
    }

    /**
     * 添加一条数据记录
     * @access protected
     * @param  string  $tableName 要操作的数据表的名称
     * @param  array $data 要添加的数据记录的内容
     * @return integer       成功时返回新数据记录的序号，失败时抛出异常
     */
    protected function _create($tableName, $data) {
        if(is_array($data)) {
            if($this->db->insert($tableName, $data)) {
                return $this->db->insert_id();
            }
            else {
<<<<<<< HEAD
                throw new Exception('Database Error');
=======
                throw new MY_Exception('Database Error');
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
            }
        }
        else {
            throw new InvalidArgumentException('Invalid Argument: $data');
        }
    }

    /**
     * 更新一条甚至若干条数据记录
     * @access protected
     * @param  string  $tableName 要操作的数据表的名称
     * @param  array $data    要更新的数据记录的内容
     * @param  mixed $filters 过滤器，用于SQL语句的where部分
     * @return  boolean           成功时返回true，失败时抛出异常
     */
    protected function _update($tableName, $data, $filters) {
        //设置where条件
        if(is_array($filters) || is_string($filters)) {
            $this->db->where($filters);
        }
        else {
            throw new InvalidArgumentException('Invalid Argument: $filters');
        }
        if(is_array($data)) {
            if($this->db->update($tableName, $data)) {
                return TRUE;
            }
            else {
<<<<<<< HEAD
                throw new Exception('Database Error');
=======
                throw new MY_Exception('Database Error');
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
            }
        }
        else {
            throw new InvalidArgumentException('Invalid Argument: $data');
        }
    }

    /**
     * 取得一条数据记录
     * @access protected
     * @param  string  $tableName 要读取的数据表的名称
     * @param  mixed $filters 过滤器，用于SQL语句的where部分
     * @param  mixed $columns 需要输出的列，用于SQL的select语句的主体部分
     * @return  array          成功时以数组形式返回数据，失败时抛出异常
     */
    protected function _read($tableName, $filters, $columns = null) {
        //设置where条件
        if(is_array($filters) || is_string($filters)) {
            $this->db->where($filters);
        }
        else {
            throw new InvalidArgumentException('Invalid Argument: $filters');
        }
        //设置select
        if($columns) {
            if(is_array($columns)) {
                $this->db->select(implode(',', $columns));
            }
            else if(is_string($columns)) {
                $this->db->select($columns);
            }
            else {
                throw new InvalidArgumentException('Invalid Argument: $columns');
            }
        }
        //返回结果
        return $this->db->get($tableName)->row_array();
    }

    /**
     * 删除一条甚至若干条记录
     * @access protected
     * @param  string $tableName 要操作的数据表名称
     * @param  mixed $filters   过滤器，用于SQL语句的where部分
     * @return  boolean            成功时返回TRUE，失败时抛出异常
     */
    protected function _delete($tableName, $filters) {
        if(is_array($filters) || is_string($filters)) {
            $this->db->where($filters);
        }
        else {
            throw new InvalidArgumentException('Invalid Argument: $filters');
        }
        return $this->db->delete($tableName);
    }

<<<<<<< HEAD
    protected function _count($tableName, $filters) {
        if($filters) {
            if(is_array($filters) || is_string($filters)) {
                $this->db->where($filters);
            }
            else {
                throw new InvalidArgumentException('Invalid Argument: $filters');
            }
        }
        return $this->db->count_all_results($tableName);
    }
	
	protected function set_err($code = 0, $msg = ''){
		if ($code != 0){
			$this->err_code = $code;
			$this->err_msg = $msg;
			return FALSE;
		}else{
			return TRUE;
		}
	}
=======
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */