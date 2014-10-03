// JavaScript Document
define(['chatroom/model/msg'],function(Message){
	
	var MsgList = Backbone.Collection.extend({
		
		model: Message,
		
		parse: function(data){
			return data.list.reverse();
		}
	});
	
	return MsgList;
});