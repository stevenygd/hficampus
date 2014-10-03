<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Count Down</title>
    <script type="application/javascript" src="/js/jquery.js"></script>
    <script>
		var deadline = new Date("January 16, 2014 00:00:00");
		setInterval(function(){
			present  = new Date();
			diff =deadline.getTime()-present.getTime();
			if (diff < 0){
				window.location.reload();
			}else{
				secs  = Math.floor(diff/1000);
				hours = Math.floor(secs/3600);
				if (hours<10){
					s_h = '0'+hours.toString();
				}else{
					s_h = hours.toString();
				}
				minutes = Math.floor((secs-hours*3600)/60);
				if (minutes<10){
					s_m = '0'+minutes.toString();
				}else{
					s_m = minutes.toString();
				}
				seconds = Math.floor((secs-hours*3600-minutes*60));
				if (seconds<10){
					s_s = '0'+seconds.toString();
				}else{
					s_s = seconds.toString();
				}
				
				$("#clock").html(s_h+':'+s_m+':'+s_s);
			}
		},1000);
    </script>
	<link rel="stylesheet" href="/css/ulibrary/ulverified.css" type="text/css" />
</head>

<body>
	<h1>Available Soon!</h1>
	<h1 id="clock"></h1>
    <div class="resend">
        <div>
        	<a class="link" href="hficampus.sinaapp.com">To Login Page</a>
        </div>
        <div>
	        <a class="link" href="hficampus.sinaapp.com/account/register">Register</a>
        </div>
    </div>
</body>
</html>