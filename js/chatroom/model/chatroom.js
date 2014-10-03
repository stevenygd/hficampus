// JavaScript Document
// Chatroom model
// by Ryan Su
define(function(){
	
	var Chatroom = Backbone.Model.extend({
		
		defaults:{
			last_msg: {content: "new chatroom"},
		},
			
		initialize:function(){
			if(!this.get("last_msg")){
				this.set({"last_msg":this.defaults.last_msg})
			}
		},
			
		urlRoot: '/chatrooms',
		
	});
	
	return Chatroom;
});