// JavaScript Document
// by LXS 

$(document).ready(function(){
	$(".button:first").parent().css("background-color","white");
	$(".button:first").addClass("clicked");
		
	$(".button").click(function(){
		$(".clicked").parent().css("background","black");
		$(".clicked").removeClass("clicked");
		$(this).parent().css("background-color","white");
		$(this).addClass("clicked");
		$(this).prev().css("display","none");
	});
	
	$(".button").mouseover(function(){
		if (! $(this).hasClass("clicked"))
		{
			$(this).addClass("red");
			$(this).prev().css("display","block");		
		}
	});
	
	$(".button").mouseout(function(){
		$(this).removeClass("red");
		$(this).prev().css("display","none");
	});
	
});