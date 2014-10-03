<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>HFI Navigation</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/home/navigation.css');?>"  />
	<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/home/navigation.js');?>"></script>
</head>

<body>
	<div id="uname">
    	<p><?php echo ucfirst(strtolower($ename));?></p>
        <!--format: fname,gname-->
    	<p class="light"><?php echo ucwords(strtolower($cname));?></p>
    </div>
    <div class="box">
    	<div class="mini">
        </div>
    	<a class="button" id="Homepage" href="<?php echo site_url('home/main');?>" target="main">Homepage</a>
    </div>
    <div class="box">
    	<div class="mini">
        </div>
    	<a class="button" id="Course" href="<?php echo site_url('course');?>" target="main">Course</a>
    </div>
    <!--div class="box">
    	<div class="mini">
        </div>
    	<a class="button" id="Club" href="<?php echo site_url('club');?>" target="main">Club</a>
    </div-->
    <div class="box">
    	<div class="mini">
        </div>
    	<a class="button" id="Event" href="<?php echo site_url('calendar');?>" target="main">Calendar</a>
    </div>
    <div class="box">
    	<div class="mini">
        </div>
    	<a class="button" id="Notice"href="<?php echo site_url('notice');?>" target="main">Notice</a>
	</div>
    <div class="box">
    	<div class="mini">
        </div>
    	<a class="button" id="Message" href="<?php echo site_url('message');?>" target="main">Message</a>
    </div>
    <?php if (isset($ulib) && ($ulib!==FALSE)):?>
        <div class="box">
            <div class="mini">
            </div>
                <a class="button" id="Message" href="<?php echo site_url('ulibrary/welcome');?>" target="main">U Library</a>
        </div>
    <?php else:?>
    	<!--<?php echo var_dump($user_info);?>-->
    <?php endif;?>

    <div class="box logout">
    	<a class="button" id="logout" href="<?php echo site_url('account/logout');?>" target="_parent">Logout</a>
    </div>
</body>
</html>