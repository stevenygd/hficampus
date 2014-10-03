<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Netdisk
 *
 * HFICampus的Netdisk控制器类
 * 
 * 
 * @package controller
 * @author halfcoder
 * @copyright One Technology Team
 * @version $Id$
 * @access public
 */
class Netdisk extends SAE_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Netdisk::index()
     *
     * 该函数是Netdisk的文件管理页面输出函数，被设计为接受形如
     * http://example.com/index.php/netdisk#/...的URL
     * 实际上，该函数只输出一个通用的页面，剩余的工作交由javascript调用api完成
     * 
     * @todo
     * @return void
     */
    public function index() {
        $this->load->view('netdisk/index');
    }

    /**
     * Netdisk::download()
     *
     * 该函数是文件下载的页面函数，被设计为接收形如
     * http://example.com/index.php/netdisk/download?file=...的URL
     * 并输出所要下载的文件。
     * 鉴于ajax无法很好的实现文件下载功能，无刷新下载文件多半采用建立隐藏iframe的方式
     * 故download不再放置在api中，改为独立形式
     *
     * @return void
     */
    public function download() {
        /*=== 以下代码已测试通过 by halfcoder on 2013-08-13 ===*/
        $this->load->model('netdisks');
        try {
            $file = $this->input->get('file', TRUE);
            $segments = explode('/', $file);
            $filename = end($segments);
            $result = $this->netdisks->download($file);
            // Try to determine if the filename includes a file extension.
            // We need it in order to set the MIME type
            if (FALSE === strpos($filename, '.'))
            {
                return FALSE;
            }

            // Grab the file extension
            $x = explode('.', $filename);
            $extension = end($x);

            
                $mime = 'application/octet-stream';
            

            // Generate the server headers
            if (strpos($this->input->server('HTTP_USER_AGENT'), "MSIE") !== FALSE)
            {
                header('Content-Type: "'.$mime.'"');
                header('Content-Disposition: attachment; filename="'.$filename.'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header("Content-Transfer-Encoding: binary");
                header('Pragma: public');
                header("Content-Length: ". $result['attr']['length']);
            }
            else
            {
                header('Content-Type: "'.$mime.'"');
                header('Content-Disposition: attachment; filename="'.$filename.'"');
                header("Content-Transfer-Encoding: binary");
                header('Expires: 0');
                header('Pragma: no-cache');
                header("Content-Length: ". $result['attr']['length']);
            }

            $this->output->set_header('Location: ' . $result['url']);
        }
        catch(HTTPException $e) {
            //载入第三方库
            $this->load->library('json');
            $this->output->set_status_header($e->getCode())
                ->set_content_type('application/json')
                ->set_output($this->json->encode(
                    array(
                        'code' => $e->getCode(),
                        'message' => $e->getMessage()
                    )
                ));
        }
    }

    /**
     * Netdisk::upload()
     *
     * 该函数是文件上传的处理函数，被设计为接收形如
     * http://example.com/index.php/netdisk/download?path=...的URL
     * 并调用模型类Netdisks::upload($targetPath)处理上传的文件
     * 鉴于普通的ajax无法很好的实现文件上传功能，无刷新上传多半采用建立隐藏iframe提交隐藏form的方式
     * 可参照：http://www.phpletter.com/Our-Projects/AjaxFileUpload/
     * 故upload不再放置在api中，改为独立形式
     * 
     * @return void 
     */
    public function upload() {
        $this->load->model('netdisks');
        //载入第三方库
        $this->load->library('json');
        try {
            $result = $this->netdisks->upload($this->input->post('path', TRUE));
            $this->output->set_status_header('200')->set_output('0');
        }
        catch(HTTPException $e) {
            $this->output->set_status_header($e->getCode())
                ->set_content_type('application/json')
                ->set_output($this->json->encode(
                    array(
                        'code' => $e->getCode(),
                        'message' => $e->getMessage()
                    )
                ));
        }
    }
    
    /**
     * Netdisk::api()
     *
     *  # jquery的ajax方法在用POST方法传递json格式的数据时存在一定问题，请前端的童鞋注意
     *  
     * 处理ajax请求，全部使用POST方法
     * 请求路径：http://example.com/index.php/netdisk/api/
     * 请求内容：（json格式，根名字为requests）
     *           {requests:[{
     *               "currentPath" : "/courses/14/test/" 
     *               "operation" : "create"
     *               "newDirectoryName" : "example"
     *           }]}
     *  # 使用数组是为了支持批量操作
     *  
     * @return void
     */
    public function api() {
        //载入netdisks模型
        $this->load->model('netdisks');
        //载入第三方库
        $this->load->library('json');
        //取得请求数据
        $rawRequests = $this->input->post('requests', TRUE);
        $requests = $this->json->decode($rawRequests);
        //判断是否为数组
        if (is_array($requests)) {
            $totalResult = TRUE;
            $results = array();
            try {
                foreach ($requests as $request) {
                    $result = $this->_operate($request);
                    array_push($results, $result);
                }
            }
            catch(Exception $e) {
                $totalResult = FALSE;
                array_push($results, array(
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ));
            }
            //判断总的处理结果并输出
            if($totalResult === TRUE) {
                $this->output->set_status_header('200');
            }
            else {
                $this->output->set_status_header('418'); //我找不到别的HTTP状态码来表达这种复杂的意思～
            }
            $this->set_content_type('application/json')->set_output($this->json->encode($results));
        }
        else {
            try {
                $result = $this->_operate($requests);
                $this->output->set_status_header('200');
                if($result !== TRUE) {
                    $this->output->set_content_type('application/json')->set_output($this->json->encode($result));
                }
            }
            catch(HTTPException $e) {
                $this->output->set_status_header($e->getCode())
                ->set_content_type('application/json')
                ->set_output($this->json->encode(
                    array(
                        'code' => $e->getCode(),
                        'message' => $e->getMessage()
                    )
                ));
            }
        }
        
    }

    /**
     * Netdisk::_operate($request)
     *
     * 私有的请求中操作符的处理函数
     *
     * @access private
     * @return mixed
     */
    function _operate($request) {
        //处理操作符
        switch($request->operation) {    
            case 'delete': 
                $result = $this->netdisks->delete($request->currentPath, $request->objectName);              
                break;
                
            case 'create':
                $result = $this->netdisks->createDirectory($request->currentPath, $request->objectName);
                break;
                
            case "list":
                $result = $this->netdisks->listFiles($request->currentPath);
                break;
                
            default:
                throw new HTTPException(501);
        }
        return $result;
    }
}