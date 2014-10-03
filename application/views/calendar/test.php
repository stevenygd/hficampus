<<<<<<< HEAD
<form style="border:solid 1px;"action="<?php echo site_url('calendar/event/add');?>" method="post">
    <h1>Add event test</h1>
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
<form style="border:solid 1px;"action="<?php echo site_url('calendar/event/del');?>" method="get">
    <h1>Delete event test</h1>
    <p>id</p>
    <input name="id" />
    <input type="submit" value="submit"/>
</form>
<form style="border:solid 1px;"action="<?php echo site_url('calendar/event/addint');?>" method="post">
    <h1>Add interval test</h1>
    <p>id</p>
    <input name="id" />
    <p>start</p>
    <input name="start" value="<?php echo date('Y-m-d',time()+3600*24)?>"/>
    <p>end</p>
    <input name="end" value="<?php echo date('Y-m-d',time()+3600*24*2)?>" />
    <input type="submit" value="submit"/>
</form>
<form style="border:solid 1px;"action="<?php echo site_url('calendar/event/delint');?>" method="post">
    <h1>Delete interval test</h1>
    <p>id</p>
    <input name="id" />
    <p>start</p>
    <input name="start" value="<?php echo date('Y-m-d',time()+3600*24)?>"/>
    <p>end</p>
    <input name="end" value="<?php echo date('Y-m-d',time()+3600*24*2)?>" />
    <input type="submit" value="submit"/>
</form>
<form style="border:solid 1px;"action="<?php echo site_url('calendar/event/get');?>" method="post">
    <h1>Get interval test</h1>
    <p>start</p>
    <input name="start" value="<?php echo date('Y-m-d',time()+3600*24)?>"/>
    <p>end</p>
    <input name="end" value="<?php echo date('Y-m-d',time()+3600*24*2)?>" />
    <input type="submit" value="submit"/>
</form>
=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Calendar</title>
</head>

<body>
	<form style="border:solid 1px;"action="<?php echo site_url('calendar/event/add');?>" method="post">
    	<h1>Add event test</h1>
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
    <form style="border:solid 1px;"action="<?php echo site_url('calendar/event/del');?>" method="get">
    	<h1>Delete event test</h1>
    	<p>id</p>
        <input name="id" />
        <input type="submit" value="submit"/>
    </form>
	<form style="border:solid 1px;"action="<?php echo site_url('calendar/event/addint');?>" method="post">
    	<h1>Add interval test</h1>
    	<p>id</p>
        <input name="id" />
    	<p>start</p>
    	<input name="start" value="<?php echo date('Y-m-d',time()+3600*24)?>"/>
    	<p>end</p>
    	<input name="end" value="<?php echo date('Y-m-d',time()+3600*24*2)?>" />
        <input type="submit" value="submit"/>
    </form>
	<form style="border:solid 1px;"action="<?php echo site_url('calendar/event/delint');?>" method="post">
    	<h1>Delete interval test</h1>
    	<p>id</p>
        <input name="id" />
    	<p>start</p>
    	<input name="start" value="<?php echo date('Y-m-d',time()+3600*24)?>"/>
    	<p>end</p>
    	<input name="end" value="<?php echo date('Y-m-d',time()+3600*24*2)?>" />
        <input type="submit" value="submit"/>
    </form>
	<form style="border:solid 1px;"action="<?php echo site_url('calendar/event/get');?>" method="post">
    	<h1>Get interval test</h1>
    	<p>start</p>
    	<input name="start" value="<?php echo date('Y-m-d',time()+3600*24)?>"/>
    	<p>end</p>
    	<input name="end" value="<?php echo date('Y-m-d',time()+3600*24*2)?>" />
        <input type="submit" value="submit"/>
    </form>
</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
