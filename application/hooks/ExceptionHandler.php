<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 这个是自定义的异常处理文件，除了使用下面的异常类，更加推荐使用PHP自带的异常类
 * 参见http://www.php.net/manual/zh/spl.exceptions.php
 */

/**
 * 自定义的异常类
 * 增加了跳转地址、跳转延时、错误标题、输错HTTP状态码等设置。
 */
class MY_Exception extends Exception {
    //输出时的延时跳转地址，默认为空
    protected $redirectUri;
    //输出时的跳转延时，单位为s，默认为空
    protected $redirectTime;
    //输出时的标题，默认为空
    protected $heading;
    //输出时的HTTP状态码，默认为500
    protected $statusCode = 500;

    /**
     * 异常类的构造函数
     * @param string  $message      异常信息，不能为空
     * @param integer $code         异常代码，可以为空，注意与后面的statusCode不同
     * @param string  $redirectUri  输出异常页面后要跳转到的地址，可以为空，是应用的内部地址
     * @param integer $redirectTime 跳转延时，可以为空，如果为0则直接跳转，否则输出异常页面后延时跳转
     * @param string  $heading      异常页面的标题
     * @param integer $statusCode   输出异常页面时的HTTP状态码，默认为500
     */
    public function __construct($message, $code = 0, $redirectUri = null, $redirectTime = null, 
        $heading = null, $statusCode = 500) {
        parent::__construct($message, $code);
        $this->redirectUri = $redirectUri;
        $this->redirectTime = $redirectTime;
        $this->heading = $heading;
        $this->statusCode = $statusCode;
    }

    public function getRedirectUri() {
        return $this->redirectUri;
    }

    public function getRedirectTime() {
        return $this->redirectTime;
    }

    public function getHeading() {
        return $this->heading;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }
}

class HTTPException extends Exception {
    static $_httpStatus = array(
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

    public function __construct($httpStatusCode, $customMessage = null) {
        if(is_integer($httpStatusCode)) {
            if($customMessage) {
                parent::__construct($customMessage, $httpStatusCode);
            }
            else if(isset(self::$_httpStatus[$httpStatusCode])) {
                parent::__construct(self::$_httpStatus[$httpStatusCode], $httpStatusCode);
            }
            else {
                parent::__construct('HTTP Error ' . $httpStatusCode, $httpStatusCode);
            }
        }
        else {
            throw new MY_Exception("Wrong Parameter");
            
        }
    }
}

/**
 * 异常输出函数，可以在全局内调用
 * @param  string  $message      要输出的信息，不能为空，例如$e->getMessage()
 * @param  string  $redirectUri  要跳转到的地址，可以为空，是应用的内部地址，会自动被site_url()函数转换
 * @param  integer $redirectTime 跳转延时，可以为空，如为0则直接跳转，否则输出异常页面后延时跳转
 * @param  string  $heading      要输出的异常页面的标题，可以为空
 * @param  integer $statusCode   输出异常页面时的HTTP状态码，默认为500
 * @param  string  $template     输出异常页面时使用的模板文件名，放在application/views下，默认为exception
 * @return void                
 */
function showException($message, $redirectUri = null, $redirectTime = null, $heading = 'An Exception Was Encountered', $statusCode = 500, $template = 'exception') {
    if($redirectUri !== null && $redirectUri !== '' && $redirectTime === 0) {
        redirect($redirectUri);
    }
    else {
        set_status_header($statusCode);

        $message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';
        //判断是否有跳转页面的需要，并设置标记变量
        if($redirectUri !== null && $redirectUri !== '' && $redirectTime !== 0) {
            $isRedirect = TRUE;
            $redirectUrl = site_url($redirectUri);
        }
        else {
            $isRedirect = FALSE;
        }

        /*送出最顶层缓冲区的内容（如果里边有内容的话），并关闭缓冲区。参见http://www.php.net/manual/zh/function.ob-end-flush.php
        ob_end_flush();*/
        //重新启动缓冲区
        ob_start();
        //载入输出模板文件并隐式执行其内容
        include(APPPATH.'views/'.$template.'.php');
        //取得缓冲区内容
        $buffer = ob_get_contents();
        //丢弃最顶层输出缓冲区的内容并关闭这个缓冲区。参见http://www.php.net/manual/zh/function.ob-end-clean.php
        ob_end_clean();
        //输出原缓冲区内容
        echo $buffer;
    }
}

/**
 * 实际的异常处理函数
 * @param  Exception $e 异常对象
 * @return void    
 */
function exceptionHandler($e) {
    if($e instanceof MY_Exception) {
        showException($e->getMessage(), $e->getRedirectUri(), $e->getRedirectTime(), $e->getHeading(), $e->getStatusCode());
    }
    else if($e instanceof HTTPException) {
        showException($e->getMessage(), null, null, 'HTTP Error', $e->getCode());
    }
    else {
        showException($e->getMessage());
    }
}

/**
 * 设置异常处理函数的函数，由CodeIgniter的Hook调用
 */
function setExceptionHandler() {
    set_exception_handler('exceptionHandler');
}

/* End of file ExceptionHandler.php */
/* Location: ./application/hooks/ExceptionHandler.php */