<<<<<<< HEAD
=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
<title>Page-<?php echo $page['title'];?></title>
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

#comments{
	border-top:solid 1px;
	padding-left:0;
}

#comments p{
	
}

#comments li{
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
			$(this).val("Say something?");
		}
	});
});
</script>
</head>

<body>
    <ul id="navi">
        <li><a href="<?php echo site_url('course/'.$cid.'/page');?>">Page list</a></li>
        <li>&gt;</li>
        <li><?php echo $page['title']?></li>
    </ul>
    <h1>
		<?php echo $page['title']?>
	    <span class="time">created at <?php echo $page['created_time'];?></span>
    </h1>
    <?php echo $page['text'];?>
    <ul id="comments">
    	<?php if (is_array($comment) && (count($comment)>0)):?>
        	<p>Comments</[>
			<?php foreach($comment as $i=>$item):?>
                <li>
                	<p class="comment_header"><?php echo $item['cnfn'].' '.$item['cnln'].' comment at'.$item['time'];?></p>
                	<p class="comment_content"><?php echo $item['text'];?></p>
                </li>
            <?php endforeach;?>
        <?php else:?>
        	<li>No comments</li>
        <?php endif;?>
        
        <div>
            <input type="button" id="write" value="Say something?" />
            <form class="hide" action="<?php echo site_url('course/'.$cid.'/page/'.$page['id'].'/comment/create');?>" method="post">
                <input type="hidden" name="pid" value="<?php echo $page['id'];?>"/>
                <textarea class="ckeditor" name="text"></textarea>
                <input type="submit" value="submit"  />
            </form>
        </div>
<<<<<<< HEAD
    </div>
=======
    </div>
</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
