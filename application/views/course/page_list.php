<link rel="stylesheet" type="text/css" href="/css/course/list.css" />
<title>Page List</title>
    <h1>Pages</h1>
    <?php if (is_array($page_list) && (count($page_list)>0)):?>
		<?php foreach($page_list as $i => $item):?>
            <div class="page">
                <a class="page_box" href="<?php echo site_url('course/'.$cid.'/page/'.$item['id']);?>">
                    <p class="title"><?php echo $item['title'];?></p>
                    <p class="ctime inlineb">created at:<?php echo $item['created_time'];?></p>
                    <p class="ltime inlineb">latest update:<?php echo $item['latest_update'];?></p>
                    <div class="clear"></div>
                </a>
            </div>
        <?php endforeach;?>
    <?php else:?>
    	<p>No pages yet</p>
	<?php endif;?>