<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CONVERSATION</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/msg/conver.css');?>"  />
<link rel="stylesheet" type="text/css" href="/css/scrolls/scroll_1_white.css" />
<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/msg/conver.js');?>"></script>
<!--script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/msg/conver.js"></script-->

</head>

<body>
	<div class="contend">
    	<h2><span><?php echo $uinfo[$eid]['enn'];?></span> <?php echo $uinfo[$eid]['cnln'];?></h2>
    </div>
    <div class="page">
		<?php foreach(array_reverse($conver) as $i=>$item):?>
            <?php if ($item['auth']==$uid):?>	
                <div class="sent msgbox">
                    <?php if (substr($item['time'],0,10)==substr(date('Y-m-d H:i:s',time()),0,10)):?>
                        <p class="time"><?php echo substr($item['time'],11,5);?></p>
                    <?php else:?>
                        <p class="time"><?php echo substr($item['time'],0,16);?></p>
                    <?php endif;?>
                    <p class="msg"><?php echo $item['msg'];?></p>           
                </div>
            <?php else:?>
                <div class="receive msgbox">
                    <?php if (substr($item['time'],0,10)==substr(date('Y-m-d H:i:s', time()),0,10)):?>
                        <p class="time"><?php echo substr($item['time'],11,5);?></p>
                    <?php else:?>
                        <p class="time"><?php echo substr($item['time'],0,16);?></p>
                    <?php endif;?>            
                    <p class="msg"><?php echo $item['msg'];?></p>
                </div>
            <?php endif;?>
        <?php endforeach;?>
    </div>        
    <div id="send">
        <form action="<?php echo site_url('message/'.$eid.'/create');?>" method="post">
            <input type="hidden" name="to" value="<?php echo $eid?>"/>
            <textarea type="text" name="text" class="text" value="<?php echo set_value('text');?>" ></textarea>
            <?php echo form_error('text');?>
            <input type="submit" class="submit" value="SEND"  />
        </form>
    </div>
    <input type="hidden" id="lid" value="<?php echo $lid;?>"/>
    <input type="hidden" id="uid" value="<?php echo $uid;?>"/>
    <input type="hidden" id="eid" value="<?php echo $eid;?>"/>
    <div class="reply">
    	<input type="button" id="reply" value="+reply" />
    </div>
    <div id="last"></div>
</body>
</html>