<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ErrorHandler
 * 
 * 错误处理类，提供统一的错误处理方式。希望各controller和model的作者删除重复代码
 * 转为使用该类进行错误处理
 * 兼容PHP更高级的异常处理机制，本来应该是这个东西干这事的好吧～
 * 
 * @package library
 * @author halfcoder
 * @copyright One Technology Team
 * @version $Id$
 * @access public
 */
class ErrorHandler {
    var $_CI;
    var $_results;
    
    var $_httpStates = array(
        //格式：[HTTP State Code : Integer] => "HTTP State Message"
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',
        205 => 'Reset Content',
        
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        418 => 'I\'m a teapot',//just a joke~search wikipedia for more information
        
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        
    );
    
    public function __construct() {
        $this->_CI = &get_instance();
        $this->_results = array();
        return;
    }
    
    /**
     * ErrorHandler::setError()
     * 
     * 设置错误号和信息
     * 
     * @param mixed $code
     * @param mixed $message
     * @return void
     */
    public function setError($code, $message) {
        $result = array();
        if($code instanceof Exception) {
            $result['code'] = $code->getCode();
            if($message === null) {
                $result['message'] = $code->getMessage();
            }
            else {
                $result['message'] = $message;
            }
            $result['exception'] = $code;
        } 
        else if(isset($this->_httpStates[$code])) {
            $result['code'] = $code;
            if($message === null) {
                $result['message'] = $this->_httpStates[$code];
            }
            else {
                $result['message'] = $message;
            }
        }
        else {
            $result['code'] = $code;
            $result['message'] = $message;
        }
        array_push($this->_results, $result);
        return TRUE;
    }
    
    public function getErrorCode($resultId) {
        return $this->_results[$resultId]['code'];
    }
    
    public function getErrorMessage($resultId) {
        return $this->_results[$resultId]['message'];
    }
    
    public function getException($resultId) {
        return $this->_results[$resultId]['exception'];
    }
    
    /**
     * ErrorHandler::showError()
     * 
     * 使用CodeIgniter的错误显示函数show_error()将错误显示为html页面
     * 参考：http://codeigniter.org.cn/user_guide/general/errors.html
     * 
     * @return void
     */
    public function showError($resultId) {
        show_error($this->_results[$resultId]['message'], $this->_results[$resultId]['code']);
        return TRUE;
    }
    
    /* 以下函数为语法糖，用于简便的取得最后一个错误的信息 */
    function popupErrorCode() {
        return $this->getErrorCode(count($this->_results) - 1);
    }
    
    function popupErrorMessage() {
        return $this->getErrorMessage(count($this->_results) - 1);
    }
    
    function popupException() {
        return $this->getException(count($this->_results) - 1);
    }
    
    function popupError() {
        return $this->showError(count($this->_results) - 1);
    }
}