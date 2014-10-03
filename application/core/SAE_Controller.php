<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CORE Controller by StevenY.
 * @version 2.0
 */
class SAE_Controller extends CI_Controller {
	
	protected $data = array();
	protected $channelData = array();
	
	//variables that differ from user to user
	protected $uid;
	protected $channelURL = '';
	
	//variable that depends on the request
	protected $code = '0';
	protected $message = '';
	
	protected $redirect = NULL;
	protected $view = NULL;
	protected $title = NULL;
	protected $partials = NULL;
	
	//variables that can be guessed
	protected $layout = NULL;
	protected $mtype = NULL;
	protected $ntype = NULL;
	
	private $_data_user  = array('uid','channelURL');
	private $_data_output = array('code','message','redirect','partials','title','layout');
	private $_data_guessable = array('uid','mtype','ntype');

	protected $kv = NULL;
	
	function __construct()
	{
		parent::__construct();
		
		//initialize
		$this->uid = $this->user->id;//@todo
		
		$this->load->config('views');
		$defaults = $this->config->item('default_settings');
		$data_structure = array_merge($this->_data_guessable,$this->_data_output,$this->_data_user);
		foreach ($data_structure as $item){
			if (array_key_exists($item,$defaults)){
				$this->$item = $defaults[$item];
			}
		}
		
	}
	
	protected function push($view = NULL, $data = NULL) 
	{
		//for temparary reason
		if (!empty($view)){
			$this->view = $view;
		}
		if (!empty($data)){
			$this->data = array_merge($this->data,$data);
		}
		
		//构建输出数据
		$this->_create_output();
				
		//formal function
		if ($this->input->is_ajax_request()) {
                        
			//输出数据
            $this->output->set_content_type('application/json')->set_output(json_encode($this->data));
		}
		else {
			if(! empty($this->redirect)) {
				redirect(site_url($this->redirect));
			}else{
				$this->render();
			}
		}
	}

	//send message to channel@todo
	protected function channelPush($uids, $content = array())
	{
		//generate additional data for 
		$url = $this->uri->uri_string();
		$method = $this->uri->segment($this->uri->total_segments());// get the last segment of URI
		if (! in_array($method, array('get','delete','edit','create')) ){
			$method = 'get';
		}
		
		//generate Channel data
		$this->_create_output();
		if (is_array($content))
			$this->channelData = array_merge($this->channelData , $content ,array('method' => $method, 'url' => $url));
		else
			$this->channelData = array_merge($this->channelData , array('method' => $method, 'url' => $url));
			
		$this->channelData['from']  = $this->data['uid'];
		$this->channelData['mtype'] = $this->data['mtype'];
		$this->channelData['ntype'] = $this->data['ntype'];
				
		//send to Channel
		/**
		 * $uids 数据结构：
		 * array([0]=>array('uid' => $uid1),[1]=>array('uid2' => $uid2),...);
		 */
		foreach ($uids as $item){
			if (isset($item['uid'])){
				$channelId = CHANNEL_PREFIX . $item['uid'];
				
				//check whether they are online:
				if ($this->channel->isUserOnline($item['uid'])){
					$this->channelData['uid'] = $item['uid'];
					$this->channel->sendMessage(json_encode($this->channelData),$channelId);
				}
			}
		}
	}

	//@todo
    public function pushException($e) {
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'code' => $e->getCode(),
            'message' => $e->getMessage()
        )));
    }

    /**
     * Automatically load the view, allowing the developer to override if
     * he or she wishes, otherwise being conventional.
     */
    public function render($view = '',$layout = NULL)
    {
		if (! empty ($view)){
			// If $tview isn't empty, load it. 
			$this->view = $view;
		}else if (empty($this->view))
			//try to guess view
			if (! empty($this->mtype)){
				//
				if (! empty($this->ntype)){
					$this->view = $this->mtype . '/' . $this->ntype;
				}else{
					$this->view = $this->mtype . '/index';
				}
			}else{
				//If it isn't, try and guess based on the controller and action name
				$this->view = $this->router->class . '/' . $this->router->method;
			}

        // Load the view into $yield
        $data['yield'] = $this->load->view($this->view, $this->data, TRUE);

        // Do we have any asides? Load them.
        if (! empty($this->partials))
        {
            foreach ($this->partials as $name => $file)
            {
                $data['partial_' . $name] = $this->load->view($file, $this->data, TRUE);
            }
        }

		//fetch the value of a layout
        if(! empty($layout)) {
            $this->layout = $layout;
        }

		if (empty($this->layout)){
            // If we didn't specify the layout, try to guess it
			if (! empty($this->mtype)){
				if (! empty($this->ntype)){
					if (file_exists(APPPATH . 'views/layouts/' .  $this->mtype . '_' . $this->ntype . '.php')){
						$this->layout = $this->mtype . '_' . $this->ntype;
					}else{
						//get config
						$mtype_config = $this->config->item($this->mtype);
						if ($mtype_config !== FALSE){
							$this->layout = $mtype_config['default_layout'];
						}
					}
				}else{
					if (file_exists(APPPATH . 'views/layouts/' .  $this->mtype . '.php')){
						$this->layout = $this->mtype;
					}else{
						//get config
						$mtype_config = $this->config->item($this->mtype);
						if ($mtype_config !== FALSE){
							$this->layout = $mtype_config['default_layout'];
						}
					}
				}
			}elseif (file_exists(APPPATH . 'views/layouts/' . $this->router->class . '.php')){
				$this->layout = $this->router->class;
			}else{
				// fail to guess a layout, just output it
            	$this->output->set_output($data['yield']);
				return;
			}
		}elseif ($this->layout === FALSE){
        	// If $layout is FALSE, we're not interested in loading a layout, so output the view directly
            $this->output->set_output($data['yield']);
			return;
		}
		
       	$this->load->view('layouts/' . $this->layout, array_merge($this->data, $data));
    }	
	
	private function _create_output()
	{		
		//构建输出数据
		if (empty($this->uid)){
			$this->uid = $this->user->id;
		}
		
		if (empty($this->channelURL))
		{
			if (isset($this->channel))
				$this->channelURL=$this->channel->getChannelURL();	
		}
		$data_structure = array_merge($this->_data_user,$this->_data_output,$this->_data_guessable);
		foreach ($data_structure as $item){
			if (empty($this->data[$item])){
				$this->data[$item] = $this->$item;
			}
		}
 	}
	
}