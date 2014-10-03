<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/entrance/register.css');?>"  />
<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/crypt/sha1.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/crypt/base64.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/crypt/jsbn.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/crypt/jsbn2.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/crypt/prng4.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/crypt/rng.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/crypt/rsa.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/crypt/rsa2.js');?>"></script>
<script>
    var n=b64tohex("<?php echo $n;?>")
</script>
<script type="text/javascript" src="<?php echo base_url('js/entrance/register.js');?>"></script>
<h2><span>HFI</span> Campus Express</h2>
<h1>Register</h1>
<h2 class="" id="sub">Confirm Informaiton</h2>
<form class="enform" id="register" action="<?php echo site_url('account/regi');?>" method="post">
    <div class="endiv">
        <label for="uname">USERNAME</label>
        <input type="text" class="login_box" id="uname" name="uname" value="<?php echo set_value('uname');?>" /></br>
        <p class="err hide" id="unerr">Username needs more than 5 characters</p>
        <div class="err">
            <?php echo form_error('uname');?>
        </div>
    </div>
    <div class="endiv pw">
          <label for="pword">PASSWORD</label>
        <input type="password" class="login_box" name="pw" id="pwf" value="" /></br>
    </div>
    <div class="endiv pw">
        <label for="pword_conf">CONFIRM PASSWORD</label>
        <input type="password" class="login_box" name="pword_conf" id="pwc" value="" /></br>
        <p class="err hide" id="pwerr">Inconsistency in passwords</p>
    </div>
    <div class="endiv">
        <label for="email">EMAIL</label>
        <input type="text" class="login_box" id="email" name="email" value="<?php echo set_value('email');?>" /></br>
        <div class="err">
            <p><?php echo form_error('email');?></p>
        </div>
    </div>
    <div class="endiv">
        <label for="cngname">GIVEN NAME</label>
        <input type="text" class="login_box" id="cngname" name="cngname" value="<?php echo set_value('cngname');?>" /></br>
        <div class="err">
            <p><?php echo form_error('cngname');?></p>
        </div>
    </div>
    <div class="endiv">
        <label for="cnfname">FAMILY NAME</label>
        <input type="text" class="login_box" id="cnfname" name="cnfname" value="<?php echo set_value('cnfname');?>" /></br>
        <div class="err">
            <p><?php echo form_error('cnfname');?></p>
        </div>
    </div>
    <div class="endiv">
        <label for="y">Student ID</label>
        <input type="text" class="login_box" id="id" name="id" value="<?php echo set_value('id');?>"></br>
        <div class="err">
            <p><?php echo form_error('id');?></p>
        </div>
    </div>
    <div class="endiv next" id="info">
        <p>
            You will receive a confirmation email containing an activation link soon
            after you submit this form. Your accont cannot be accessed until
            activated. Please notice that only one account can be created by each student.
            Be sure to verify your identity information before submission.
        </p>
    </div>
    <a href="<?php echo site_url('account');?>"><input type="button" class="ensubmit first" id="back1" value="Back" /></a>
    <input type="button" class="ensubmit next" id="back2" value="Back"  />
    <input type="button" class="ensubmit first" id="next" value="Next"  />
    <input type="submit" class="ensubmit next" id="send" value="Submit" />
</form>
<div class="err">
    <!--<p><?php echo validation_errors();?></p>-->
    <p><?php if (isset($err_code)) echo $err_code;?></p>
    <p><?php if (isset($err_msg)) echo $err_msg;?></p>
</div>
<p style="color:black"><?php if (isset($email_debug)) print $email_debug;?></p>