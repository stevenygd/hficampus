// JavaScript Document
//CHATROOM index.js by Steven, Ryan
channel.onmessage = function(output){
	if (typeof(onmessage) == 'function'){
		onmessage(output);
	}
};
function onmessage(output){
	data = eval('('+output.data+')');
	if (data.ntype === 'msg' ){
		speaker = {'uid':data.speaker.user.id,'cnfn':data.speaker.user_info.cnfn,'cnln':data.speaker.user_info.cnln,'enn':data.speaker.user_info.enn};
		//add message
		$("#conver").append(htmlmark(data.messageId,data.content,speaker,data.created_time));
		//clear sending
		$("#content").val("");
		$("#content").removeAttr("disabled");
		$("#send").html('<i class="icon-comments-alt"></i>send');
		$("#conver").scrollTop(1024*$("#conver").height());
		//insert into the chatroom show list
		last_msg = '[' + data.speaker.user_info.cnfn + ' ' + data.speaker.user_info.cnln + ']' +data.content;
		$("#chatroom_"+data.chatId).find(".last_msg").html(last_msg);
		
	}else{
		//chatroom operation
		
	}
};


$(document).ready(function(){
	
	/*Chatroom Functions*/
	//enter the chatroom
	$(".chatroom").live("click",function(e){
		//get chatId
		hideButton();
		$(".delete-button").removeClass("hidden");
		chatId = Number($(this).attr("id").slice(9));
		switch_chatroom(chatId);
	});
	
	
	$(".create-button").click(function(){
		//clear the original infomation
		hideButton();
		$(".delete-button").removeClass("hidden");
		$("#update_button").addClass("hidden");
		$("#create_button").removeClass("hidden");
		info_holder(true);
		prerender();
		render("#create-chatroom");
	});
			
	//create chatroom
	$("#create_button").click(function(e){
		e.preventDefault();
		//create chatroom, ajax to the server
		//fetch data
		name =     $("#new_chatroom_name").val();
		type =     $('input[name="new_chatroom_type"]:checked').val();//@todo
		topics =   $("#new_chatroom_topics").val();

		if (type=="single")
		{
			capacity=2;
		}
		else
		{
			capacity = $("#new_chatroom_capacity").val();
		}
		d ={'name':name,'type':type,'topics':topics,'capacity':capacity};
		url = site_url + 'chatrooms/create';
		
		//get chatroom info
		$.post(url,d,function(data,status){
			if (status == 'success' && data.code == 0){
				//get chatid
				chatId =data.chatroom_id;
									
				//create a chatting room
				if (type == 'group'){
					htmlmarks = '<li class="chatroom todo-done" id="chatroom_'+chatId+'">'+
									'<div class="todo-icon"><i class="icon-group"></i></div>'+
									'<div class="todo-value">'+
										'<h4 class="todo-name">'+
											'<strong class="chatroom_name">'+name+'</strong>'+
											'<span class="navbar-unread">?</span>'+
										'</h4>'+
										'<div class="last_msg"></div>'+
									'</div>'+
								'</li>';
				}else if (type == 'single'){
					htmlmarks = '<li class="chatroom" id="chatroom_'+chatId+'">'+
                                	'<div class="todo-icon fui-user"></div>'+
									'<div class="todo-value">'+
										'<h4 class="todo-name">'+
											'<strong class="chatroom_name">'+name+'</strong>'+
											'<span class="navbar-unread">?</span>'+
										'</h4>'+
										'<div class="last_msg"></div>'+
									'</div>'+
								'</li>';
				}
				$("#chatroom-list").find("li").last().before(htmlmarks);
				add_member(chatId);
				//create chatroom info space@todo
				
				//prepare chatting@todo
				
			}else{
				alert(status+data.message);
			}
		});
	});
	
	
	//add a member into chatroom@todo
	$("#add-member").click(function(){
		render("#search");
	});
	
	$(".search_user").live("click",function(){
		hideButton();
		$(this).find(".add-button").removeClass("hidden");
		$(this).find(".add-button").addClass("clicked");
	});
	
	$(".add-button").live("click",function(){
		member_list = [];
		$(".member").each(function(){
			member_list[member_list.length] = $(this).find(".member-id").val();
		});
		member_info = {
				"enn":$(this).parent().find(".search-enn").val(),
				"cnln":$(this).parent().find(".search-cnln").val(),
				"uid":$(this).parent().find(".search-id").val()
			  }
		count = 0;
		for (x in member_list)
		{
			if (member_list[x]==member_info.uid)
			{
				alert('member already exists');
				count = 1;
				break;	
			}
		}
		if (count == 0)
		{
			append_member = htmlmark_member(member_info);
			$("#create-chatroom").find(".member-field").find(".controls").append(append_member);
		}
		$(".member-field").removeClass("hidden");
		render("#create-chatroom");
	});
	//kick a member from the chatroom
	$(".member-field").delegate(".member-name","click", function(){
		hideButton();
		$(this).parent().find(".delete-member").removeClass("hidden");
		$(this).parent().find(".delete-member").addClass("clicked");
	});
	
	$(".delete-member").live("click",function(){
		element=$(this).parent();
		chatId = Number($(".todo-done").attr("id").slice(9));
		uid = element.find(".member-id").val();	
		url = site_url+'/chatrooms/'+chatId+'/edit';
		$.post(url,
		{
			uid: uid,
			operation: "kick",
		},
		function(data, status){
			if (status == 'success' && data.code == 0){
				alert("success");
				element.detach();
			}
			else
			{
				alert(status+data.message);
			}
		});
	});
	
	//delete chatroom
	$(".delete-button").click(function(){
		$(this).addClass("hidden");
		$(".delete-chatroom").removeClass("hidden");
		$(".delete-chatroom").addClass("clicked");
	});
	
	$(".delete-chatroom").click(function(){
		chatId = Number($(".todo-done").attr("id").slice(9));
		url = site_url+'/chatrooms/'+chatId+'/delete';
		$.get(url,{},function(data,status){
			if (status == 'success' && data.code == 0){
				alert("success");
				$("#chatroom_"+chatId).detach();
				$("#chatroom-msg").addClass("hidden");
			}
			else
			{
				alert(status+data.message);	
			}
		});
		hideButton();
		$(".delete-button").removeClass("hidden");
	});
	
	//back button
	$(".back").click(function(){
		render("#chatroom-list");
	});
	
	//hide delete button
	$("#content").focus(function(){
		hideButton();
	});
	
	//send message
	$("#send").click(function(e){
		e.preventDefault();
		chatId = current_chatId;
		content = $("#content").val();
		//content operations
		$("#content").attr("disabled","disabled");
		$("#send").html('<i class="icon-spinner icon-spin"></i>sending');
		
		d = {'content':content};
		url = site_url + 'chatrooms/'+chatId+'/msg/create';
		$.post(url,d,function(data,status){
			if (status != 'success' || data.code != 0){
				alert(status+data);
			}else{
				msgId = data.messageId;
				var current = new Date();
				var timeString = current.getFullYear()+'-'+(current.getMonth()+1)+'-'+current.getDate()+' '+current.getHours()+':'+current.getMinutes()+':'+current.getSeconds();
				$("#conver").append(htmlmark(data.messageId,content,false,timeString),function(){
					$("#conver").scrollTop(1024*$("#conver").height());
				});
				$("#content").val("");
				$("#content").removeAttr("disabled");
				$("#send").html('<i class="icon-comments-alt"></i>send');
			}
		});
	});
	
	//render delete-msg button
	$(".send").live("click",function(){
		hideButton();
		$(this).find(".delete-msg").removeClass("hidden");
		$(this).find(".delete-msg").addClass("clicked");
	});
	
	//delete-msg
	$(".delete-msg").live("click",function(){
		chatId=$("#chatroom-id").val();
		element=$(this).parent();
		msgId=$(this).parent().find(".msgId").val();
		url = site_url+'/chatrooms/'+chatId+'/msg/'+msgId+'/delete';
		$.get(url,{},function(data,status){
			if (status == 'success' && data.code == 0){
				alert("success");
				last_msg = '[' + data.last_msg.cnfn + ' ' + data.last_msg.cnln + ']' +data.last_msg.content;
				$("#chatroom_"+chatId).find(".last_msg").html(last_msg);
				element.detach();
			}
			else
			{
				alert(status+data.message);
			}
		});
	});
	
	
	//see chatRoom Information
	$("#show_info").click(function(){
		render("#create-chatroom");
	});
	
	//change ChatRoom information
	$("#update_button").click(function(){
		chatId = Number($(".todo-done").attr("id").slice(9));
		url = site_url+'chatrooms/'+chatId+'/edit';
		$.post(url,
			{
				'operation':'edit',
				'name':$("#new_chatroom_name").val(),
				'topics':$("#new_chatroom_topics").val(),
				'capacity':$("#new_chatroom_capacity").val()
			},
		function(data,status){
			if (status == 'success' && data.code == 0){	
				add_member(chatId);
			}
			else
			{
				alert(status+data.message);
			}
		});
	});
	
	//
	$(".search_back").click(function(){
		render("#create-chatroom");
	});
	
	//search member
	$("#search_input").keyup(function(){
		content = $(this).val();
		if (content == "")
		{
			$.get("/index.php/message/getuserinfo",
			function(data,status){
				//var obj=eval ("("+data+")");
				obj=data;
				//@todo
				$(".search_user").detach();
				if (typeof obj != "undefined")
					for (x in obj)
					{	
						if (! isNaN(Number(x)))
							$("#search").find("ul").append(
							'<li class="search_user">'+
								'<a href="#" class="button button-rounded button-flat-caution hidden add-button" style="float: right;">Add</a>'+
								'<div class="todo-icon">'+
									'<i class="icon-group"></i>'+
								'</div>'+
								'<div class="todo-value">'+
									'<h4 class="todo-name">'+obj[x].cnfn+' '+obj[x].cnln+'</h4>Student'+
								'</div>'+
								'<input type="hidden" class="search-id" value="'+obj[x].uid+'"/>'+
								'<input type="hidden" class="search-enn" value="'+obj[x].enn+'"/>'+
								'<input type="hidden" class="search-cnln" value="'+obj[x].cnln+'"/>'+
							'</li>'
						);
					} 
				}
		
			);
		}
		else
		{	
			$.get("/index.php/message/search",{fsearch: $("#search_input").val()},function(data,status){
				//var obj=eval ("("+data+")");
				obj=data;
				//@todo
				$(".search_user").detach();
				if (typeof obj != "undefined")
					for (x in obj)
					{	
						if (! isNaN(Number(x)))
							$("#search").find("ul").append(
							'<li class="search_user">'+
								'<a href="#" class="button button-rounded button-flat-caution hidden add-button" style="float: right;">Add</a>'+
								'<div class="todo-icon">'+
									'<i class="icon-group"></i>'+
								'</div>'+
								'<div class="todo-value">'+
									'<h4 class="todo-name">'+obj[x].cnfn+' '+obj[x].cnln+'</h4>Student'+
								'</div>'+
								'<input type="hidden" class="search-id" value="'+obj[x].uid+'"/>'+
								'<input type="hidden" class="search-enn" value="'+obj[x].enn+'"/>'+
								'<input type="hidden" class="search-cnln" value="'+obj[x].cnln+'"/>'+
							'</li>'
						);
					} 
			});
		}
	});
	
	//get UID information
	$("selecor").click(function(){
		
	});
	
});

