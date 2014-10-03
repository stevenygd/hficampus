<<<<<<< HEAD
=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Setting</title>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
<link rel="stylesheet" type="text/css" href="/css/course/course_setting.css" />
<script type="application/javascript" src="/js/jquery.js"></script>
<script>
	var site_url="<?php echo site_url();?>";
	var base_url="<?php echo base_url();?>";
	var cid="<?php echo $cid;?>";
</script>
<script type="application/javascript" src="/js/course/course_setting.js"></script>
<<<<<<< HEAD
<h1>Setting of <?php echo $course_info[0]['name'];?></h1>

<input type="button" class="title" value="User Management" />
<div id="user" class="hide">
    <div id="user_manage">
        <h4>Current User Management</h4>
        <table>
            <tr>
                <th>User id</th>
                <th>Name</th>
                <th>Operation</th>
            </tr>
            <?php foreach ($sub as $i=>$item):?>
                <tr>
                    <th><?php echo $item['uid'];?></th>
                    <th><?php echo $user_info[$item['uid']]['cnfn'].'&nbsp;'.$user_info[$item['uid']]['cnln'];?></th>
                    
                    <?php if($item['uid']==$uid):?>
                        <th>Owner!</th>
                    <?php else:?>
                        <th>
                            <form action="<?php echo site_url('course/'.$cid.'/edit');?>" method="post">
                                <input type="hidden" name="execution" value="kick" />
                                <input type="hidden" name="gid" value="<?php echo $gid;?>" />
                                <input type="hidden" name="uid" value="<?php echo $item['uid'];?>" />
                                <input type="submit" value="Kick!!!" />
                            </form>
                        </th>
                    <?php endif;?>
                </tr>
            <?php endforeach;?>
        </table>    
    </div>    

    
    <div id="user_invitation">
        <h4>Invitations</h4>
        <ul>
            <?php if(count($waitlist)>0):?>
                <?php foreach($waitlist as $i=>$item):?>
                    <li style="border:solid 1px;">
                        <p><?php echo $user_info[$item['uid']]['cnfn'].'&nbsp;'.$user_info[$item['uid']]['cnln'];?></p>
                        <p><?php echo $item['description']?></p>
                        <form action="<?php echo site_url('course/'.$cid.'/create');?>" method="post">
                            <input type="hidden" name="execution" value="invite" />
                            <input type="hidden" name="gid" value="<?php echo $gid;?>" />
                            <input type="hidden" name="uid" value="<?php echo $item['uid'];?>" />
                            <input type="submit" value="accept" />
                        </form>
                    </li>
                <?php endforeach;?>
            <?php else:?>
                <li>No body wants in yet~</li>
            <?php endif;?>
        </ul>
    </div>
    <div id="add_user">
        <input type="button" id="madd" value="Adds More" />
        <div class="hide">
            <div id="user_search" class="inlineb">
                <input id="search_text" />
                <input type="button" id="search_button" value="Search" />
            </div>
            <div id="user_list" class="inlineb">
                <h3>User List</h3>
                <table>
                    <tr class="head">
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Operation</th>
                    </tr>
                </table>
            </div>
            <div id="user_wait" class="inlineb">
                <h3>Wait List</h3>
                <table>
                    <tr class="head">
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Operation</th>
                    </tr>
                </table>
            </div>
            <input type="button" id="multiadd" class="inlineb" value="Add Submit" />
        </div>
    </div>
</div>
    
<input type="button" class="title pclick" value="Page Management" />
<div id="pages" class="hide">
    <div id="page_current">
        <table>
            <tr class="head">
                <th>Page Id</th>
                <th>Title</th>
                <th>Created Time</th>
                <th>Latest Update</th>
                <th>Operation</th>
            </tr>
            <tr class="padd">
                <a href="<?php echo site_url('course/'.$cid.'/page/0/editor');?>">Add Page</a>
            </tr>
        </table>
    </div>
</div>

<input type="button" class="title qclick" value="Question Management" />
<div id="questions" class="hide">
    <div id="question_current">
        <table>
            <tr class="head">
                <th>Question Id</th>
                <th>Author</th>
                <th>Title</th>
                <th>Content</th>
                <th>Created Time</th>
                <th>Operation</th>
            </tr>
            <tr class="qadd">
                <a href="<?php echo site_url('course/'.$cid.'/question/0/editor');?>">Add Question</a>
            </tr>
        </table>
    </div>
</div>
   
