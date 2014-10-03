<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>CLUB TEST</title>
    <style>
		form {
			border:solid 1px;
		}
	</style>
</head>

<body>
	<?php 
		if ($this->input->get('cid'))
			$cid=$this->input->get('cid');
		else
			$cid=1;
		if ($this->input->get('bid'))
			$bid=$this->input->get('bid');
		else
			$bid=1;
		if ($this->input->get('pid'))
			$pid=$this->input->get('pid');
		else
			$pid=1;
		if ($this->input->get('tid'))
			$tid=$this->input->get('tid');
		else
			$tid=1;
	?>
    
	<form action="<?php echo site_url('club/create');?>" method="post">
    	<h3>Create Club</h3>
    	<?php foreach (array('gid','name','description') as $item):?>
    		<p><?php echo $item;?>:<input name="<?php echo $item;?>" /></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
    </form>
    
	<form action="<?php echo site_url('club/'.$cid.'/blog/create');?>" method="post">
    	<h3>Add Club Blog</h3>
    	<?php foreach (array('cid','title','content') as $item):?>
    		<p><?php echo $item;?>:<input name="<?php echo $item;?>" /></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
    </form>
    
	<form action="<?php echo site_url('club/'.$cid.'/blog/'.$bid.'/comment/create');?>" method="post">
    	<h3>Comment on Club Blog</h3>
    	<?php foreach (array('bid','content') as $item):?>
    		<p><?php echo $item;?>:<input name="<?php echo $item;?>" /></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
    </form>    
    
	<form action="<?php echo site_url('club/'.$cid.'/page/create');?>" method="post">
    	<h3>Add Club Page</h3>
    	<?php foreach (array('cid','title','content') as $item):?>
    		<p><?php echo $item;?>:<input name="<?php echo $item;?>" /></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
    </form>
    
	<form action="<?php echo site_url('club/'.$cid.'/page/'.$pid.'/comment/create');?>" method="post">
    	<h3>Comment on Club Page</h3>
    	<?php foreach (array('pid','content') as $item):?>
    		<p><?php echo $item;?>:<input name="<?php echo $item;?>" /></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
    </form>    
    
	<form action="<?php echo site_url('club/'.$cid.'/topic/create');?>" method="post">
    	<h3>Add Club Topics</h3>
    	<?php foreach (array('cid','title','content') as $item):?>
    		<p><?php echo $item;?>:<input name="<?php echo $item;?>" /></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
    </form>
    
    <form action="<?php echo site_url('club/'.$cid.'/topic/'.$tid.'/comment/create');?>" method="post">
    	<h3>Comment on Club Topic</h3>
    	<?php foreach (array('tid','content') as $item):?>
    		<p><?php echo $item;?>:<input name="<?php echo $item;?>" /></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
    </form>    

</body>
</html>
