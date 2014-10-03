// JavaScript Document
// by LXS

$(document).ready(function(){
	$(".button > p").mouseover(function(){
		$(this).css("color","red");	
	});
	$(".button > p").mouseout(function(){
		$(this).css("color","#306");	
	});
	
	var uid=$("#uid").val();

	$("#add").click(function(){
		$("#text").fadeOut("fast");
		$("#list").fadeOut("fast");
		$("#search").val("");
		$(".name").detach();
		$.get("/index.php/message/getuserinfo",
			function(data,status){
				////var obj=eval ("("+data+")");
				obj=data;
				if (obj.length > 0)
					for (x in obj)
					{
						if (x!='length')
							$("#search").after(
								'<div class="name"><p><span>'
								+obj[x].enn+'</span>'
								+obj[x].cnln+'</p><input type="hidden" value="'+x+'"/></div>'	
							);
					} 
			}
		
		);
		$("#conver").fadeOut("fast",function(){
			$("#list").fadeIn("fast");
			});		
	});
	
	$("#search").keyup(function(){
		if ($("#search").val()=="")
		{
			$.get("/index.php/message/getuserinfo",
			function(data,status){
				//var obj=eval ("("+data+")");
				obj=data;
				$(".name").detach();
				if (obj.length > 0)
					for (x in obj)
					{
						if (x != 'length')
							$("#search").after(
								'<div class="name"><p><span>'
								+obj[x].enn+'</span>'
								+obj[x].cnln+'</p><input type="hidden" value="'+x+'" /></div>'	
						);
					} 
				}
		
			);
		}
		else
		{	
			$.get("/index.php/message/search",
				{
					fsearch: $("#search").val()	
				},
				function(data,status){
					//var obj=eval ("("+data+")");
					obj=data;
					$(".name").detach();
					if (obj.length > 0)
						for (x in obj)
						{
							if (x != 'length')
								$("#search").after(
								'<div class="name"><p><span>'
								+obj[x].enn+'</span>'
								+obj[x].cnln+'</p><input type="hidden" value="'+x+'" /></div>'	
						);
						}
				}
		
			);
		}
	});
	
	$("#new").click(function(){
		$("#text").fadeOut("fast");
		$("#list").fadeOut("fast");
		$("#search").val("");
		$(".name").detach();
		$.get("/index.php/message/getflist",
			function(data,status){
				//var obj=eval ("("+data+")");
				obj=data;	
				for (x in obj)
				{
					$("#search").after('<div class="name new"><p><span>'
								+obj[x].enn+'</span>'
								+obj[x].cnln+'</p><input type="hidden" value="'+x+'" /></div>');
				}
			}
		);
		$("#conver").fadeOut("fast",function(){
			$("#list").fadeIn("fast");
			});		
	});
		
	$("#list").delegate(".name","click",function(){
		$("#text").fadeOut("fast");
		$(".clicked").css("background-color","#CCC");
		$(".clicked").css("color","white");
		$(".clicked").removeClass("clicked");
		$(this).css("background-color","white");
		$(this).css("color","#CCC");
		$(this).addClass("clicked");
		
		id=$(this).find("input").val();
		if ($(this).hasClass("new"))
		{
			$("#text").html(
				'<form action="message/'+id+'/create" method="post" target="conver">'+
					'<input type="hidden" name="to" value="'+id+'"/>'+
					'<p>send a new message to your friend</p>'+
					'<textarea type="text" name="text"></textarea>'+
					'<input type="submit" id="send" value="send"/>'+
				'</form>');
		}
		else
		{
			$("#text").html(
				'<form action="message/friends/create" method="post" target="conver">'+
					'<input type="hidden" name="to" value="'+id+'"/>'+
					'<p>send a message to inform your friend</p>'+			
					'<textarea type="text" name="text"></textarea>'+
					'<input type="submit" id="ask" value="send"/>'+
				'</form>');
		}
		$("#text").fadeIn("fast");
	});
	
	$("#text").delegate("#send","click",function(){
		$("#conver").fadeIn("fast");
		$("#text").fadeOut();
		$("#list").fadeOut();
	});
	
	$("#text").delegate("#ask","click",function(){
		if ($("#conver").html()==0)
		{
			alert("Your request has been sent.");
			$("#text").fadeOut();
		}
	});
});