<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Channel{
	
	private $cn;
	private $channelURL = NULL;
	private $channelName = NULL;
	private $duration = NULL;
	private $_CI;
	public  $channelList = NULL;
	public  $data = array();

	/**
	 * Constructor
	 *    $config = array('channelName'=>$channelName,'duration'=>$duration);
	 * or $config = array('uid'=>$channelName,'duration'=>$duration);
	 */
	public function __construct($config = NULL){
		$this->cn = new SaeChannel();
		$this->_CI = & get_instance();
		
		if (isset($config)){
			if (isset($config['duration']))
				$this->duration = $config['duration'];
			else
				$this->duration = CHANNEL_DURATION;
			
			if (isset($config['name'])){			
				$this->channelName = $config['name'];
			}elseif(isset($config['uid'])){
				$this->channelName = CHANNEL_PREFIX . $config['uid'];
			}else{
				//just give a random one...
				$this->channelName = CHANNEL_PREFIX . sha1(time() . mt_rand(1, 65535));
			}
			
			$this->channelURL = $this->cn->createChannel($this->channelName,$this->duration);
		}
	}	
	
	/*Channel List Functions*/
	/**
	 * isUserOnline : test whether a certain UID is online
	 */
	public function isUserOnline($uid = NULL){
		if (! isset($uid)){
			$uid = $this->_CI->user->id;
		}		
		//refresh the list
		$this->channelList = $this->rChannelOnline();
		
		if (array_key_exists(CHANNEL_PREFIX . $uid,$this->channelList) 
			&& $this->channelList[CHANNEL_PREFIX . $uid] > time() - CHANNEL_DURATION){
			return TRUE;
		}else{
			//double check if it's deleted
			if (array_key_exists(CHANNEL_PREFIX . $uid,$this->channelList)){
				unset($this->channelList[CHANNEL_PREFIX . $uid]);
				$this->wChannelOnline($this->channelList);
			}
			return FALSE;
		}
	}
	
	/**
	 * rChannelOnline: read the entire channel Online List
	 */
	public function rChannelOnline(){
		$mmc = memcache_init();
		if($mmc==false){
		  return "mc init failed";
		}else{
		  $jsondata = memcache_get($mmc,"channelOnline");
		  $arrdata = json_decode($jsondata,true);
		  $this->channelList = $arrdata;
		  return $arrdata;
		}
	}	
	/**
	 * wChannelOnline:  write into the Channel Online List
	 */
	function wChannelOnline($arrdata=''){
		$mmc = memcache_init();
		if($mmc==false){
		  return "mc init failed";
		}else{
		  $jsondata = json_encode($arrdata);
		  memcache_set($mmc,"channelOnline",$jsondata);
		  return true;
		}
	}
	
	/*Original Channel Functions*/
	/**
	 * createChannel: renew Channel
	 * @param str $channelName  name of the channel
	 * @prarm int $duration     number of seconds of the new channel
	 */
	public function createChannel($channelName,$duration){
		$this->channelName = $channelName;
		$this->duration = $duration;
		$this->channelURL = $this->cn->createChannel($this->channelName,$this->duration);
		return $this->channelURL;
	}
	
	/**
	 * sendMessage: the original function of the sendMessage in Channel
	 * @param mix $message_content   message content, better jason
	 * @param str $channelName       Channel Name
	 */
	public function sendMessage($message_content, $channelName = NULL){
		//@todo check message content
		if (isset($channelName)){
			return $this->cn->sendMessage($channelName,$message_content);
		}else{
			if (isset($this->channelName)){
				return $this->cn->sendMessage($this->channelName,$message_content);
			}else{
				return FALSE;
			}
		}
	}
	
	/**
	 * getChannelURL: return current channel URL
	 */
	public function getChannelURL(){
		if (isset($this->channelURL)){
			return $this->channelURL;
		}else{
			return FALSE;
		}
	}
	
	/**
	 * getChannelName: return current channel Name
	 */
	public function getChannelName(){
		if (isset($this->channelName)){
			return $this->channelName;
		}else{
			return FALSE;
		}
	}
	
	/**
	 * getChannelDuration: return current channel Duration
	 */
	public function getChannelDuration(){
		if (isset($this->duration)){
			return $this->duration;
		}else{
			return FALSE;
		}
	}
	
	/**
	 * getChannelErr: return current channel Error Message and Error Code
	 */
	public function getChannelErr(){
		return 'Channel Error Code:' . $this->cn->errno() .'; Channel Error Message:' . $this->cn->errmsg();
	}
	
}