//js functions
function hideButton() {
	$(".clicked").addClass("hidden");
	$(".clicked").removeClass("clicked")
}

function switch_type(type){
	if (type=="group")
	{
		$(".toggle").removeClass("toggle-off");
		$("#toggleOption1").attr("checked","checked");
		$("#toggleOption2").removeAttr("checked");
		$(".capacity-field").removeClass("hidden");
	}
	else if (type=="single")
	{
		$("#toggleOption2").attr("checked","checked");
		$("#toggleOption1").removeAttr("checked");
		$(".toggle").addClass("toggle-off");
		$(".capacity-field").addClass("hidden");
	}
	
}

function prerender() {
	$(".todo-done").find(".navbar-unread").addClass("white");
	$(".todo-done").find(".navbar-unread").removeClass("green");
	$(".todo-done").removeClass("todo-done");
}

function render(selector){
	$(".rendered").fadeOut("fast");
	$(".rendered").addClass("hidden");
	$(".rendered").removeClass("rendered");
	$(selector).addClass("rendered");
	$("#chatroom-list").removeClass("rendered");
	$(selector).removeClass("hidden");
}


function switch_chatroom(chatId){
	//create chatting space
	current_chatId = chatId;
	element = $("#chatroom_"+chatId);
	chatName = element.find(".chatroom_name").html();
	$("#chatroom-id").val(chatId);
	$("#chatroom_name").html('<a href="#" class="user-name" style="border-left:none;"><i class="icon-user"></i>'+chatName+'</a>');
	$("#conver").empty();
	
	//ajax
	url = site_url+'chatrooms/'+chatId+'/msg';
	$.get(url,function(data,status){
		if (status == 'success' && data.code == 0){			
			//list messages
			conver = '';
			for (x in data.list){
				speaker = {'uid':data.list[x].speaker,'cnfn':data.list[x].cnfn,'cnln':data.list[x].cnln,'enn':data.list[x].enn};
				conver = htmlmark(data.list[x].id,data.list[x].content,speaker,data.list[x].created_time) + conver;
			}
			$("#conver").html(conver);
			$("#conver").scrollTop(1024*$("#conver").height());
			//effect@todo
			current_chatroom.messages = data.list;
		}else{
			alert(status+data.message);
		}
	});
	
	url = site_url + 'chatrooms/' + chatId;
	$.get(url,{},function(data,status){
		if (status == 'success' && data.code == 0){
			current_chatroom.info = data.chatroom_info;
			current_chatroom.members = data.members;
			info_holder(false);
		}else{
			alert(status + data.code + data.message);
		}
	});
	prerender();
	render("#chatroom-msg");
	element.addClass("todo-done");
	element.find(".navbar-unread").addClass("green");
	element.find(".navbar-unread").removeClass("white");
}

