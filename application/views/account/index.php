<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/entrance/login.css');?>"  />
<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
<script>
	if (window!=top)
	{
		top.location.href=location.href
	}
</script>
<script type="text/javascript" src="<?php echo base_url('js/crypt/sha1.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/entrance/Log_In.js');?>"></script>
<h3>&nbsp;<span>HFI</span> Campus Express</h3>
<div class="header">
    <h1>log in</h1>
</div>
<div class="content">
    <form  class="enform" id="login" action="<?php echo site_url('account/login');?>" method="post" >
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
    <p class="link"><a href="<?php echo site_url('account/register');?>" >Register</a></p>
    <br/>
    <p class="link"><a href="<?php echo site_url('account/lost');?>" >Lost</a></p>
</div>