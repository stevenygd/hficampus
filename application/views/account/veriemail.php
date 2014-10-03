<<<<<<< HEAD
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/entrance/veriemail.css');?>" />
<h2><span>HFI</span> Campus Express</h2>
<h1><?php echo $headline;?></h1>
<h5>An activation key has been sent to your e-mail address</h5>
<p class="err"><?php if (isset($email_err)) echo $email_err;?></p>
<div class="resend">
<a href="<?=site_url('account/resend/register');?>?uname=<?=$uname?>">Resend e-mail</a>
</div>
=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register Submitted</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/entrance/veriemail.css');?>" />
</head>

<body>
	<h2><span>HFI</span> Campus Express</h2>
	<h1><?php echo $headline;?></h1>
	<h5>An activation key has been sent to your e-mail address</h5>
	<p class="err"><?php if (isset($email_err)) echo $email_err;?></p>
    <div class="resend">
	<a href="<?=site_url('account/resend/register');?>?uname=<?=$uname?>">Resend e-mail</a>
    </div>
</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
