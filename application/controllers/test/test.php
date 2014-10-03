<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*test.php by StevenY*/
class Test extends SAE_Controller {
	
	var $uid;
	
	function __construct()
	{
		parent::__construct();
		$this->uid=$this->user->id;
	}
	
	function get($mid = NULL)
	{
		if (isset($mid))
		{
			switch($mid)
			{
				case 'synall':
					$this->db->select('id');
					$users=$this->db->get_where('user')->result_array();
					$this->load->library('permission');
					foreach ($users as $item)
					{
						echo $item['id'].':'.var_dump($this->permission->syn($item['id'])).'</br>';
					}
				break;
				case 'checkKVDB':
					$kv = new SaeKV();
					// 初始化KVClient对象
					$ret = $kv->init();
					var_dump($ret);
					// 循环获取所有key-values       
					$ret = $kv->pkrget('', 100);     
					while (true) {                    
						var_dump($ret);                       
						end($ret);                                
						$start_key = key($ret);
						$i = count($ret);
						if ($i < 100) break;
						$ret = $kv->pkrget('', 100, $start_key);
					}
				break;
				case 'clearKVDB':
					$kv = new SaeKV();
					// 初始化KVClient对象
					$ret = $kv->init();
					var_dump($ret);
					// 循环获取所有key-values       
					$ret = $kv->pkrget('', 100);     
					while (true) {                    
						foreach($ret as $i=>$item)
						{
							$rev = $kv->delete($i);
    						var_dump($rev);
						}
						end($ret);                                
						$start_key = key($ret);
						$i = count($ret);
						if ($i < 100) break;
						$ret = $kv->pkrget('', 100, $start_key);
					}
				break;
				default:
				break;
			}
		}
		else
		{
			$a['error']=NULL;
			$this->db->select('group.*, user_info.cnfn, user_info.cnln, user_info.enn');
			$this->db->join('user_info','user_info.uid=group.auth');
			$a['group']=$this->db->get_where('group')->result_array();
			foreach ($a['group'] as $item)
			{
				$a['permi'][$item['id']]=$this->db->get_where('auth',array('gid'=>$item['id']))->result_array();
				$this->db->join('user_info','user_info.uid=user_sub.uid');
				$a['sub'][$item['id']]=$this->db->get_where('user_sub',array('user_sub.gid'=>$item['id']))->result_array();
			}
			$this->push('test/fortest',$a);
		}
	}
		
	function edit($mid = NULL)
	{
		if (isset($mid))
		{
			switch($mid)
			{
				case 'addauth':
					$this->load->library('permission');
					$a['group']=$this->permission->addto_group( $this->input->post('gid'),
																$this->input->post('auth')?$this->input->post('auth'):'-----',
																$this->input->post('mtype'),
																$this->input->post('mid'),
																$this->input->post('ntype'),
																$this->input->post('nid')
																);
					if (! $a['group'])
						$a['error']='Error:add permission failed';
					$this->push('test/fortest',$a);
				break;
				default:
				break;
			}
		}
		else
		{
		}
	}
	
	function delete($mid = NULL)
	{
		if (isset($mid))
		{
			switch($mid)
			{
				case 'delauth':
					$this->load->library('permission');
					$a['group']=$this->permission->delfrom_group($this->input->get('gid'),
																  $this->input->post('auth')?$this->input->post('auth'):'-----',
																  $this->input->get('mtype'),
																  $this->input->get('mid'),
																  $this->input->get('ntype'),
																  $this->input->get('nid')
																);
					if (! $a['group'])
						$a['error']='Error:syn failed';
					$this->push('test/fortest',$a);
				break;
				case 'deluser':
					if ($this->input->get('uid') && $this->input->get('gid'))
					{
						$uid=$this->input->get('uid');
						$gid=$this->input->get('gid');
						$this->load->model('group');
						if ($this->group->kick($uid,$gid))
						{
							$this->push('test',0,TRUE);;
						}
						else
							echo $this->group->get_err_msg();
					}
					else
						throw new MY_Exception('Nothing recieved');
				break;
			}
		}
		else
		{
		}
	}
	
	function create($mid = NULL)
	{
		if (isset($mid))
		{
			switch($mid)
			{
				case 'adduser'://add user to particular group
					if ($this->input->post('gid') && $this->input->post('uid'))
					{
						$this->load->model('group');
						if ($this->group->add($this->input->post('uid'),$this->input->post('gid')))
						{
							$this->push('test',0,TRUE);
						}
						else
						{
							echo $this->group->get_err_msg().'Something Wrong!!!';
						}
					}
					else
						throw new MY_Exception('Nothing recieved');
				break;
				
			}
		}
		else
		{
		}
	}
}