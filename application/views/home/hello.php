<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="/css/entrance/register.css"  />
    <title>Hellow <?php echo $user['uname']?></title>
</head>

<body>
	<?php //var_dump($user);?>
	<?php //var_dump($user_info);?>
    <?php echo validation_errors();?>
	<h1 style="margin-top:50px;">Hello <?php echo $user['uname']?>~</h1>
    <h2 style="margin-top:30px">Your account in HFICAMPUS is almost complete~</br>There are couple information we want to double check~</h2>
    
        <form class="enform" id="update_form" action="<?php echo site_url('home/welcome');?>" method="post">
            <div style="height:auto" class="endiv">
            	<p>If you want to change your username,please fill this field</p>
                <label for="uname">USERNAME</label>
                <input type="text" class="login_box" id="uname" name="uname" value="" />
            </div>
            
            <div style="height:auto" class="endiv pw">
            	<p>Your default password is blank,unless you fill in the following field to change it.Dont make a typo when typing the password, or you want to kick Steven's ass in order to fix itT.T(It was Steven's stupid idea...)</p>
            	<label for="pword">PASSWORD</label>
                <input type="text" class="login_box" name="pw" id="pwf" value="" /></br>
            </div>
                        
            <div style="height:auto" class="endiv">
            	<p>If you want a security email, please fill in the following filed</p>
            	<label for="email">EMAIL</label>
                <input type="text" class="login_box" id="email" name="email" value="<?php echo $user['email']?>" />
            </div>
            
            <div style="height:auto" class="endiv">
            	<p>What's your name?(Maybe Chinese name?)</p>
            	<label for="email">Chinese First Name</label>
                <input type="text" class="login_box" id="cnfn" name="cnfn" value="<?php echo $user_info['cnfn']?>" />
            	<label for="email">Chinese Last Name</label>
                <input type="text" class="login_box" id="cnln" name="cnln" value="<?php echo $user_info['cnln']?>" />
            </div>
            
            <div style="height:auto" class="endiv">
            	<p>If you have an English name</p>
                <input type="text" class="login_box" id="enn" name="enn" value="<?php echo $user_info['cnln']?>" />
            </div>
            
            <input type="submit" style="
            	height:30px;
                width:autho;
            	display:block;
                background-color:rgb(51,0,102);
                border:none;
                font:regular;
                font-size:16px;
                position:absolute;
                right:0;
                color:white;
                
            "value="Submit"/>
         </form>
</body>
</html>