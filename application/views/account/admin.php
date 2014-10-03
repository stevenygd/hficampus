<<<<<<< HEAD
<form action="<?php echo site_url('account/add_group');?>" method="post">
    <h2>Add Group</h2>
    <input type="text" name="gname" id="gname" />
    <input type="password" name="pword" id="pword"  />
    <input type="text" name="email" id="email"  />
    <input type="submit" name="submit" id="submit"  />
</form>
<form action="<?php echo site_url('account/admin/adduser');?>" method="post">
    <h2>Add Special User</h2>
    <input type="text" name="uname" id="gname" />
    <input type="password" name="pw" id="pword"  />
    <input type="text" name="email" id="email"  />
    <select name="type">
        <option selected="selected" value="">Please selecet what type of user</option>
        <option value="developer">Developer</option>
        <option value="aca">Academic Teacher</option>
        <option value="office">Officer</option>
    </select>
    <input type="submit" name="submit" id="submit"  />
</form>
=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
	<form action="<?php echo site_url('account/add_group');?>" method="post">
    	<h2>Add Group</h2>
    	<input type="text" name="gname" id="gname" />
        <input type="password" name="pword" id="pword"  />
        <input type="text" name="email" id="email"  />
        <input type="submit" name="submit" id="submit"  />
    </form>
	<form action="<?php echo site_url('account/admin/adduser');?>" method="post">
    	<h2>Add Special User</h2>
    	<input type="text" name="uname" id="gname" />
        <input type="password" name="pw" id="pword"  />
        <input type="text" name="email" id="email"  />
        <select name="type">
        	<option selected="selected" value="">Please selecet what type of user</option>
        	<option value="developer">Developer</option>
        	<option value="aca">Academic Teacher</option>
        	<option value="office">Officer</option>
        </select>
        <input type="submit" name="submit" id="submit"  />
    </form>
</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
