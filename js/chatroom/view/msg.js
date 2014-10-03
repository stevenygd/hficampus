// JavaScript Document
// msg view
// by Ryan Su

define(function(){
	
	var MsgView = Backbone.View.extend({
		
		tagName: 'div',
				
		className: function(){
			if(this.model.get('speaker')==uid)
				return 'send'
			else
				return 'receive'
		},
		
		template: _.template($("#item-msg").html()),
		
		events: {
			'.click .tooltip': 'showDel',
			'.click .delete': 'destroy',
		},
			
		initialize: function(){
			_.bindAll(this, 'render', 'remove', 'showDel');
			this.model.bind('change', this.render);
			this.model.bind('destroy', this.remove);
		},
			
		render: function(){
			$(this.el).html(this.template(this.model.toJSON()));
			return this;
		},
		
		showDel: function(){
			$(".clicked").addClass("hidden");
			$(".clicked").removeClass("clicked");
			this.$(".delete-msg").removeClass("hidden");
			this.$(".delete-msg").addClass("hidden");
		},
		
		destroy: function(){
			this.model.destroy();
		},
		
		remove: function(){
			this.remove();
		},
		
	});
	
	return MsgView;
});