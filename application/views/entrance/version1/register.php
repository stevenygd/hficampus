<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="/css/entrance/login.css"  />
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
        var n=b64tohex("<?=$n?>")
    </script>
    <script type="text/javascript" src="/js/entrance/register.js"></script>
</head>
<body class="center">
		<h1>Register</h1>
        <form class="enform" id="register" action="<?php echo site_url('entrance/regi');?>" method="post" onsubmit="a()">
            <div class="endiv">
                <label for="uname">UNAME</label>
                <input type="text" class="login_box" id="uname" name="uname" value="<?php echo set_value('uname');?>" /></br>
                <div class="enerr">
            		<p><?php echo form_error('uname');?></p>
                </div>
            </div>
            <div class="endiv">
            <label for="pword">PWORD</label>
                <input type="password" class="login_box" id="pword" name="pword" id="pwf" value="" /></br>
            </div>
            <div class="endiv">
                <label for="pword_conf">PWORD CONFIRM</label>
                <input type="password" class="login_box" id="pword_conf" name="pword_conf" id="pwc" value="" /></br>
            </div>
            <div class="endiv">
            	<label for="email">EMAIL</label>
                <input type="text" class="login_box" id="email" name="email" value="<?php echo set_value('email');?>" /></br>
                <div class="enerr">
            		<p><?php echo form_error('email');?></p>
                </div>
            </div>
            <div class="endiv">
                <label for="cngname">GIVEN NAME</label>
                <input type="text" class="login_box" id="cngname" name="cngname" value="<?php echo set_value('cngname');?>" /></br>
                <div class="enerr">
            		<p><?php echo form_error('cngname');?></p>
                </div>
            </div>
            <div class="endiv">
                <label for="cnfname">FAMILY NAME</label>
                <input type="text" class="login_box" id="cnfname" name="cnfname" value="<?php echo set_value('cnfname');?>" /></br>
                <div class="enerr">
            		<p><?php echo form_error('cnfname');?></p>
                </div>
            </div>
            <div class="endiv">
                <label for="y">YEAR</label>
                <input type="text" class="login_box" id="y" name="y" value="<?php echo set_value('y');?>"></br>
                <div class="enerr">
            		<p><?php echo form_error('y');?></p>
                </div>
            </div>
            <input type="submit" class="ensubmit" id="submit" value="" />
        </form>
        <div class="enerr">
            <!--<p><?php echo validation_errors();?></p>-->
            <p><?php if (isset($err_code)) echo $err_code;?></p>
            <p><?php if (isset($err_msg)) echo $err_msg;?></p>
        </div>
        <h6>HFI</h6>
        <p style="color:black"><?php if (isset($email_debug)) print $email_debug;?></p>
</body>
</html>