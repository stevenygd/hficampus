<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Netdisks
 * 
 * HFICampus的网盘模型类
 * 
 * 
 * @package model
 * @author halfcoder
 * @copyright One Technology Team
 * @version $Id$
 * @access public
 */
class Netdisks extends CI_Model {
    /**
     * Netdisks::__construct()
     * 
     * @return void
     */
    function __construct() {
        parent::__construct();
        //加载storage辅助函数
        $this->load->helper('storage');
    }
    
    /**
     * Netdisks::download($rawPath)
     *
     * 该函数是文件下载的处理函数
     *
     * @param string $rawPath
     * @return boolean
     */
    function download($rawPath) {
        //@todo 检查权限
		$segs=explode('/', $this->_convertPath($rawPath));
		
		if (! isset($segs[1])){
			throw new MY_Exception('No Permission to create download',403);
		}
			
		if (! $this->auth->set('c',$segs[0],$segs[1],'all',0,TRUE)){
			throw new MY_Exception('Something Wrong, please email:stevenygd@gmail.com',403);
		}
		
        if($this->auth->get_permission() !== TRUE) {
            throw new MY_Exception('No Permission to create download',403);
        }

		
        /*=== 以下代码已测试通过 by halfcoder on 2013-08-13 ===*/
        //转换路径
        $path = $this->_convertPath($rawPath);
        //检查请求路径是否为目录
        if($this->_isDir($path)) {
            throw new HTTPException(403);
        }
        //检查文件是否存在
        if(!s_file_exists($path)) {
            throw new HTTPException(404);
        }
        //输出文件地址
        return array(
            'url' => s_get_url($path),
            'attr' => s_get_attr($path)
        );
    }
    
    /**
     * Netdisks::upload($targetPath)
     *
     * 文件上传的处理函数
     * 
     * @param  string $targetPath 文件保存到的目标目录（尚未转换！）
     * @return boolean
     */
    function upload($targetPath) { 
        //转换路径
        $path = $this->_convertPath($targetPath);
        //判断是否为目录
        if(!$this->_isDir($path)) {
            throw new HTTPException(404);
        }
		
		//@todo 检查权限
		$segs=explode('/',$path);
		if (! isset($segs[1]))
		{
			throw new MY_Exception('No Permission to upload',403);
		}
			
		if (! $this->auth->set('c',$segs[0],$segs[1],'all',0,TRUE))
		{
			throw new MY_Exception('Something Wrong, please email:stevenygd@gmail.com',403);
		}
		
        if($this->auth->get_permission() !== TRUE) {
            throw new MY_Exception('No Permission to upload',403);
        }

        //加载上传库
		$this->load->library('upload', array(
            'upload_path' => $path,
            'allowed_types' => 'ppt|pptx|doc|docx|xls|xlsx|zip|rar',
            'max_size' => '512000',
            'encrypt_name' => FALSE,
        ));
		if ($this->upload->do_upload('file')) {
		    /*上传成功，写入数据库
		    $data=array(
				'auth' => $this->uid,
				'gid' => end($this->db->get_where('aca',array('id'=>$cid))->result())->gid,
				'oname' => $return['upload_data']['orig_name'],
				'fname' => $return['upload_data']['raw_name'],
				'ftype' => $return['upload_data']["file_ext"],
				'fpath' => $cid.$dir
			);
			$this->db->insert('aca_file',$data);
            */
            
            return TRUE;
		}
		else {
		    //上传失败，返回HTTP500和失败信息
			throw new HTTPException(500, $this->upload->display_errors());
		}
    }
    
    /**
     * Netdisks::rename($currentPath, $oldObjectName, $newObjectName)
     *
     * 文件或者目录重命名的处理函数
     * 
     * @param  string $currentPath   文件管理页面当前所处的目录
     * @param  string $oldObjectName 操作对象的旧名字
     * @param  string $newObjectName 操作对象的新名字
     * @return boolean                
     */
    function rename($currentPath, $oldObjectName, $newObjectName) {
        //@todo 检查权限
		$segs=explode('/', $this->_convertPath($currentPath. '/' . $oldObjectName));
		
		if (! isset($segs[1])){
			throw new MY_Exception('No Permission to rename',403);
		}
			
		if (! $this->auth->set('u',$segs[0],$segs[1],'all',0,TRUE)){
			throw new MY_Exception('Something Wrong, please email:stevenygd@gmail.com',403);
		}
		
        if($this->auth->get_permission() !== TRUE) {
            throw new MY_Exception('No Permission to rename',403);
        }
        
        /*=== 以下代码已测试通过 by halfcoder on 2013-06-20 ===*/
        $oldPath = $this->_convertPath($currentPath . '/' . $oldObjectName);
        $newPath = $this->_convertPath($currentPath . '/' . $newObjectName);
        if(rename($oldPath, $newPath)) {
            return TRUE;
        }
        else {
            $this->errorhandler->setError(500);
            return FALSE;
        }
    }

    /**
     * Netdisks::delete($currentPath, $objectName)
     *
     * 文件或目录删除的处理函数
     * 
     * @param  string $currentPath 文件管理页面当前所处的目录
     * @param  string $objectName  操作对象的名字
     * @return boolean
     */
    function delete($currentPath, $objectName) {
        $path = $this->_convertPath($currentPath . '/' . $objectName);
		
        //判断对象的类型和是否存在
        if($this->_isDir($path)) {
            //对象为目录
            //检查权限
			$segs=explode('/', $path);
			
			if (! isset($segs[1]))
			{
				throw new MY_Exception('No Permission to delete'.var_dump($segs),403);
			}
				
			if (! $this->auth->set('d',$segs[0],$segs[1],'all',0,TRUE))
			{
				throw new MY_Exception('Something Wrong, please email:stevenygd@gmail.com',403);
			}
			
			if($this->auth->get_permission() !== TRUE) {
				throw new MY_Exception('No Permission to delete',403);
			}
			
            return $this->netdisks->_recursiveDeleteDirectory($path);
        }
        elseif(s_file_exists($path)) {
            //对象为文件
            if(s_delete($path)) {
                return TRUE;
            }
            else {
                throw new HTTPException(500);
            }
        }
        else {
            //确定对象确实不存在
            throw new HTTPException(404);
        }
    }
    
