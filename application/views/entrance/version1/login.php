<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<link rel="stylesheet" type="text/css" href="/css/entrance/login.css"  />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/crypt/sha1.js"></script>
<script type="text/javascript" src="/js/entrance/Log_In.js"></script>
</head>
<body class="center">
    <h1>log in</h1>
    <form  class="enform" id="login" action="<?php echo site_url('entrance/login');?>" method="post" >
        <div class="endiv">
            <label class="" for="username" >username</label>
            <input type="text" class="login_box" name="uname" id="username" value="<?php echo set_value('uname');?>"/>
        </div>
        <div class="endiv">
            <label class="" for="password" >password</label>
            <input type="password" class="login_box" name="pw" id="password" />
        </div>
        <input type="submit" class="ensubmit" id="submit" value=""/>
        <input type="hidden" id="login_key" value="<?php echo $login_key;?>"  />
    </form>
    <div class="enerr">
        <p class="err_code"><?php if( isset($err_code)) echo $err_code; ?></p>
        <p class="err_msg"><?php if( isset($err_msg)) echo $err_msg; ?></p>
        <p class="err_msg"><?php echo validation_errors();?></p>
    </div>
    <p><a href="<?php echo site_url('entrance/register');?>" >Register</a></p>
    <p><a href="<?php echo site_url('entrance/receive');?>" >Forget Password</a></p>
    <h6>HFI</h6>
</body>
</html>