<<<<<<< HEAD
<link rel="stylesheet" type="text/css" href="/css/course/page.css"  />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Add Page</title>
<link rel="stylesheet" type="text/css" href="/css/course/page.css"  />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
</head>

<body>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
	<?php if ($info==FALSE):?>
	<form action="<?php echo site_url('course/'.$cid.'/page/create');?>" method="post">
        <p>Title</p>
        <input type="text" name="title"  />
        <p>Text</p>
        <textarea type="text" class="ckeditor" name="text"></textarea>
        <p>Whether send as an email</p>
        <span>Yes</span>
        <input type="radio" name="email" value="1" />
        <span>No</span>
        <input checked="checked" type="radio" name="email" value="0" />
        <p>Whether send as a notice</p>
        <span>Yes</span>
        <input type="radio" name="not"  value="1"/>
        <span>No</span>
        <input checked="checked" type="radio" name="not"  value="0"/>
        <br  />
        <input type="submit" value="submit"  />
    </form>
    <?php else:?>
    <form action="<?php echo site_url('course/'.$cid.'/page/'.$info['id'].'/edit');?>" method="post">
    	<input type="hidden" name="pid" value="<?php echo $info['id'];?>"  />
        <p>Title</p>
        <input type="text" name="title" value="<?php echo $info['title']?>" />
        <p>Text</p>
        <textarea type="text" class="ckeditor" name="text"><?php echo $info['text']?></textarea>
        <p>Whether send as an email</p>
        <span>Yes</span>
        <input type="radio" <?php if ($info['email']==1) echo 'checked="checked"'?> name="email" value="1" />
        <span>No</span>
        <input type="radio" <?php if ($info['email']==0) echo 'checked="checked"'?> name="email" value="0" />
        <p>Whether send as a notice</p>
        <span>Yes</span>
        <input type="radio" <?php if ($info['not']==1) echo 'checked="checked"'?> name="not"  value="1"/>
        <span>No</span>
        <input type="radio" <?php if ($info['not']==0) echo 'checked="checked"'?> name="not"  value="0"/>
        <input type="submit" value="submit"  />
    </form>
    <a href="<?php echo site_url('course/'.$cid.'/page/'.$info['id'].'/delete');?>">DELETE</a>
<<<<<<< HEAD
    <?php endif;?>
=======
    <?php endif;?>
</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
