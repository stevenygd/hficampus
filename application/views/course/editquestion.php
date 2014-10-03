<<<<<<< HEAD
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
<?php if ($info==FALSE):?>
	<form action="<?php echo site_url('course/'.$cid.'/question/create');?>" method="post">
		<input type="hidden" name="cid" value="<?php echo $cid;?>"  />
		<p>Title</p>
		<input type="text" name="title"  />
		<p>Text</p>
		<textarea class="ckeditor" name="text"></textarea>
		<input type="submit" value="submit"  />
	</form>
<?php else:?>
	<form action="<?php echo site_url('course/'.$cid.'/question/'.$info['id'].'/edit');?>" method="post">
		<input type="hidden" name="cid" value="<?php echo $cid;?>"  />
		<p>Title</p>
		<input type="text" name="title" value="<?php echo $info['title']?>" />
		<p>Text</p>
		<textarea class="ckeditor" name="text"><?php echo $info['text']?></textarea>
		<input type="submit" value="submit"  />
	</form>
	<a href="<?php echo site_url('course/'.$cid.'/page/'.$info['id'].'/delete');?>">DELETE</a>
<?php endif;?>
=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
<title>Add Question</title>
</head>

<body>
	<?php if ($info==FALSE):?>
        <form action="<?php echo site_url('course/'.$cid.'/question/create');?>" method="post">
            <input type="hidden" name="cid" value="<?php echo $cid;?>"  />
            <p>Title</p>
            <input type="text" name="title"  />
            <p>Text</p>
            <textarea class="ckeditor" name="text"></textarea>
            <input type="submit" value="submit"  />
        </form>
    <?php else:?>
        <form action="<?php echo site_url('course/'.$cid.'/question/'.$info['id'].'/edit');?>" method="post">
            <input type="hidden" name="cid" value="<?php echo $cid;?>"  />
            <p>Title</p>
            <input type="text" name="title" value="<?php echo $info['title']?>" />
            <p>Text</p>
            <textarea class="ckeditor" name="text"><?php echo $info['text']?></textarea>
            <input type="submit" value="submit"  />
        </form>
    	<a href="<?php echo site_url('course/'.$cid.'/page/'.$info['id'].'/delete');?>">DELETE</a>
    <?php endif;?>
</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
