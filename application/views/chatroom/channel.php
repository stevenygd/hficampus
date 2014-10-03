<h2>Chat Room Table</h2>
<div>
    <table id="chat_room_table">
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>TOPICS</th>
            <th>TYPES</th>
            <th>CREATED TIME</th>
            <th>CREATOR ID</th>
            <th>EXPIRE TIME</th>
            <th>OPERATION</th>
        </tr>
        <?php foreach ($chatrooms as $item):?>	
            <tr id="chatroom_list_<?php echo $item['id'];?>">
                <th class="chatId"><?php echo $item['id'];?></th>
                <th><?php echo $item['name'];?></th>
                <th><?php echo $item['topics'];?></th>
                <th><?php echo $item['type'];?></th>
                <th><?php echo $item['created_time'];?></th>
                <th><?php echo $item['creator'];?></th>
                <th><?php echo $item['expire_time'];?></th>
                <th>
                    <input type="button" class="chatroom_op" value="Enter" />
                    <?php if ($item['creator'] == $uid):?>
                        <input type="button" class="chatroom_op" value="Delete" />
                    <?php endif;?>
                    <div>
                    	<input type="hidden" value="<?php echo $item['id'];?>" />
                    	<input class="search" />
                    </div>
                </th>
            </tr>
        <?php endforeach;?>
        <tr>
            <p>Name:<input id="chatroom_name" /></p>
            <p>Topics: <input id="chatroom_topics" /></p>
            <p>
                Chatroom Type:
                <select id="chatroom_type">
                  <option value ="single">Single</option>
                  <option value ="group">Group</option>
                </select>
            </p>
            <p>
                Chatroom Capacity:
                <select id="chatroom_capacity">
                  <option value ="50">50</option>
                  <option value ="100">100</option>
                </select>
            </p>
            <input type="button" id="create_chatroom" value="Create" />
        </tr>
    </table>
</div>

<div id="chats">
</div>
<script type="text/javascript" src="/js/test/channel.js"></script>