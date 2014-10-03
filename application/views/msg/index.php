<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Message</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/msg/index.css');?>"  />
<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
<script type="text/javascript" src="/js/msg/index.js"></script>
</head>

<body>
	<h3><span>HFI</span> Campus Express</h3>
	<h1>Mailbox</h1>
	<iframe frameborder="0" src="<?php echo site_url('message/main');?>" width="350" height="350"></iframe>
	<div id="list">
    	<input type="text" name"search" id="search" />
    </div>
    <div id="text">
    </div>
    <iframe frameborder="0" name="conver" id="conver" width="350" height="350"></iframe>
    <div class="button">
    	<p id="add">add Friend</p>
        <p id="new">+write new message</p>
    </div>
    <input type="hidden" id="uid" value"<?php echo $uid;?>" />
</body>
</html>