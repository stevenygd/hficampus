<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="/css/entrance/register.css"  />
    <script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('js/crypt/sha1.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('js/entrance/Log_In.js');?>"></script>
    <script type="text/javascript" src="/js/entrance/Log_In.js"></script>
    <title>Security Check</title>
</head>

<body>
    <?php //echo validation_errors();?>
    <h2 style="margin-top:30px">We need to do a security check!~</h2>
    
        <form class="enform" id="login" action="<?php echo site_url('home/setting/secchk');?>" method="post">
            <div style="height:auto" class="endiv">
                <label for="uname">USERNAME</label>
                <input type="text" disabled="disabled" class="login_box" id="uname" name="uname" value="<?php echo $uname;?>" />
            </div>
            
            <div style="height:auto" class="endiv pw">
            	<label for="pword">PASSWORD</label>
                <input type="password" class="login_box" name="pw" id="password" value="" /></br>
                <input type="hidden" id="login_key" value="<?php echo $key;?>">
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
                
            "value="Submit" id="submit" />
         </form>
</body>
</html>