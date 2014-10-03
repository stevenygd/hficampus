<<<<<<< HEAD
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/crypt/sha1.js"></script>
<script type="text/javascript" src="/js/crypt/base64.js"></script>
<script type="text/javascript" src="/js/crypt/jsbn.js"></script>
<script type="text/javascript" src="/js/crypt/jsbn2.js"></script>
<script type="text/javascript" src="/js/crypt/prng4.js"></script>
<script type="text/javascript" src="/js/crypt/rng.js"></script>
<script type="text/javascript" src="/js/crypt/rsa.js"></script>
<script type="text/javascript" src="/js/crypt/rsa2.js"></script>
<script>
	var n=b64tohex("<?php echo $n;?>")
</script>
<script type="text/javascript" src="/js/entrance/register.js"></script>
<a href="<?php echo site_url('account/logout');?>" target="_top">Logout</a>
<a href="<?php echo site_url('account/admin');?>">Admin</a>    
<form id="register" action="<?php echo site_url('account/setting/chpw');?>" method="post">
	Password:<input type="password" id="pwf" name="password" value="<?php echo $user["password"] ?>" />
	<input type="submit" id="send" value="change"/>
</form>

<form action="<?php echo site_url('account/setting/chinfo');?>" method="post">
	First Name:<input type="text" name="cnfn" value="<?php echo $user["first_name"] ?>" />
	<input type="submit" value="change" />
</form>

<form action="<?php echo site_url('account/setting/chinfo');?>" method="post">
	Last Name:<input type="text" name="cnln" value="<?php echo $user["last_name"] ?>" />
	<input type="submit" value="change"/>
</form>

<form action="<?php echo site_url('account/setting/chinfo');?>" method="post">
	English Name:<input type="text" name="enn" value="<?php echo $user["english_name"] ?>" />
	<input type="submit" value="change"/>
</form>

<form action="<?php echo site_url('account/setting/chemail');?>" method="post">
	E-mail:<input type="text" name="email" value="<?php echo $user["email"] ?>" />
	<input type="submit" value="change" />
</form>
=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/crypt/sha1.js"></script>
	<script type="text/javascript" src="/js/crypt/base64.js"></script>
    <script type="text/javascript" src="/js/crypt/jsbn.js"></script>
    <script type="text/javascript" src="/js/crypt/jsbn2.js"></script>
    <script type="text/javascript" src="/js/crypt/prng4.js"></script>
    <script type="text/javascript" src="/js/crypt/rng.js"></script>
    <script type="text/javascript" src="/js/crypt/rsa.js"></script>
    <script type="text/javascript" src="/js/crypt/rsa2.js"></script>
    <script>
        var n=b64tohex("<?php echo $n;?>")
    </script>
    <script type="text/javascript" src="/js/entrance/register.js"></script>
    <title>Setting</title>
</head>
<body>
	<a href="<?php echo site_url('account/logout');?>" target="_top">Logout</a>
    <a href="<?php echo site_url('account/admin');?>">Admin</a>    
    <form id="register" action="<?php echo site_url('account/setting/chpw');?>" method="post">
        Password:<input type="password" id="pwf" name="password" value="<?php echo $user["password"] ?>" />
        <input type="submit" id="send" value="change"/>
    </form>
    
    <form action="<?php echo site_url('account/setting/chinfo');?>" method="post">
        First Name:<input type="text" name="cnfn" value="<?php echo $user["first_name"] ?>" />
        <input type="submit" value="change" />
    </form>
    
    <form action="<?php echo site_url('account/setting/chinfo');?>" method="post">
        Last Name:<input type="text" name="cnln" value="<?php echo $user["last_name"] ?>" />
        <input type="submit" value="change"/>
    </form>
    
    <form action="<?php echo site_url('account/setting/chinfo');?>" method="post">
        English Name:<input type="text" name="enn" value="<?php echo $user["english_name"] ?>" />
        <input type="submit" value="change"/>
    </form>
    
    <form action="<?php echo site_url('account/setting/chemail');?>" method="post">
        E-mail:<input type="text" name="email" value="<?php echo $user["email"] ?>" />
        <input type="submit" value="change" />
    </form>

</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
