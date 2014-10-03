<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Question-<?php echo $question['title'];?></title>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
<style>
html{
	width:100%;
	height:100%;
}
body{
	padding:0 40px;
}

#navi{
	padding:0;
	position:relative;
}

#navi li{
	display:inline-block
}
h1{
	text-align:center;
	border-bottom:solid 1px;
	display:block;
}

.time{
	display:block;
	font-size:10px;
	margin:10px 0;
}

#answers{
	border-top:solid 1px;
	padding-left:0;
}

#answers p{
	
}

#answers li{
	display:block;
}

#write{
	color: #0C034D;
	background-color: white;
	display: block;
	width: auto;
	border: none;
	font-size: 1.5em;
	-webkit-margin-before: 0.83em;
	-webkit-margin-after: 0.83em;
	-webkit-margin-start: 0px;
	-webkit-margin-end: 0px;
	font-weight: bold;
	border: none;
	box-shadow: 0.5px 0.5px 4px 1px rgba(153, 153, 153, 0.53);
}

.hide{
	display:none;
}
</style>

<script>
$(document).ready(function(){
	$("#write").click(function(){
		if ($(this).next().hasClass("hide"))
		{
			$(this).next().removeClass("hide");
			$(this).val("Close");
		}
		else
		{
			$(this).next().addClass("hide");
			$(this).val("Have an anser?");
		}
	});
});
</script>

</head>

<body>
    <ul id="navi">
        <li><a href="<?php echo site_url('course/'.$cid.'/question');?>">Question list</a></li>
        <li>&gt;</li>
        <li><?php echo $question['title']?></li>
    </ul>
    <h1><?php echo $question['title']?><span class="time"><?php echo $question['created_time'];?></span></h1>
    <?php echo $question['text'];?>
    <ul id="answers">
    	<?php if (is_array($comment) && (count($comment)>0)):?>
			<?php foreach($comment as $i=>$item):?>
                <li>
                	<p class="comment_header"><?php echo $item['cnfn'].' '.$item['cnln'].' answer at'.$item['time'];?></p>
                	<p class="comment_content"><?php echo $item['text'];?></p>
                </li>
            <?php endforeach;?>
        <?php else:?>
        	<li>No answers</li>
        <?php endif;?>
        <div>
            <input type="button" id="write" value="Have an anser?" />
            <form class="hide" action="<?php echo site_url('course/'.$cid.'/question/'.$qid.'/answer/create');?>" method="post">
                <input type="hidden" name="qid" value="<?php echo $qid;?>"  />
                <textarea class="ckeditor" name="text"></textarea>
                <input type="submit" value="submit"  />
            </form>
		</div>
	</ul>
</body>
</html>