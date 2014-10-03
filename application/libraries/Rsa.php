<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_include_path(get_include_path().PATH_SEPARATOR.rtrim(APPPATH,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'third_party'.DIRECTORY_SEPARATOR.'phpseclib'.DIRECTORY_SEPARATOR);
include_once('Crypt'.DIRECTORY_SEPARATOR.'RSA.php');

class Rsa extends Crypt_RSA
{	
	function set_key()
	{
		extract($this->createKey());
		$file=fopen("application/third_party/phpseclib/key/public_key.kem","w");
		fwrite($file,$publickey);
		fclose($file);
		
		$file=fopen("application/third_party/phpseclib/key/private_key.kem","w");
		fwrite($file,$privatekey);
		fclose($file);
	}
	
	function get_publickey()
	{
		$file=fopen("application/third_party/phpseclib/key/public_key.kem","r");
		$publickey=fread($file,filesize("application/third_party/phpseclib/key/public_key.kem"));
		fclose($file);	
		return $publickey;
	}
	
	function get_privatekey()
	{
		$file=fopen("application/third_party/phpseclib/key/private_key.kem","r");
		$privatekey=fread($file,filesize("application/third_party/phpseclib/key/private_key.kem"));
		fclose($file);	
		return $privatekey;
	}
	
	function get_modulus()
	{
		
		$file=fopen("application/third_party/phpseclib/key/modulus.kem","r");
		$modulus=fread($file,filesize("application/third_party/phpseclib/key/modulus.kem"));
		fclose($file);
		
		return $modulus;
	}
	
	function load_privatekey()
	{
		$this->loadKey($this->get_privatekey());
	}
	
	function load_publickey()
	{
		$this->loadKey($this->get_publickey());
	}
	
}