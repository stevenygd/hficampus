<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Message</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/msg/msg.css');?>" />
<link ref="stylesheet" type="text/css" href="/css/scrolls/scroll_1_blue.css" />
<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
<script src="<?php echo base_url('js/msg/msg.js');?>" type="text/javascript"></script>
<!--script src="/js/msg/msg.js" type="text/javascript"></script-->

</head>
<body>
	<?php if(is_array($msg_list) && (count($msg_list)>0)):?>
        <?php foreach($msg_list as $i => $item):?>
            <a class="button" href="<?php echo site_url('message/'.$i.'/get#last');?>" target="conver">
                <div class="box" id="box<?php echo $i;?>">
                    <div class="mini"></div>
                    <p class="name"><span><?php echo $finfo[$i]['enn'];?></span> <?php echo $finfo[$i]['cnln'];?></p>
                    <?php if (substr($item[0]['time'],0,10)==substr(date('Y-m-d H:i:s'),0,10)):?>
                        <p class="time"><?php echo substr($item[0]['time'],11,5);?></p>
                    <?php else:?>
                        <p class="time"><?php echo substr($item[0]['time'],0,16);?></p>
                    <?php endif;?>
                    <p class="msg"><?php echo $item[0]['msg'];?></p>
                    <input type="hidden" class="lid" value="<?php echo $item[0]['id'];?>" />
                    <input type="hidden" class="uid" value="<?php echo $i;?>" />
                </div>
            </a>
        <?php endforeach;?>
    <?php endif;?>
    
    <?php if (is_array($invitation) && (count($invitation)>0)):// var_dump($invitation);?>
        <a class="button" href="<?php echo site_url('message/invitation');?>" target="conver">
            <div class="box" id="box_last">
                <div class="mini"></div>
                <p>There are <?php echo count($invitation);?> people who want to be your friend!</p>
            </div>
        </a>
    <?php endif;?>
    
    <input type="hidden" id="uid" value="<?php echo $uid;?>"/>
</body>
</html>
