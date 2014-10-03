<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $info['name'];?></title>
	<link rel="stylesheet" type="text/css" href="/css/course/main.css"  />
    <link rel="stylesheet" type="text/css" href="/css/scrolls/scroll_iphone.css"/>
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/course/course_main.js"></script>
</head>

<body>
	<h1><?php echo $info['name'];?></h1>
    <div id="closeframe"></div>
    <div id="main">
    	<div class="arrow" id="up">
        	<p>UP</p>
        </div>
        <?php 
			$num=0;
		?>
		<?php foreach ($themes as $title=>$item):?>
        	<?php $num=$num+1;?>
        	<div class="cluster <?php if($num==1) echo 'current'; ?>" id="cluster<?php echo $num?>">
                <a target="dframe" class="box frameo" href="<?php echo site_url($item['url']);?>">
                    <p class="label">Course</p>
                    <p class="label"><?php echo $title;?></p>
                    <p class="count"><?php if (isset($item['count'])) echo $item['count']?></p>
                    <div class="triangle"></div>        
                </a>                
                <div class="list">
                    <div class="triangle2"></div>
                    <div class="block intro">
                        <p><?php echo $item['intro'];?></p>
                    </div>
                    <?php if (is_array($item['data']) && (count($item['data'])>0)):?>
                        <?php foreach($item['data'] as $jtem):?>
                            <?php if(is_array($jtem)):?>
                                <div class="block"><!--specific pages or questions-->
                                    <a target="dframe" class="frameo" href="<?php echo site_url("course/".$cid.'/'.$title.'/'.$jtem['id']);?>">
                                        <p class="title"><?php echo $jtem['title']?></p>
                                        <p class="time"><?php echo $jtem['created_time']?></p>
                                    </a>
                                </div>
                            <?php endif;?>
                        <?php endforeach;?>
                    <?php else:?>
                        <p>None</p><!--Clendar and Netdisk-->
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach;?>
        <?php if ($role=='teacher'):?>
         	<?php $num=$num+1;?>
           	<div class="cluster <?php if($num==1) echo 'current'; ?>" id="cluster<?php echo $num?>">
                <a target="dframe" class="box frameo" class="box" href="<?php echo site_url("course/".$cid."/setting");?>">
                    <p class="label">Course</p>
                    <p class="label">Setting</p>
                    <p class="count">?</p>
                    <div class="triangle"></div>        
                </a>
            </div>
        <?php endif;?>
        <div class="arrow" id="down">
        	<p>DOWN</p>
        </div>
        <script>
			var cluster_num=Number("<?php echo $num;?>");
		</script>
    </div>
    <iframe id="dframe" name="dframe"></iframe>
</body>
</html>