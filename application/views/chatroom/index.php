<link rel="stylesheet" href="/css/chatroom/channel.css"/>
<div class="container">
    <div class="row">
        <div id="chatroom-list" class="todo mrm col-sm-4 col-md-4 col-lg-4 col-xs-12">
            <ul>
            	<?php if (isset($chatrooms)):?>
					<?php $first = reset($chatrooms);?>
                    <?php foreach ($chatrooms as $i => $item):?>
                            <?php if ($item['type'] == 'group'):?>
                                <li class="chatroom <?php if ($i == $first)echo "todo-done";?>" id="chatroom_<?php echo $item['id'];?>">
                                    <div class="todo-icon"><i class="icon-group"></i></div>
                                    <div class="todo-value">
                                        <h4 class="todo-name">
                                            <strong class="chatroom_name"><?php echo $item['name'];?></strong>
                                            <span class="navbar-unread white"></span>
                                        </h4>
                                        <?php $last_msg = $item['last_msg'];?>
                                        <div class="last_msg">
                                            <?php if ($last_msg !== FALSE):?>
                                            <?php echo '[' . $last_msg['cnfn'] . ' '. $last_msg['cnln']. ' ]' .$last_msg['content'];?>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </li>
                            <?php else:?>
                                <li class="chatroom <?php if ($i == $first)echo "todo-done";?>" id="chatroom_<?php echo $item['id'];?>">
                                    <div class="todo-icon fui-user"></div>
                                    <div class="todo-value">
                                        <h4 class="todo-name">
                                            <strong class="chatroom_name"><?php echo $item['name'];?></strong>
                                            <span class="navbar-unread white"></span>
                                        </h4>
                                        <?php $last_msg = $item['last_msg'];?>
                                        <div class="last_msg">
                                            <?php if ($last_msg !== FALSE):?>
                                            <?php echo '[' . $last_msg['cnfn'] . ' '. $last_msg['cnln']. ' ]' .$last_msg['content'];?>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </li>
                            <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>
                <li>
                    <div class="delete-button"><i class="icon-minus"></i></div>
                    <a href="#" class="button button-rounded button-flat-caution hidden delete-chatroom" style="color:white;"><i class="icon-trash"></i>Delete or Quit the Selected Chatroom</a>
                    <div class="create-button"><i class="icon-plus"></i></div>
                </li>
            </ul>
        </div>
        
        <div id="chatroom-msg" class="col-sm-4 col-md-4 col-lg-4 hidden-xs hidden">
			<div>
                <ul class="pager">
                    <li>
                        <a href="#" class="back" style="float: left;"><i class="icon-angle-left"></i>Back</a>
                    </li>
                    <li id="chatroom_name">
                    	<a href="#" class="user-name" style="border-left:none;"><i class="icon-user"></i></a>
                    </li>
                    <li>
                    	<a href="#" id="show_info" style="float: right;"><i class="icon-info-sign"></i>Info</a>
                    </li>
                </ul>
            </div>
            <div id="conver">
            </div>
            <div id="send-msg">
            	<textarea id="content" class="form-control" rows="3"></textarea>
                <a id="send" class="button glow button-rounded button-flat"><i class="icon-comments-alt"></i>send</a>
            </div>
            <input type="hidden" id="chatroom-id" value="" />
        </div>
            
        <div id="create-chatroom" class="col-sm-4 col-md-4 col-lg-4 hidden-xs hidden">
              <form class="form-horizontal login-form">
                <fieldset>
                  <a href="#" class="back" style=""><i class="icon-angle-left"></i>Back</a>
                  <div id="legend" class="">
                    <legend class="">New Chatroom</legend>
                  </div>
                <div class="control-group">
            
                      <!-- Text input-->
                      <label class="control-label" for="input01">Name</label>
                      <div class="controls">
                        <input id="new_chatroom_name" type="text" placeholder="name" class="input-xlarge form-control">
                        <p class="help-block">Chatroom's name</p>
                      </div>
                    </div>
            
                <div class="control-group">
            
                      <!-- Text input-->
                      <label class="control-label" for="input01">Topic</label>
                      <div class="controls">
                        <input id="new_chatroom_topics" type="text" placeholder="topic" class="input-xlarge form-control">
                        <p class="help-block">Topic of conversations</p>
                      </div>
                    </div>
                    
                <div class="control-group type-field">
            
                      <!-- Text input-->
                      <label class="control-label" for="input01">Type</label>
                      <div class="controls">
                        <div class="toggle toggle-off">
                          <label class="toggle-radio" for="toggleOption2"><i class="icon-group"></i>Group</label>
                          <input type="radio" name="new_chatroom_type" id="toggleOption1" value="group" >
                          <label class="toggle-radio" for="toggleOption1"><i class="icon-user"></i>Single</label>
                          <input type="radio" name="new_chatroom_type" id="toggleOption2" value="single" checked="checked">
                        </div>
                        <p class="help-block">Single or Group</p>
                      </div>
                    </div>
                
                <div class="control-group capacity-field hidden">
            
                      <!-- Text input-->
                      <label class="control-label" for="input01">Capacity</label>
                      <div class="controls">
                        <input id="new_chatroom_capacity" type="text" placeholder="capacity" class="input-xlarge form-control" value="">
                        <p class="help-block">Number of people allowed (3~100)</p>
                      </div>
                    </div>
                
                <div class="control-group member-field">
                	<label class="control-label" for="input01">Members</label>
                    <div class="controls">
                    	
                    </div>
                </div>

                <div class="control-group">
                      <label class="control-label"></label>
                      <!-- Button -->
                      <div class="controls">
                        <a href="#" id="add-member" class="button glow button-rounded button-flat-primary" style="color: white;">Add Member</a>
                        <a href="#" id="create_button" class="button glow button-rounded button-flat-action" style="color: white;">Create</a>
                        <a href="#" id="update_button" class="button glow button-rounded button-flat-action hidden" style="color: white;">Update</a>
                      </div>
                    </div>
            
                </fieldset>
              </form>
        </div>
        
        <div id="search" class="todo mrm col-sm-4 col-md-4 col-lg-4 hidden-xs hidden">
            <a href="#" class="search_back" style=""><i class="icon-angle-left"></i>Back</a>
            <div class="todo-search">
                <input id="search_input" type="search" class="todo-search-field form-control" placeholder="Search"/>
            </div>
            <ul>
                <!--<li class>
                    <div class="todo-icon"><i class="icon-group"></i></div>
                    <div class="todo-value">
                        <h4 class="todo-name">
                            <strong>Steven</strong>
                            Yang
                        </h4>
                            Student
                    </div>
                </li>-->
            </ul>
        </div>
    </div>
</div>
<script type="application/javascript" src="/js/chatroom/index.js"></script>
<script>
	// save chatroom information
	var chatrooms_php = <?php echo json_encode($chatrooms);?>;
	/*
	for (x in chatrooms_php){
		chatroom_create(chatrooms_php[x]);
	}
	*/
	var current_chatId = Number("<?php echo isset($chatId)?$chatId:$first;?>");
	var current_chatroom={"messages":null,"info":null,"members":null};

</script>