function info_holder(create_or_not){
	if (create_or_not == true){
		//prepare the space for create chatroom
		$("#legend").html('<legend class="">Create Chatroom</legend>');
		$("#new_chatroom_name").val("");
		$("#new_chatroom_topics").val("");
		$("#new_chatroom_capacity").val("");
		$(".member-field").find(".controls").empty();
		switch_type("single");
		$(".type-field").delegate("label","click",function(){
			if ($(this).parent().hasClass("toggle-off"))
			{
				switch_type("group");
			}
			else
			{
				switch_type("single");
			}
		});
		//@todo chatroom type
		//@todo chatroom members
		$(".member-field").addClass("hidden");
		$("#create_button").parent().parent().removeClass("hidden");
	}else{
		$("#legend").html('<legend class="">'+current_chatroom.info.name+'</legend>');
		$("#new_chatroom_name").val(current_chatroom.info.name);
		$("#new_chatroom_topics").val(current_chatroom.info.topics);
		$("#new_chatroom_capacity").val(current_chatroom.info.capacity);
		switch_type(current_chatroom.info.type);
		$(".member-field").find(".controls").empty();
		for (x in current_chatroom.members)
		{
			$(".member-field").find(".controls").append(
				htmlmark_member(current_chatroom.members[x])
			);
		};
		$(".member-field").removeClass("hidden");
		$(".type-field").undelegate();
		$("#create_button").addClass("hidden");
		$("#update_button").removeClass("hidden");
	}
}

