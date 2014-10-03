<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
	<form action="<?php echo site_url('home/add_group');?>" method="post">
    	<input type="text" name="gname" id="gname" />
        <input type="password" name="pword" id="pword"  />
        <input type="text" name="email" id="email"  />
        <input type="submit" name="submit" id="submit"  />
    </form>
</body>
</html>