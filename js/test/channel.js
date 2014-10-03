// JavaScript Document	
	channel.onopen = function(){
		alert('Channel Successfully Opened!');
	};
	
	channel.onmessage = function(output){
		data = eval('('+output.data+')');
		if (data.ntype === 'msg' ){
			//message operation
			$("#chat_"+data.chatId).find('table').append(
				'<tr>'+
					'<th class="speaker" class="speaker_uid_'+data.speaker.user.id+'">'+data.speaker.user_info.cnfn+' '+data.speaker.user_info.cnln+' '+'</th>'+
					'<th class="msg" id="msg_'+data.messageId+'">'+data.content+'</th>'+
				'</tr>'
			);
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
					$("#chat_room_table").append(
						'<tr id="chatroom_list_'+chatId+'">'+
							'<th>'+chatId+'</th>'+
							'<th class="members">'+uid+'</th>'+
							'<th>'+
								'<input type="button" class="chatroom_op" value="Quit" />'+
							'</th>'+
						'</tr>'
					);
					
					//create chatting space
					$("#chat_room_table").append(
						'<div id="chat_'+chatId+'" class="check_div">'+
							'<p>'+chatId+'</p>'+
							'<table class="chat_list">'+
								'<tr><th>ID</th><th>Data</th></tr>'+
							'</table>'+
							'<input name="data" class="msg_data" />'+
							'<input type="hidden" name="chatId" class="chatId_hidden" value="'+chatId+'" />'+
							'<input type="button" class="msg_submit" value="Send" />'+
						'</div>'
					);
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
					$("#chat_"+chatId).remove();
					$("#chatroom_list_"+chatId).remove();
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
					table = '<div id="chat_'+chatId+'">'+
								'<p>'+chatId+'</p>'+
								'<table class="chat_list">'+
									'<tr><th>ID</th><th>Data</th></tr>';
								
					for(x in data.list){
						table = table +'<tr><th>'+data.list[x].speaker+'</th><th>'+data.list[x].content+'</th></tr>';
					}
					table = table +
							'</table>'+
							'<input name="data" class="msg_data" />'+
							'<input type="hidden" name="chatId" class="chatId_hidden" value="'+chatId+'" />'+
							'<input type="button" class="msg_submit" value="Send" />'+
						'</div>';

					$("#chat_room_table").append(table);
					$("#chatroom_list_"+chatId).find(".chatroom_op [value='Enter']").val("Quit");
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
					$("#chat_room_table").append(
						'<div id="chat_'+chatId+'">'+
							'<p>'+chatId+'</p>'+
							'<table class="chat_list">'+
								'<tr><th>ID</th><th>Data</th></tr>'+
							'</table>'+
							'<input name="data" class="msg_data" />'+
							'<input type="hidden" name="chatId" class="chatId_hidden" value="'+chatId+'" />'+
							'<input type="button" class="msg_submit" value="Send" />'+
						'</div>');
					$("#chatroom_list_"+chatId).find(".chatroom_op").val("Quit");
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
					$("#chat_"+chatId).remove();
					$("#chatroom_list_"+chatId).remove();
				}else{
					alert(status+data.message);
				}
			});
		},

		"send":function(element,e){
				e.preventDefault();
				data = element.prev().prev().val();
				chatId = element.prev().val();
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
			
	$(".search").keyup(function(){
		chatId = $(this).prev().val();
		if ($("#search").val()=="")
		{
			$.get("/index.php/message/getuserinfo",
			function(data,status){
				//var obj=eval ("("+data+")");
				obj=data;
				$(".name").detach();
				if (obj.length > 0)
					for (x in obj)
					{
						if (x != 'length')
							$("#chatroom_list_"+chatId).find(".search").after(
								'<div class="name"><p><span>'+
								obj[x].enn+'</span>'+
								obj[x].cnln+'</p><input type="hidden" value="'+x+'" />'+
								'<input type="hidden" value="'+chatId+'"/>'+
								'<input type="button" class="invite" value="Invite" />'+
								'</div>'	
						);
					} 
				}
		
			);
		}
		else
		{	
			$.get("/index.php/message/search",
				{
					fsearch: $("#chatroom_list_"+chatId).find(".search").val()	
				},
				function(data,status){
					//var obj=eval ("("+data+")");
					obj=data;
					$(".name").detach();
					if (obj.length > 0)
						for (x in obj)
						{
							if (x != 'length')
								$("#chatroom_list_"+chatId).find(".search").after(
								'<div class="name"><p><span>'+
								obj[x].enn+'</span>'+
								obj[x].cnln+'</p><input type="hidden" value="'+x+'" />'+
								'<input type="hidden" value="'+chatId+'"/>'+
								'<input type="button" class="invite" value="Invite" />'+
								'</div>'	
						);
						}
				}
			);
		}
	});
	
	
	$(".invite").live("click",{},function(e){
		e.preventDefault();
		elemtn = $(this);
		chatId = $(this).prev().val();
		uid    = $(this).prev().prev().val();
		url    = site_url + 'chatrooms/' + chatId + '/create';
		data   = {'uid':uid};
		$.post(url,data,function(data,status){
			if (status == 'success' &&  data.code == 0){
				element.remove();
				alert('success');
			}else{
			}
		});
	});