function add_member(chatId){
	member_list = [];
	$(".member").each(function(){
		member_list[member_list.length] = $(this).find(".member-id").val();
	});
	var json = JSON.stringify(member_list);
	url = site_url + 'chatrooms/'+chatId+'/create';
	$.post(url,{'members':json},function(data,status){
		if (status == 'success' && data.code == 0){
			alert("success");
			$(".member-field").removeClass("hidden");
			switch_chatroom(chatId);
		}else{
			alert(status+data.message);
		}
	});
	
}

function htmlmark_member(member_info){
	return '<div class="member">'+
			  '<p class="member-name">'+member_info.enn+' '+member_info.cnln+'</p>'+
			  '<input type="hidden" class="member-id" value="'+member_info.uid+'"/>'+
			  '<a href="#" class="button button-rounded button-flat-caution hidden delete-member" style="color: white">Delete Member</a>'+
			'</div>';

}
/**
 * Function to return a message html in the conversation given the data
 * id is the message ID
 * content is the message content
 * speaker = {'uid':uid,'cnfn':cnfn,'cnln':cnln,'enn':enn
 * if speaker = false, it's user himself speaking
 */ 
function htmlmark(id, content, speaker, timestring){
	if ($("#msg_"+id).length == 0){
		year  = timestring.substr(0,4);
		month = timestring.substr(5,2);
		day   = timestring.substr(8,2);
		time  = timestring.substr(11);
		
		current = new Date();
		
		if ((year == current.getFullYear()) && (month == current.getMonth()+1) && (day == current.getDate()))
		{
			timestamp = time;
		}
		else
		{
			timestamp = timestring;
		}
		if (speaker !== false && speaker.uid != uid){
			//others receive
			return 	'<div class="receive" id="msg_'+id+'">'+
						'<div class="msg-info">'+speaker.enn+' '+speaker.cnln+' '+timestamp+'</div>'+
						'<div class="tooltip fade right in">'+
							'<div class="tooltip-arrow"></div>'+
							'<div class="tooltip-inner">'+content+'</div>'+
						'</div>'+
					'</div>';
		}else{
			//myself
			return	'<div class="send" id="msg_'+id+'">'+
						//@todo
						'<div class="msg-info">'+timestamp+'</div>'+
						'<a href="#" class="button button-rounded button-flat-caution hidden delete-msg" style="color:white;"><i class="icon-trash"></i>Delete</a>'+
						'<div class="tooltip fade left in">'+
							'<div class="tooltip-arrow"></div>'+
							'<div class="tooltip-inner">'+content+'</div>'+
							'<input class="msgId" type="hidden" value="'+id+'">'+
						'</div>'+
					'</div>';
		}
	}
}