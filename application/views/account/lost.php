<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lost</title>
<link rel="stylesheet" type="text/css" href="/css/entrance/lost.css"  />
<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
<script type="text/javascript" src="/js/entrance/lost.js"></script>
</head>
<body>
	<h2><span>HFI</span> Campus Express</h2>
	<div>
		<form class="enform" action="<?php echo site_url('account/lost_name');?>" method="post">
			<div class="endiv">	
                <h3>Forgot username</h3>
           </div>
            <div class="endiv">
           	<label>GIVEN NAME</label>
				<input type="text" name="cngname" />
           </div>
           <div class="endiv">
           	<label>FAMILY NAME</label>
				<input type="text" name="cnfname" />
           </div>
			<div class="endiv">
           	<label>Student ID</label>
				<input type="text" name="id" />
           </div>
    		<input type="submit" class="ensubmit" name="submit" value="Submit"  />
		</form>
    <?php if(isset($lost_name)) echo $lost_name['err_msg'].'</br>',$lost_name['err_code'];?>
	</div>
	<div>
		<form class="enform" action="<?php echo site_url('account/lost_pw');?>" method="post">
			<div class="endiv">
            	<h3>Forgot password</h3>
           </div>
            <div class="endiv">
               <label>USERNAME</label>
                <input type="text" name="uname" />
           </div>
           <div class="endiv">
                <label>Student ID</label>
                <input type="text" name="id" />
           </div>
            <input type="submit" class="ensubmit" value="Submit"/>
            <a href="<?php echo site_url('account');?>"><input type="button" class="ensubmit" id="back" value="Back" /></a>
		</form>
    <?php if(isset($lost_pw)) echo $lost_pw['err_msg'].'</br>',$lost_pw['err_code'];?>
	</div>
    </br>
</body>
</html>