// JavaScript Document	
	channel.onopen = function(){
		alert('Channel Successfully Opened!');
	};
	
	channel.onmessage = function(output){
		data = eval('('+output.data+')');
		if (data.ntype === 'msg' ){
			//message operation
		}else{
			//chatroom operation
			
		}
	};
	
	channel.onerror = function(){
		alert('Something Wrong');
	};
	
	channel.onclose = function(){
		alert('Channel Closed!');
	};
	
	//get check room class	
	Chatroom = {
				
		"create":function(){
			//fetch data
			var name = $("#chatroom_name").val();
			var type = $("#chatroom_type").val();
			var topics = $("#chatroom_topics").val();
			var capacity = $("#chatroom_capacity").val();
			
			d ={'name':name,'type':type,'topics':topics,'capacity':capacity};
			url = site_url + 'chatrooms/create';
			
			$.post(url,d,function(data,status){
				if (status == 'success' && data.code == 0){
					//get chatid
					chatId =data.chatroom_id;
										
					//create a chatting room
					
					//create chatting space
				}else{
					alert(status+data.message);
				}
			});
		},
		
		"quit":function(chatId){
			url = site_url+'chatrooms/'+chatId+'/delete';
			$.get(url,function(data,status){
				if (status == 'success' && data.code ==0){
					//create chatting space
					
				}else{
					alert(status+data.message);
				}
			});
		},
		
		"enter" : function (element,e){
			chatId = element.parent().prevAll(".chatId").html();
			url = site_url+'chatrooms/'+chatId+'/msg/';
			$.get(url,function(data,status){
				if (status == 'success' && data.code == 0){
					//create chatting space
					
					
				}else{
					alert(status+data.message);
				}
			});
		},
		
/*		"joinin":function(chatId){
			url = site_url+'testChannel/api/join_chatroom';
			d = {'chatId':chatId,'channelId':channelId};
			$.post(url,d,function(data,status){
				if (status == 'success'){
					//create chatting space
					
				}else{
					alert(status+data);
				}
			});
		},
*/		
		
		"del":function (chatId){
			url = site_url+'chatrooms/'+chatId+'/delete';
			$.get(url,function(data,status){
				if (status == 'success' && data.code ==0){
					//create chatting space
					
				}else{
					alert(status+data.message);
				}
			});
		},

		"send":function(element,e){
				e.preventDefault();
				data = 
				chatId = 
				d = {'content':data};
				url = site_url + 'chatrooms/'+chatId+'/msg/create';
				$.post(url,d,function(data,status){
					if (status != 'success' || data.code != 0){
						alert(status+data);
					}else{
						element.prev().prev().val('');
					}
				});
		}
	}
		
	$("#create_chatroom").click(Chatroom.create);
	
	$(".chatroom_op").live("click",function(){
		chatId = $(this).parent().prevAll(".chatId").html();
		
		switch ($(this).val()){
			case 'Enter':
				Chatroom.enter($(this),chatId);
			break;
			case 'Quit':
				Chatroom.quit(chatId);
			break;
			case 'Delete':
				Chatroom.del(chatId);
			break;
		}
	});
	
	$(".msg_submit").live("click",function(e){
		Chatroom.send($(this),e);
	});
			
