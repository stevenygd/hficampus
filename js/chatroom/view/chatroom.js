// JavaScript Document
// Chatroom view
// by Ryan Su
define(function(){
		
	var ChatroomView = Backbone.View.extend({
		
		tagName: 'li',
		
		className: 'chatroom',
				
		template: _.template($("#item-chatroom").html()),
		
		events: {
			'click': 'activate',
			'click .delete-button': 'destroy',
		},
			
		initialize: function(){
			_.bindAll(this, 'render', 'remove', 'activate');
			this.model.bind('change', this.render);
			this.model.bind('destroy', this.remove);
		},
			
		render: function(){
			$(this.el).html(this.template(this.model.toJSON()));
			return this;
		},
		
		deatroy: function(){
			if(this.hasClass('active'))
				this.model.destroy();
		},
		
		remove: function(){
			this.remove();
		},
			
		activate: function(){
			$("#chatroom-msg").addClass("hidden");
			$("#conver").empty();
			$(".todo-done").removeClass("todo-done");
			$(this.el).addClass("todo-done");
			$("#chatroom-msg").removeClass("hidden");
			window.chatId = this.model.id;
		},
		
	});
	
	return ChatroomView;
});