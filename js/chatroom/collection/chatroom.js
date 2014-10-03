// JavaScript Document
// Chatroom Collecttion
// by Ryan Su
define(['chatroom/model/chatroom'],function(Chatroom){
	
	var ChatroomList = Backbone.Collection.extend({
		
		model: Chatroom,
		
		url: '/chatrooms',
		
		parse: function(data){
			return data.chatrooms;
		}
		
	});
	
	return ChatroomList;
});