<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="/css/ulibrary/ulwelcome.css" />
</head>

<body>
	<div>
    	<p>Thanks you for supporting our program!</p>
        <p>We have received the following registeration information from you.</p>
        <p>If you have any questions, please contact: hfi_books@126.com or hficampus@sina.cn. Thank you!</p>
    </div>
    <div class="group">
        <p><span>ID:</span> <?php echo $compet_info['id'];?></p>
        <p><span>Leader SID:</span> <?php echo $compet_info['leader'];?></p>
        <p><span>Group Contact Email:</span> <?php echo $compet_info['email'];?></p>
        <p><span>Register Time:</span> <?php echo $compet_info['time'];?></p>
        <p><span>Register Type:</span> <?php if ($compet_info['teammates']=='N/A') echo 'Individual'; else echo 'Team';?></p>
    </div>
	<div class="member">
    	<table>
        	<tr>
            	<th><span>Member SID</span></th>
                <th><span>email</span></th>
            	<th><span>English Name</span></th>
            	<th><span>First Name</span></th>
            	<th><span>Last Name</span></th>
            	<th><span>Message</span></th>
                <th><span>Code</span></th>
            	<th><span>Register</span></th>
                <th><span>Class</span></th>
            </tr>
			<?php $members=$compet_info['members'];?>
            <?php foreach($members as $jtem):?>
            <tr>

                <th><?php echo $jtem['sid'];?></th>
                <th><?php echo $jtem['email'];?></th>
                <?php $info=$jtem['info'];?>
                <th><?php echo $info['en'];?></th>
                <th><?php echo $info['fn'];?></th>
                <th><?php echo $info['ln'];?></th>
                <th><?php echo $info['msg'];?></th>
                <th><?php echo $info['code'];?></th>
                <th><?php echo $info['regi'];?></th>
                <th><?php echo $info['class'];?></th>
                <?php if($jtem['sid']==$compet_info['leader']):?>
                    <th>Leader</th>
                <?php endif;?>
            </tr>
            <?php endforeach;?>
        </table>
    </div>
</body>
</html>