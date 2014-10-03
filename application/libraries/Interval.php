<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*********************
*Time Interval Class by Chris*
**********************/
/***********************
*Designed to represent a 
*temporary interval object
*until it is written into 
*database
*************************/
/*******************************************
*Hmm...Had lunch in Ducheng today. So spicy.
*By the way, Fridays and Saturdays are really
*great in that the animes I like just show up
*on these two days.
*Get to watch anime up in the morning. Hmm...interesting
*pondering about other people's ignorance
*******************************************/

class Interval{
	var $start;
	var $end;
	

	//Contructor
	function __construct()
	{
		//echo "The interval construct function is function is useless";
	}
	
	function set($s,$e)
	{
		$CI =& get_instance();
		
		$this->start = $s;
		$this->end = $e;
	}
	
	function write()
	{
		$CI =& get_instance();
		
		$CI->db->where('start',$this->start);
		$CI->db->where('end',$this->end);
		$query = $CI->db->get('interval');
		if($query->num_rows()>0)
		{
			$CI->db->update('interval',array('counter'=>(end($query->result())->counter)+1),array('id'=>end($query->result())->id));
		}
		else
		{
			$data = array(
								'start' => $this->start,
								'end' => $this->end,
								'counter' => 1
							);
			$CI->db->insert('interval',$data);
			
		}
		
		return TRUE;
	}
	function erase()
	{
		$CI =& get_instance();
		
		$CI->db->where('start',$this->start);
		$CI->db->where('end',$this->end);
		$query = $CI->db->get('interval');
		if($query->num_rows()==0)
		{
			return FALSE;
		}
		
		if(end($query->result())->counter>1)
		{
			$data = array('counter'=>(end($query->result())->counter)-1);
			$CI->db->update('interval',$data,array('id'=>end($query->result())->id));
		}
		else
		{
			$CI->db->delete('interval',array('id'=>end($query->result())->id));
		}
		return TRUE;
	}
	
	function get_id()
	{
		$CI =& get_instance();
		
		$CI->db->where('start',$this->start);
		$CI->db->where('end',$this->end);
		$query = $CI->db->get('interval');
		return (end($query->result())->id);
	}
	
}
