<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
        <form style="border:solid 1px;"action="<?php echo site_url('calendar/'.$gid.'/event/create');?>" method="post">
            <h1>Add event test</h1>
            <p>GID:<?php echo $gid?></p>
            <p>Name</p>
            <input name="name" />
            <p>description</p>
            <textarea name="description"></textarea>
            <p>start</p>
            <input name="start" value="<?php echo date('Y-m-d',time())?>"/>
            <p>end</p>
            <input name="end" value="<?php echo date('Y-m-d',time()+3600*24)?>" />
            <p>type</p>
            <input name="type" />
            <input type="submit" value="submit"/>
        </form>
</body>
</html>