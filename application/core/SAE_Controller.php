<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*CORE Controller by StevenY.*/
class SAE_Controller extends CI_Controller {
	var $_output = array();
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Push function for loading view
	 */
	function push($view = NULL, $data = NULL, $redirect = FALSE)
	{		
		if ($this->input->is_ajax_request())
		{
				$this->load->library('json');
            	$this->output->set_content_type('application/json')
					 ->set_output($this->json->encode($data));
		}
		elseif ($redirect)
			redirect($view);
		else
		{
			if (isset($view))
			{
				if (isset($data))
					$this->load->view($view,$data);
				else
					$this->load->view($view);
			}
			else
				show_404();
		}
	}
	
}