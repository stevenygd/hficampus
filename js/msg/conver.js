// JavaScript Document

$(document).ready(function(){
	
	$("#reply").click(function(){
		$(".page").css("margin","45px 0 162px 0");
		$("#send").fadeIn("fast",function(){
			$("body").scrollTop($(".page").height());	
		});
	});
	
	$(".page").click(function(){
		$("#send").fadeOut("fast");
		$(".page").css("margin","45px 0 0 0");
	});
	
	$(".sent").each(function(){
		$(this).find(".time").css("display","inline-block");
		left=$(this).find(".msg").width()-$(this).find(".time").width()+10;
		if (left > 10)
		{
			$(this).find(".time").css("margin","0 "+left+"px 0 0");
		}
		$(this).find(".time").css("display","block");
	});
	
	$(".receive").each(function(){
		$(this).find(".time").css("display","inline-block");
		right=$(this).find(".msg").width()-$(this).find(".time").width()+10;
		if (right > 10)
		{
			$(this).find(".time").css("margin","0 0 0 "+right+"px");
		}
		$(this).find(".time").css("display","block");
	});
	
	var uid=$("#uid").val();
	var t;
	t=setInterval(
		function(){
				eid=$("#eid").val();
				lid=$("#lid").val();
				$.get("/index.php/message/"+eid+"?execution=fetchmsg&lid="+lid,
					  function(data,status){
					   //json
					   //var obj=eval ("("+data+")");
						obj=data;
					   //change the content
					   if (obj.length > 0)
						   for (x in obj)
						   {
							   if (obj[x].auth==uid)
							   {
								   $(".msgbox").last().after(
								   '<div class="sent msgbox"><p class="time">'
								   +obj[x].time.substr(11,5)
								   +'</p><p class="msg">'
								   +obj[x].msg
								   +'</p></div>');
								   $("#lid").val(x);
							       $(".sent").last().find(".time").css("display","inline-block");
								   left=$(".sent").last().find(".msg").width()-$(".sent").last().find(".time").width()+10;
								   if (left > 10)
								   {
										$(".sent").last().find(".time").css("margin","0 "+left+"px 0 0");
								   }
								   $(".sent").last().find(".time").css("display","block");
							   }
							   else
							   		if(obj[x].to==uid)
							   		{
									   $(".msgbox").last().after(
									   '<div class="receive msgbox"><p class="time">'
									   +obj[x].time.substr(11,5)
									   +'</p><p class="msg">'
									   +obj[x].msg
									   +'</p></div>');
								   	   $("#lid").val(x);
									   $(".receive").last().find(".time").css("display","inline-block");
									   right=$(".receive").last().find(".msg").width()-$(".receive").last().find(".time").width()+10;
									   if (right > 10)
									   {
											$(".receive").last().find(".time").css("margin","0 0 0 "+right+"px");
									   }
									   $(".receive").last().find(".time").css("display","block");	
									}
						   }
				});		
		}
	,5000);
	
});