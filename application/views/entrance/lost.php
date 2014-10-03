<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lost</title>
<link rel="stylesheet" type="text/css" href="/css/entrance/lost.css"  />
<script type="text/javascript" src="/js/entrance/lost.js"></script>
</head>
<body>
	<h1>HFI Campus Express</h1>
	<h2>Forgot username</h2>
	<div>
		<form action="<?php echo site_url('account/lost_name');?>" method="post">
			<label>Pinyin name</label>
			<input type="text" name="pname" />
			<label>email</label>
			<input type="text" name="email" />
    		<input type="submit" name="submit" value="submit"  />
		</form>
    <?php if(isset($lost_name)) echo $lost_name['err_msg'].'</br>',$lost_name['err_code'];?>
	</div>
	<h2>Forgot password</h2>
	<div>
		<form action="<?php echo site_url('account/lost_pw');?>" method="post">
			<label>username</label>
			<input type="text" name="uname" />
			<label>email</label>
			<input type="text" name="email" />
            <input type="submit" value="submit"/>
		</form>
    <?php if(isset($lost_pw)) echo $lost_pw['err_msg'].'</br>',$lost_pw['err_code'];?>
	</div>
    </br>
</body>
</html>