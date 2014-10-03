<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>test</title>
        <link rel="stylesheet" type="text/css" href="/css/test/auth_admin.css"  />
        <script type="application/javascript" src="/js/jquery.js"></script>
        <script type="application/javascript">
			var site_url="<?php echo site_url();?>";
        </script>
        <script type="application/javascript" src="/js/test/auth_admin.js"></script>
    </head>
    
    <body>
        <form id="add_auth" action="<?php echo site_url('test/group/edit');?>" method="get">
        	<p>Add authority to some group</p>
        	<input type="hidden" name="check" value="group" />
        	<label>GID</label>
        	<input name="gid" value="1" >
        	<label>MTYPE</label>
        	<input name="mtype" value="all" >
            <label>MID</label>
        	<input name="mid" value="0" >
        	<label>NTYPE</label>
        	<input name="ntype" value="all" >
            <label>NID</label>
        	<input name="nid" value="0" >            
        	<label>AUTH</label>
        	<input name="auth" value="lcurd" >            
            <input type="button" id="add_auth_to_group" value="group" />
        </form>
        <!--h1>PERMI:<?php echo var_dump($permi);?></h1>
        <p>SUB:<?php var_dump($sub);?></p>
        <p>GROUP:<?php var_dump($group);?></p>
        <p><?php var_dump($error);?></p-->
        <table>
        	<tr>
            	<th>Group Id</th>
            	<th>Group Name</th>
            	<th>Group Owner Id</th>
            	<th>Group Owner</th>
                <th>Group Members</th>
                <th>Permission</th>
            </tr>
            <?php foreach($group as $i=>$item):?>
            	<tr id="g<?php echo $item['id'];?>" class="group_row">
                	<th class="group_id"><?php echo $item['id'];?></th>
                	<th class="group_name"><?php echo $item['name'];?></th>
                	<th class="group_owner"><?php echo $item['auth'];?></th>
                	<th class="group_owner_name"><?php echo $item['cnfn'].' '.$item['cnln'].'('.$item['enn'].')';?></th>
                    <th class="group_members">
                    	<p class="inline num"><?php echo count($sub[$item['id']]);?></p>
                        <input type="button" class="switch_next" value="Show" />
                        	<table class="hide group_member_list">
                            	<tr>
                                	<th>User Id</th>
                                    <th>User Name</th>
                                    <th>Operation</th>
                                </tr>
								<?php foreach($sub[$item['id']] as $j=>$jtem):?>
                                    <tr>
                                    	<th><?php echo $jtem['uid'];?></th>
                                    	<th><?php echo $jtem['cnfn'].' '.$jtem['cnln'].'('.$jtem['enn'].')';?></th>
                                        <th>
                                        	<input type="hidden" name="gid" value="<?php echo $item['id'];?>" />
                                        	<input type="hidden" name="uid" value="<?php echo $jtem['uid'];?>" />
                                        	<input type="button" class="kickbutton" value="kick!" />
                                        </th>
                                    </tr>
                                <?php endforeach;?>
                                <tr>
                                	<th></th>
                                    <th></th>
                                	<th>
                                    <input type="hidden" name="gid" value="<?php echo $item['id']?>" />
                                    <input type="text" class="auser_search_text" name="uid" value="" />
                                    <input type="button" class="add membe" value="add" />
                                    <table>
                                        <tr class="head">
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Operation</th>
                                        </tr>
                                    </table>
                                    </th>
                                </tr>
                            </table>
                    </th>
                    <th class="group_permi">
                    	<p class="inline num">
							<?php echo count($permi[$item['id']]);?>
                        </p>
						<input type="button" class="switch_next" value="Show" />
                    	<ul class="hide">
                        	<?php foreach($permi[$item['id']] as $j=>$jtem):?>
                            	<li>
                                    <span class="auth_content"><?php echo 'url:'.$jtem['mtype'].'/'.$jtem['mid'].'/'.$jtem['ntype'].'/'.$jtem['nid'].':'.$jtem['auth'];?></span>
                                    <input type="button" class="switch_next" value="Show" />
                                    <div class="hide">
                                    	<input type="hidden" name="gid" value="<?php echo $jtem['gid'];?>" />
                                    	<input type="hidden" name="mtype" value="<?php echo $jtem['mtype']?>" />
                                    	<input type="hidden" name="mid" value="<?php echo $jtem['mid']?>" />
                                    	<input type="hidden" name="ntype" value="<?php echo $jtem['ntype']?>" />
                                    	<input type="hidden" name="nid" value="<?php echo $jtem['nid']?>" />
                                    	<input type="text" name="auth" value="<?php echo $jtem['auth']?>" />
                                        <input type="button" class="chauth" value="Change Permission" />
                                        <input type="button" class="delauth" value="Delete Permission" />
                                    </div>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </th>
                </tr>
            <?php endforeach;?>
        </table>
    </body>
</html>