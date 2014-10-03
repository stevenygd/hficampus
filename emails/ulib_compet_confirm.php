<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>U Library Design Competition Signin Confirmation</title>
    <style>
		#header{
			width:100%;
			padding:0;
			margin:10px;
			height:50px;
			text-align:center;
		}
		
		#header > img{
			height:100%;
			display:inline-block;
			margin:0 auto;
		}
		
        p {
			padding-left:30px;
			font:"Times New Roman", Times, serif;
			font-size:15px;	
		}
		
		.greeting {
			padding-left:0px;
		}
		
		.footer{
			padding-left:0px;
		}
		
		.contact{
			padding-left:0px;
			font-size:13px;
		}
		
		.center{
			text-align:center;
		}
    </style>
</head>

<body class="center">
	<div id="header"><img src="<?php echo base_url('images/email/ulibrary.jpg');?>"/></div>
    <div id=""content>
        <p class="greeting">Dear <?php echo ucfirst(strtolower($info['en']))?></p>
        <p>You have been successfully join the Library Design Competition!</p>
        <p> Please register an account in the following link using your HFI Student ID (<?php echo $item['sid'];?>) to check the competition status:<a href="<?php echo site_url('register');?>"><?php echo site_url('register');?></a>'</p>
        <p>We will keep in touch with you through this email shortly and send you further materials about the library and the competition!</p>
        <p>Thank you for supporting our program!</p>
        <p>Kind regards,</p>
        <p class="footer">HFI U Library Club & HFICampus Team</p>
        <p class="footer"><?php echo date('Y-m-d h:i:s',time());?></p>
        <p class="contact">If you have any questions concerning the competition, please contact us through: hfi_books@126.com or Rowena(189-2884-8217) </p>
        <p class="contact">Technical problem please find: hficampus@sina.cn</p>
    </div>
    
</body>
</html>
