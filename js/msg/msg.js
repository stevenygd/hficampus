// JavaScript Document
/* by LXS */
var t;
var uid;

$(document).ready(function(){
	uid=$("#uid").val();
	
	$(".button").click(function(){
		$(".clicked").find(".mini").css("display","none");
		$(".clicked").removeClass("clicked");
		$(this).addClass("clicked");
		$(this).find(".mini").css("display","block");
		$(window.parent.document).find("#text").fadeOut("fast");
		$(window.parent.document).find("#list").fadeOut("fast",function(){
			$(window.parent.document).find("#conver").fadeIn("fast");	
		});
	});
	
	t=self.setInterval(
		function(){
			$('.box').each(function(){
				eid=$(this).find('.uid').val();
				lid=$(this).find('.lid').val();
				lim=1;
				$.get("/index.php/message/"+eid+"?execution=fetchmsg&lim="+lim+"&lid="+lid,
					  function(data,status){
					   //json
					   //var obj=eval("("+data+")");
					   obj=data;
					   //change the content
					   if (obj.length > 0)
						   for (x in obj)
						   {
							   if (obj[x].auth==uid)
							   {
								   $("#box"+obj[x].to).find(".msg").html(obj[x].msg);
								   $("#box"+obj[x].to).find(".time").html(obj[x].time.substr(11,5));
								   $("#box"+obj[x].to).find(".lid").val(x);
							   }
							   else
							   {
								   $("#box"+obj[x].auth).find(".msg").html(obj[x].msg);
								   $("#box"+obj[x].auth).find(".time").html(obj[x].time.substr(11,5));
								   $("#box"+obj[x].auth).find(".lid").val(x);
							   }
						   }
				});		
			});
		}
	,5000);
	
});