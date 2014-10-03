<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/entrance/veriemail.css');?>" />
<h2><span>HFI</span> Campus Express</h2>
<h1><?php echo $headline;?></h1>
<h5>An activation key has been sent to your e-mail address</h5>
<p class="err"><?php if (isset($email_err)) echo $email_err;?></p>
<div class="resend">
<a href="<?=site_url('account/resend/register');?>?uname=<?=$uname?>">Resend e-mail</a>
</div>
