	<link rel="stylesheet" type="text/css" href="/css/course/main.css"  />
    <link rel="stylesheet" type="text/css" href="/css/scrolls/scroll_iphone.css"/>
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/course/course_main.js"></script>
	<h1><?php echo $info['name'];?></h1>
    <div id="main">
		<?php foreach ($themes as $title=>$item):?>
                <a  class="box frameo" href="<?php echo site_url($item['url']);?>">
                    <p class="label">Course</p>
                    <p class="label"><?php echo $title;?></p>
                    <p class="count"><?php if (isset($item['count'])) echo $item['count']?></p>
                    <div class="triangle"></div>        
                </a>                
        <?php endforeach;?>
        <?php if ($role=='teacher'):?>
                <a  class="box frameo" class="box" href="<?php echo site_url("course/".$cid."/setting");?>">
                    <p class="label">Course</p>
                    <p class="label">Setting</p>
                    <p class="count">?</p>
                    <div class="triangle"></div>        
                </a>
        <?php endif;?>
    </div>
