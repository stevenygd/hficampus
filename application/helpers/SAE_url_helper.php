<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 覆写CodeIgniter自带的base_url函数，实现在SAE环境上自动转换jquery和underscore的地址，节省流量
 * @param  string $uri 原始地址
 * @return string      转换后的地址
 */
function base_url($uri = '')
{
    $trimedUri = trim($uri, '/');
    if($trimedUri === 'js/jquery.js') {
        return 'http://lib.sinaapp.com/js/jquery/1.8/jquery.min.js';
    }
    else if($trimedUri === 'js/underscore.js') {
        return 'http://lib.sinaapp.com/js/underscore/1.4.4/underscore-min.js';
    }
    else {
        $CI =& get_instance();
        return $CI->config->base_url($uri);
    }
    
}

/* End of file SAE_url_helper.php */
/* Location: ./application/helpers/SAE_url_helper.php */