<div id="dangerous">
    <form action="<?php echo site_url('course/'.$cid.'/delete');?>" method="post">
        <input type="hidden" name="cid" value="<?php echo $cid;?>" />
        <input type="submit" id="delcourse" value="DELETE COURSE" />
    </form>
</div>
=======
</head>

<body>
    <h1>Setting of <?php echo $course_info[0]['name'];?></h1>
	
    <input type="button" class="title" value="User Management" />
    <div id="user" class="hide">
        <div id="user_manage">
            <h4>Current User Management</h4>
            <table>
                <tr>
                    <th>User id</th>
                    <th>Name</th>
                    <th>Operation</th>
                </tr>
                <?php foreach ($sub as $i=>$item):?>
                    <tr>
                        <th><?php echo $item['uid'];?></th>
                        <th><?php echo $user_info[$item['uid']]['cnfn'].'&nbsp;'.$user_info[$item['uid']]['cnln'];?></th>
                        
                        <?php if($item['uid']==$uid):?>
                            <th>Owner!</th>
                        <?php else:?>
                            <th>
                                <form action="<?php echo site_url('course/'.$cid.'/edit');?>" method="post">
                                    <input type="hidden" name="execution" value="kick" />
                                    <input type="hidden" name="gid" value="<?php echo $gid;?>" />
                                    <input type="hidden" name="uid" value="<?php echo $item['uid'];?>" />
                                    <input type="submit" value="Kick!!!" />
                                </form>
                            </th>
                        <?php endif;?>
                    </tr>
                <?php endforeach;?>
            </table>    
        </div>    
    
        
        <div id="user_invitation">
            <h4>Invitations</h4>
            <ul>
                <?php if(count($waitlist)>0):?>
                    <?php foreach($waitlist as $i=>$item):?>
                        <li style="border:solid 1px;">
                            <p><?php echo $user_info[$item['uid']]['cnfn'].'&nbsp;'.$user_info[$item['uid']]['cnln'];?></p>
                            <p><?php echo $item['description']?></p>
                            <form action="<?php echo site_url('course/'.$cid.'/create');?>" method="post">
                                <input type="hidden" name="execution" value="invite" />
                                <input type="hidden" name="gid" value="<?php echo $gid;?>" />
                                <input type="hidden" name="uid" value="<?php echo $item['uid'];?>" />
                                <input type="submit" value="accept" />
                            </form>
                        </li>
                    <?php endforeach;?>
                <?php else:?>
                    <li>No body wants in yet~</li>
                <?php endif;?>
            </ul>
        </div>
        <div id="add_user">
            <input type="button" id="madd" value="Adds More" />
            <div class="hide">
                <div id="user_search" class="inlineb">
                    <input id="search_text" />
                    <input type="button" id="search_button" value="Search" />
                </div>
                <div id="user_list" class="inlineb">
                    <h3>User List</h3>
                    <table>
                        <tr class="head">
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Operation</th>
                        </tr>
                    </table>
                </div>
                <div id="user_wait" class="inlineb">
                    <h3>Wait List</h3>
                    <table>
                        <tr class="head">
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Operation</th>
                        </tr>
                    </table>
                </div>
                <input type="button" id="multiadd" class="inlineb" value="Add Submit" />
            </div>
        </div>
    </div>
        
    <input type="button" class="title pclick" value="Page Management" />
    <div id="pages" class="hide">
    	<div id="page_current">
        	<table>
            	<tr class="head">
                	<th>Page Id</th>
                	<th>Title</th>
                	<th>Created Time</th>
                    <th>Latest Update</th>
                    <th>Operation</th>
                </tr>
                <tr class="padd">
                	<a href="<?php echo site_url('course/'.$cid.'/page/0/editor');?>">Add Page</a>
                </tr>
            </table>
        </div>
    </div>
    
    <input type="button" class="title qclick" value="Question Management" />
    <div id="questions" class="hide">
    	<div id="question_current">
        	<table>
            	<tr class="head">
                	<th>Question Id</th>
                    <th>Author</th>
                	<th>Title</th>
                    <th>Content</th>
                	<th>Created Time</th>
                    <th>Operation</th>
                </tr>
                <tr class="qadd">
                	<a href="<?php echo site_url('course/'.$cid.'/question/0/editor');?>">Add Question</a>
                </tr>
            </table>
        </div>
    </div>
       
    <div id="dangerous">
        <form action="<?php echo site_url('course/'.$cid.'/delete');?>" method="post">
            <input type="hidden" name="cid" value="<?php echo $cid;?>" />
            <input type="submit" id="delcourse" value="DELETE COURSE" />
        </form>
    </div>
   	
</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
