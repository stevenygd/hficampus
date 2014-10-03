<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>HFI Campus Notice</title>
    <link rel="stylesheet" href="/css/notice/index.css" />
    <link rel="stylesheet" href="/css/scrolls/scroll_iphone.css" />
    <script type="application/javascript" src="/js/jquery.js" ></script>
    <script type="application/javascript">
		var site_url="<?php echo site_url();?>";
		var base_url="<?php echo base_url();?>";
	</script>
    <script type="application/javascript" src="/js/notice/index.js"></script>
</head>

<body>
	<?php $new=2;?>
    <!--$new: notices in this week-->
    <h3><span>HFI</span> Campus Express</h3>
    <h1>Notice</h1>
    <div id ="middle">
        <div id="groups">
        	<?php $first=TRUE;?>
            <?php foreach($mysub as $i=>$item):?>
            	<?php if (($first===TRUE) && ($notice[$item['gid']]!==FALSE)) {$first=array();$first['gid']=$item['gid'];$first['i']=$i;}?>
                <div class="group">
                	<div class="mini"></div>
					<p><?php echo $item['name'];?></p>
                  <input type="hidden" value="<?php echo $item['gid'];?>" />
                </div>
                <?php $notice[$item['gid']]['group_name']=$item['name'];?>
            <?php endforeach;?>
        </div>
        <div class="triangle"></div>
    </div>
    <div class="create">
       <img src="/images/course/create.png" />
       <p>Send new notice</p>
    </div>
    <div id="right" class="right">
    	<?php foreach($notice as $igid => $item):?>
        	<?php if (isset($notice[$igid][0])):?>
                <div id="<?php echo 'notice_'.$igid;?>" class="notice_block hide">
                    <h6><?php echo $item['group_name'];?></h6>
                    <div class="notice">
                        <p class="title"><?php echo $notice[$igid][0]['title'];?></p>
                        <p class="time"><?php echo $notice[$igid][0]['time'];?></p>
                        <p class="text"><?php echo $notice[$igid][0]['msg'];?></p>
                        <p class="next fetch">Next</p>
                        <input class="count" type="hidden" value="0" />
                        <input class="group" type="hidden" value="<?php echo $igid;?>" />
                    </div>
                </div>
            <?php endif;?>
        <?php endforeach;?>
        <div class="triangle3"></div>
    </div>

	<div id="add" class="right hide">
        <div class="add_info">
            <label>To</label>
            <br />
            <select name="to">
                <?php foreach($mygroup as $item):?>
                <!--$group: pertaining group of user-->
                    <option value="<?php echo $item['gid'];?>"><?php echo $item['name'];?></option>
                <?php endforeach;?>
                <option value="5">Everyone</option>
            </select>
            <br />
            <label>Titile</label>
            <br  />
            <input class="title" name="title"  />
            <br  />
            <label>Notice</label>
            <br  />
            <textarea class="text" name="text"></textarea>
            <br />
            <!--<label>Send with email</label>
            <input type="checkbox" name="email" value="email" />-->
            <p class="add_submit">Submit</p>
            <p class="hide">Processing...</p>
        </div>
        <div class="triangle3"></div>
    </div>
<!--<pre>
	mysub:<?php echo var_dump($mysub);?>
</pre>
<pre>
	mygroup:<?php echo var_dump($mygroup);?>
</pre>
<pre>
	notice:<?php echo var_dump($notice);?>
</pre>--!>

<ul>
</ul>
</body>
</html>