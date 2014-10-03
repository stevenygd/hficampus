<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Channel Test</title>
    
    <script type="text/javascript" src="http://channel.sinaapp.com/api.js"></script>
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script>
		
		var site_url = "<?php echo site_url();?>";
		
		//get channel information
		var channel = new sae.Channel('<?php echo $url;?>');
		var channelId = "<?php echo $channelId;?>";

	</script>
</head>

<body>
	<h2>Chat Room Table</h2>
	<table id="chat_room_table">
    	<tr>
        	<th>ROOM ID</th>
            <th>Members</th>
            <th>Operations</th>
        </tr>
    	<?php foreach ($chatRooms as $item):?>	
        	<tr id="chatroom_list_<?php echo $item['id'];?>">
            	<th><?php echo $item['id'];?></th>
                <th class="members"><?php echo $item['members'];?></th>
                <th>
                	<input type="button" class="chatroom_op" value="Join" />
                </th>
            </tr>
        <?php endforeach;?>
        <tr>
        	<input type="button" id="create_chatroom" value="Create" />
        </tr>
    </table>
    
    <div id="chats">
    </div>
    <script type="text/javascript" src="/js/test/channel.js"></script>

</body>
</html>