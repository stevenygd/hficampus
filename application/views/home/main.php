<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Home</title>
<!--link rel="stylesheet" type="text/css" href="/css/home/home3.css"  /-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/home/home3.css');?>"  />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/home/home3.js');?>"></script>
<script type="text/javascript" src="/js/home/home3.js"></script>
</head>

<body>
    <h3><span>HFI</span> Campus Express</h3>
    <h1>Welcome, <?php echo ucfirst(strtolower($user_info['enn']));?></h1>
    <div id="boxes">
    	<?php foreach ($boxes as $item):?>
        	<?php //just for event
				if ($item =='event')
					$url='calendar';
				else
					$url=$item;
				if ($item=='club')
					continue;
			?>
            <a href="<?php echo site_url($url);?>" class="box2">
                <p class="label">My</p>
                <p class="label"><?php echo ucfirst($item);?></p>
                <input type="hidden" class="name" value="<?php echo ucfirst($item);?>" />
                <p class="count"><?php echo $num['my'.$item]?></p>
                <!--$mycount: number of my courses-->
                <div class="triangle"></div>        
            </a>
        <?php endforeach;?>
        <a href="<?php echo site_url('account/setting');?>" class="box2">
            <p class="label">My</p>
            <p class="label">Setting</p>
            <p class="count"><!--?php echo $count;?-->?</p>
            <!--$count: number of all courses-->
            <div class="triangle"></div>        
        </a>  
    </div>
    <iframe name="frame" id="frame" src=""></iframe>
</body>
</html>