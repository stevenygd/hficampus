// JavaScript Document
//by LXS

$(document).ready(function(){
	$(".box2").click(function(){
		id=$(this).find(".name").val();
		$(window.parent.frames['navi'].document).find("#"+id+"").parent().css("background-color","white");
		$(window.parent.frames['navi'].document).find("#"+id+"").addClass("clicked");
		$(window.parent.frames['navi'].document).find(".button:first").parent().css("background-color","black");
		$(window.parent.frames['navi'].document).find(".button:first").removeClass("clicked");
	});
});
