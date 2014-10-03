// JavaScript Document
// app view
// by Ryan Su
define(['chatroom/collection/chatroom','chatroom/collection/msg','chatroom/view/chatroom','chatroom/view/msg'],function(chatroomList, messageList, chatroomView, msgView){
	
	window.msgList = new messageList();
	
	window.chatList = new chatroomList();
	
	var AppView = Backbone.View.extend({
		
		el: $("body"),
		
		events: {
			'click .todo-done':'showMsg',
			'click #send':'sendMsg',
			},
		
		initialize: function(){
			this.content=$("#content");
			_.bindAll(this, 'addOneChat', 'addAllChat', 'addOneMsg', 'addAllMsg', 'showMsg');
			chatList.bind('add', this.addOneChat);
			chatList.bind('reset', this.addAllChat);
			msgList.bind('add', this.addOneMsg);
			msgList.bind('reset', this.addAllMsg);
			chatList.fetch({reset:true});
		},
				
		addOneChat: function(model){
			var view = new chatroomView({model:model});
			this.$("#chatroom-list").find("ul").prepend(view.render().el);
			
		},
		
		addAllChat: function(){
			chatList.each(this.addOneChat);
		},
		
		showMsg: function(){
			msgList.url='/chatrooms/'+chatId+'/msg';
			msgList.fetch({reset:true});
		},
		
		addOneMsg: function(model){
			var view = new msgView({model:model});
			this.$("#chatroom-msg").find("#conver").append(view.render().el);
		},
		
		addAllMsg: function(){
			msgList.each(this.addOneMsg);
		},
		
		sendMsg: function(){
			msgList.url='/chatrooms/'+chatId+'/msg/create';
			msgList.create({content:this.content.val()}, _.last(msgList).fetch());
			this.content.val('');
		},

	});
	
	return AppView;
});