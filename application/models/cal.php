<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*******************
*Calender Class by Chris*
**********************/

/*******************************************
*Hmm...Had lunch in Ducheng today. So spicy.
*By the way, Fridays and Saturdays are really
*great in that the animes I like just show up
*on these two days.
*Get to watch anime up in the morning. Hmm.
*Hahhahahahhahhahahah
*Muahahahhahahahahha
*@feeling 只要5分钟
*******************************************/
/******************************************
@feeling
啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊
好无聊的课啊
Personal Statement什么的。。。
................
Recommendation Form 终于写完了。。。
好麻烦。。。。。。
***********************************************/
class Cal extends CI_Model {


	var $uid;
	
	/***************
	*Constructor
	**************/	
	function __construct()
	{
		parent::__construct();
		$this->uid=$this->user->id;
	}
	
	/****************
	*Hmm the ice cream
	*was so tasty.
	****************/
	
	/****************
	*Create New event
	****************/
	function create($title,$description,$gid,$start,$end,$allday)
	{		
		//创建Interval对象
		$this->load->library('interval');
		$this->interval->set($start,$end);
		if(! $this->interval->write())
		{
			return FALSE;
		}
		//check input(gid)
		
		//数据库操作
		$this->db->trans_begin();
		
		$data = array(
							'title'=>$title,
							'description'=>$description,
							'allday'=>is_bool($allday)?$allday:FALSE,
							'auth'=>$this->uid,
							'gid'=>$gid
						);
		$this->db->insert('calender_event',$data);
		
		$data = array(
							'eventid'=>end($this->db->get_where('calender_event',array('title'=>$title))->result())->id,
							'intervalid'=>($this->interval->get_id())
						);
		$this->db->insert('calender_sub',$data);
		
		
		if($this->db->trans_status() === FALSE)
		{
			//错误则滚回
			$this->db->trans_rollback();
			return FALSE;
		}
		else  
		{
			$this->db->trans_commit();
			return TRUE;
		}  		
	}
	/**************
	*Delete Event
	***************/
	function delete($id)
	{
		//@todo 权限验证
		
		//Get Intervals
		$this->db->where('eventid',$id);
		$query = $this->db->get('calender_sub');
		
		//Construct Interval Object
		$this->load->library('interval');
		//遍历所有Interval对象，分别erase一次
		foreach($query->result() as $row)
		{
			$this->db->where('id',$row->intervalid);
			$query2 = $this->db->get('interval');
			$this->interval->set(end($query2->result())->start,end($query2->result())->end);
			$this->interval->erase();	
		}
		
		//删除数据库资料
		$this->db->trans_begin();
		
		$this->db->where('eventid',$id);
		$this->db->delete('calender_sub');
		
		$this->db->where('id',$id);
		$this->db->delete('calender_event');
		
		if($this->db->trans_status()===FALSE)
		{
			$this->db->trnas_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_complete();
			return TRUE;
		}
	}
	/**************************
	今天我要吃都城！！！
	MUAHAHAHAHAHAAHA
	***************************/
	
	//对id为$id的event新增一个interval，
	//开始时间为$start,结束时间为$end
	function addinterval($id,$start,$end)
	{
		//@todo 权限验证
		
		//Create Interval Object
		$this->load->library('interval');
		$this->interval->set($start,$end);
		$this->interval->write();
		
		//数据库操作
		$this->db->trans_begin();
		
		$data = array(
								'eventid'=>$id,
								'intervalid'=>$this->interval->get_id()
								);
		
		$this->db->insert('calender_sub',$data);
		
		
		/*************************
		量子态只有在束缚态的时候才是离散的！！
		在散射态的时候是连续的！！
		为什么不明白！！！
		*********************/
		
		if($this->db->trans_status()===FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_complete();
			return TRUE;
		}
		
	}
	