    /**
     * Netdisks::createDirectory($currentPath, $objectName)
     *
     * 目录创建的处理函数
     * 
     * @param  string $currentPath 文件管理页面当前所处的目录
     * @param  string $objectName  操作对象的名字
     * @return boolean              
     */
    function createDirectory($currentPath, $objectName){
		//权限验证 @todo
		$segs=explode('/', $this->_convertPath($currentPath. '/' . $objectName));
		
		if (! isset($segs[1])){
			throw new MY_Exception('No Permission to create directory.'.var_dump($segs),403);
		}
			
		if (! $this->auth->set('c',$segs[0],$segs[1],'all',0,TRUE)){
			throw new MY_Exception('Something Wrong, please email:stevenygd@gmail.com',403);
		}
		
        if($this->auth->get_permission() !== TRUE) {
            throw new MY_Exception('No Permission to create directory',403);
        }
		
        /*=== 以下代码已测试通过 by halfcoder on 2013-08-13 ===*/
        //转换路径
        $path = $this->_convertPath($currentPath . '/' . $objectName . '/dir');
        if(s_file_exists($path)) {
            throw new HTTPException(403);
        }
        if(s_write($path, 'This is created by system!')) {
            return TRUE;
        }
        else {
            throw new HTTPException(500);
        }
    }
    
    /**
     * Netdisks::listFiles($currentPath)
     *
     * 目录内容输出的处理函数
     * 
     * @param  string $currentPath 文件管理页面当前所处的目录
     * @return mixed              
     */
    function listFiles($currentPath){
		//权限验证 @todo
		$segs=explode('/', $this->_convertPath($currentPath));
		
		if (! isset($segs[1])){
			throw new MY_Exception('No Permission to list files in this directory',403);
		}
			
		if (! $this->auth->set('l',$segs[0],$segs[1],'all',0,TRUE)){
			throw new MY_Exception('Something Wrong, please email:stevenygd@gmail.com',403);
		}
		
        if($this->auth->get_permission() !== TRUE) {
            throw new MY_Exception('No Permission to list files in this directory',403);
        }
		
        //处理参数
        return $this->_listFiles($this->_convertPath($currentPath));
    }

    /**
     * Netdisks::_listFiles($path)
     *
     * 私有的，实际作用的，目录内容输出的处理函数
     * 单独列出该部分是为了避免类中的其它方法调用时重复处理路径
     *
     * @access private
     * @param  string $path 文件管理页面当前所处目录的绝对路径
     * @return mixed
     */
    function _listFiles($path) {
        if(!$this->_isDir($path)) {
            throw new HTTPException(404);
        }
        if($result = s_get_dir_file_info($path)) {
            return $result;
        }
        else {
            throw new HTTPException(500);
        }
    }
    
    /**
     * Netdisks::_recursiveDeleteDirectory($path)
     *
     * 私有的递归的目录删除的处理函数
     * 由于PHP的标准rmdir函数不支持删除非空目录，所以递归删除目录下的文件和子目录
     *
     * @access private
     * @param  string $path 所要删除目录的绝对路径
     * @return boolean       
     */
    function _recursiveDeleteDirectory($path) {
        //获取当前目录下的文件和子目录
        $fileInfo = s_get_dir_file_info($path);
        if($fileInfo['dirNum'] !== 0) {
            foreach ($fileInfo['dirs'] as $dir) {
                $this->_recursiveDeleteDirectory($path . '/' . $dir['name']);
            }
        }
        if($fileInfo['fileNum'] !== 0) {
            foreach ($fileInfo['files'] as $file) {
                if(!s_delete($path . '/' . $file['Name'])) {
                    throw new HTTPException(500);
                }
            }
        }
    }

    /**
     * 私有的路径转换的处理函数
     *
     * @access private
     * @param  string $rawPath 输入的路径
     * @return string          转换后的绝对路径
     */
    function _convertPath($rawPath) {
        //去除两边的'/'
        return trim($rawPath, '/');
    }

    /**
     * 私有的判断是否为目录的函数
     * 
     * @access private
     * @param  string  $path 输入的路径
     * @return boolean       是目录返回TRUE，否则返回FALSE
     */
    function _isDir($path) {
        return s_file_exists($path . '/dir');
    }

    /** 
     * 获取自动转换单位的文件大小值（四舍五入） 
     * 
     * @param float $byte 
     * @param integer $precision 精度 
     * @return string 
     */  
    function _getAutoSize($byte, $precision = 2) {  
        $kb = 1024;  
        $mb = $kb * 1024;  
        $gb = $mb * 1024;  
        $tb = $gb * 1024;  
        if ($byte < $kb) {  
            return $byte . ' B';  
        } elseif ($byte < $mb) {  
            return round($byte / $kb, $precision) . ' KB';  
        } elseif ($byte < $gb) {  
            return round($byte / $mb, $precision) . ' MB';  
        } elseif ($byte < $tb) {  
            return round($byte / $gb, $precision) . ' GB';  
        } else {  
            return round($byte / $tb, $precision) . ' TB';  
        }  
    }  
}

/* End of file netdisks.php */
/* Location: ./application/models/netdisks.php */
