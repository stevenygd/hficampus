<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_include_path(get_include_path().PATH_SEPARATOR.rtrim(APPPATH,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'third_party'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR);

Class Json
{
	function encode($a)
	{
		if (function_exists('json_encode'))
			$str=json_encode($a);
		else
		{
			include_once('json.php');
			$json=new Services_JSON();
			$str=$json->encode($a);
		}
		return $str;
	}
	
	function decode($a)
	{
		if (function_exists('json_decode'))
			$str=json_decode($a);
		else
		{
			include_once('json.php');
			$json=new Services_JSON();
			$str=$json->decode($a);
		}
		return $str;
	}
}