<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>test</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css"  media="screen"/>
	<link href="/css/bootstrap/flat-ui.css" rel="stylesheet"/>
	<link href="/css/bootstrap/font-awesome.min.css" rel="stylesheet"/>
	<link href="/css/bootstrap/buttons.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="/css/layout/styles.css" />
	<link rel="stylesheet" type="text/css" href="/css/layout/home.css" />
	<script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/layout/responsive-nav.js"></script>
    <script>
    		var site_url = "<?php echo site_url();?>";
		var base_url = "<?php echo base_url();?>";
		var uid = "<?php echo $uid;?>";
	</script>
</head>
<body>
	<div role="navigation" id="foo" class="nav-collapse">
      <ul>
        <li class="active"><a href="<?php echo site_url('home');?>">Home</a></li>
        <li><a href="<?php echo site_url('course');?>">Course</a></li>
        <li><a href="<?php echo site_url('calendar');?>">Calendar</a></li>
        <li><a href="<?php echo site_url('notice');?>">Notice</a></li>
        <li><a href="<?php echo site_url('chatrooms');?>">Message</a></li>
        <li><a href="<?php echo site_url('account/logout');?>">Log Out</a></li>
      </ul>
    </div>
    <div role="main" class="main">
        <a href="#nav" class="nav-toggle">Menu</a>
    	<?php echo $yield;?>
    </div>
	<script src="/js/bootstrap/buttons.js"></script>
    <script src="/js/bootstrap/bootstrap.min.js"></script>
    <script src="/js/bootstrap/bootstrap-select.js"></script>
    <script src="/js/bootstrap/bootstrap-switch.js"></script>
    <script src="/js/bootstrap/flatui-checkbox.js"></script>
    <script src="/js/bootstrap/flatui-radio.js"></script>
    <script src="/js/bootstrap/jquery.tagsinput.js"></script>
    <script src="/js/bootstrap/jquery.placeholder.js"></script>
    <script>
      var navigation = responsiveNav("foo", {customToggle: ".nav-toggle"});
    </script>
</body>
</html>