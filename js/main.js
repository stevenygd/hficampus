// JavaScript Document
// main 
// by Ryan Su
require.config({
	paths: {
		"jquery": "jquery",
		"underscore": "underscore-min",
		"backbone": "backbone-min"
	}
	
});
require(['jquery', 'underscore', 'backbone', 'chatroom/model/chatroom', 'chatroom/model/msg', 'chatroom/collection/chatroom', 'chatroom/collection/msg','chatroom/view/chatroom', 'chatroom/view/msg', 'chatroom/view/app'], 
function ($, _, b, model, model_2, collection, collection_2, view, view_2, app) {
    var app = new app();
});