	/***************************
	唉。。。早上起来吃早餐。。
	吃完早餐马上想着吃午餐。。
	等了4节课后终于吃午餐了。。
	吃完午餐等晚餐。。。
	吃完晚餐等睡觉。。。
	又一天开始了。。。
	这几天好颓废。。。。
	***************************/
	
	
	function deleteinterval($id,$start,$end)
	{
		//@todo 检查权限
		
		//检查是否id为$id的event有这样一个interval
		//要是没有($start,$end)的Interval，那就没有删除可言
		$this->db->where('start',$start);
		$this->db->where('end',$end);
		$query = $this->db->get('interval');
		//刚刚喝了Chocolate Milk MUAHAHAHAHA
		$this->db->where('intervalid',end($query->result())->id);
		$this->db->where('eventid',$id);
		$query2 = $this->db->get('calender_sub');
		if($query2->num_rows()==0)
		{
			return FALSE;
		}
		
		//创建Interval对象
		$this->load->library('interval');
		$this->interval->set($start,$end);
		$this->interval->erase();
		
		//数据库操作
		$this->db->trans_begin();
		
		//Hmm...Interesting
		/********************
		Complex Analysis 好难
		*********************/
		
		$this->db->where('intervalid',end($query->result())->id);
		$this->db->where('eventid',$id);
		$this->db->delete('calender_sub');
		
		if($this->db->trans_status()===FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_complete();
			return TRUE;
		}
	}
	
	//输入event的id，
	//返回一个n*3数组，
	//分别表示interval的id，开始时间和结束时间
	function get_interval($id)
	{
		//@todo 检查权限
		
		//数据库操作
		$this->db->trans_start();
		//读取数据库calender_sub
		$this->db->where('eventid',$id);
		$query = $this->db->get('calender_sub');
		
		foreach($query->result() as $row)
		{
			$this->db->or_where('id',$row->intervalid);
		}
		$this->db->order_by('end','asc');
		$this->db->order_by('start','asc');
		$data = $this->db->get('interval')->row_array();
		
		//好颓废的一天
		
		$this->db->trans_complete();
	
		if($this->db->trans_status==FALSE)
		{
			return FALSE;
		}
		
		return $data;
	}
	
	
	function get_event($eid = NULL)
	{
		if (! is_numeric($eid))
			throw new InvalidArgumentException('Invalid Argument: $eid:'.var_dump($eid));
		
		$this->db->join('calender_sub','calender_event.id=calender_sub.eventid');	
		$this->db->join('interval','interval.id=calender_sub.intervalid');
		return $this->db->get_where('calender_event',array('calender_event.id'=>$eid))->row_array();
	}
	
	function get_events($start,$end,$gid = 0)
	{
		if ($gid != 0)
		{
			if (! is_numeric($gid))
				return FALSE;
				
			$this->db->where('calender_event.gid',$gid);
			$this->db->where('interval.start >',$start-1);
			$this->db->where('interval.end <',$end+1);
			$this->db->select('calender_event.*, interval.start, interval.end, group.name');
			$this->db->join('calender_sub','calender_sub.intervalid=interval.id');
			$this->db->join('calender_event','calender_sub.eventid=calender_event.id');
			$this->db->join('group','calender_event.gid=group.id');
			return $this->db->get('interval')->result_array();
		}
		else//get all
		{
			$arr = $this->db->get_where('user_sub',array('uid'=>$this->uid))->result_array();
			$gids=array();
			foreach ($arr as $item)
			{
				array_push($gids,$item['gid']);
			}
			$this->db->where_in('calender_event.gid',$gids);
			$this->db->where('interval.start >',$start-1);
			$this->db->where('interval.end <',$end+1);
			$this->db->select('calender_event.*, interval.start, interval.end, group.name');
			$this->db->join('calender_sub','calender_sub.intervalid=interval.id');
			$this->db->join('calender_event','calender_sub.eventid=calender_event.id');
			$this->db->join('group','calender_event.gid=group.id');
			$return1 = $this->db->get('interval')->result_array();
			
			$this->db->where('calender_event.gid',0);
			$this->db->where('interval.start >',$start-1);
			$this->db->where('interval.end <',$end+1);
			$this->db->where('calender_event.auth',$this->uid);
			$this->db->select('calender_event.*, interval.start, interval.end, group.name');
			$this->db->join('calender_sub','calender_sub.intervalid=interval.id');
			$this->db->join('calender_event','calender_sub.eventid=calender_event.id');
			$this->db->join('group','calender_event.gid=group.id');
			$return2 = $this->db->get('interval')->result_array();
			
			foreach ($return2 as $item)
			{
				array_push($return1,$item);
			}

			return $return1;
		}
	}
}