<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>HFI Home</title>
<link rel="stylesheet" type="text/css" href="/css/home/home.css"  />
</head>

<body>
    <div class="header">
    	<a href="<?php echo site_url('entrance/logout');?>">logout</a>
    </div>
    <div class="main">
    	<div class="welcome">
        	<p>Welcome,</p>
            <p><?php echo $info['user_info']['enn'];?></p>
        </div>
        <div class="col1">
            <a href="<?php echo site_url('msg');?>">
                <div class="block1">
                	<h2>You have</h2>
                    <h2><?php echo $new_mes_num;?></h2>
                    <h2>new message</h2>
                </div>
            </a>
        </div>
        <div class="col2">
            <a href="<?php echo site_url('calendar');?>">
                <div class="block2">
                    <h2>my schedule</h2>
                </div>
            </a>
            <a href="<?php echo site_url('academic');?>">
                <div class="block2">
                    <h2>my course</h2>
                </div>
            </a>
        </div>
    </div>
    <!--<?php var_dump($info);?>-->
</body>
</html>
