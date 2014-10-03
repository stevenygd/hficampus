<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Receive Password</title>
<link href="/css/entrance/login.css" type="text/css"></head>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/entrance/register.js"></script>
</head>

<body class="center">
	<h6>找回密码</h6>
    <form  class="enform" action="<?=site_url('entrance/receivepw')?>" method="post">
        <div class="endiv">
            <label>UNAME</label>
    		<input type="text" name="uname" value="ygd1995" /></br>
        </div>
        <div class="endiv">
            <label>EMAIL</label>
        	<input type="text" name="email" value="476839010@qq.com"></br>
        </div>
        <input type="submit" value="SUBMIT" />
    </form>
    <div id="err">
		<p class="err_code"><?php if( isset($err_code)) echo $err_code; ?></p>
		<p class="err_msg"><?php if( isset($err_msg)) echo $err_msg; ?></p>
	</div>
</body>
</html>