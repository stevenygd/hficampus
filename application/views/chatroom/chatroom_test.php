<link rel="stylesheet" href="/css/chatroom/channel.css"/>
<div class="container">
	<div class="row">
        <div id="chatroom-list" class="todo mrm col-sm-4 col-md-4 col-lg-4 col-xs-12">
            <ul>
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
    </div>
</div>

<script type="text/template" id="item-chatroom">
		<% if (type=='group'){%>
			<div class="todo-icon">
				<i class="icon-group"></i>
			</div>
		<%}else{%>
			<div class="todo-icon fui-user"></div>
		<% }%>
	<div class="todo-value">
		<h4 class="todo-name">
			<strong class="chatroom_name"><%= name %></strong>
			<span class="navbar-unread white"></span>
		</h4>
		<div class="last_msg">
			[<%= last_msg.enn%> <%= last_msg.cnln %>] <%= last_msg.content %>
		</div>
		<input type="hidden" id="chatId" value="<%=id%>"/>
	</div>
</script>

<script type="text/template" id="item-msg">
	<% if (speaker == uid){%>
		<div class="msg-info"><%= created_time %></div>
		<a href="#" class="button button-rounded button-flat-caution hidden delete-msg" style="color:white;"><i class="icon-trash"></i>Delete</a>
		<div class="tooltip fade left in">
			<div class="tooltip-arrow"></div>
			<div class="tooltip-inner"><%= content%></div>
		</div>
	<%}else{%>
		<div class="msg-info"><%= enn %> <%= cnln%> <%= created_time %></div>
		<div class="tooltip fade right in">
			<div class="tooltip-arrow"></div>
			<div class="tooltip-inner"><%= content%></div>
		</div>
	<%}%>

</script>
<script src="/js/require.js" data-main="/js/main" type="text/javascript"></script>
