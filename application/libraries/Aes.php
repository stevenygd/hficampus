<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_include_path(get_include_path().PATH_SEPARATOR.rtrim(APPPATH,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'third_party'.DIRECTORY_SEPARATOR.'phpseclib'.DIRECTORY_SEPARATOR);
include_once('Crypt'.DIRECTORY_SEPARATOR.'AES.php');

class Aes extends Crypt_AES
{
	
}