// JavaScript Document
// by LXS
$(document).ready(function(){
	
	$(".create").mouseover(function(){
		$(this).find("p").css("color","red");
	});
	
	$(".create").mouseout(function(){
		$(this).find("p").css("color","#0C034D");
	});
	
	$(".group").click(function(){
		$(".clicked").find(".mini").css("display","none");
		$(".clicked").removeClass("clicked");
		$(this).addClass("clicked");
		$(this).find(".mini").css("display","block");
		$(".notice_block").addClass("hide");
		$(".notice").addClass("hide");
		$("#right").hide();
		group=$(this).find("input").val();
		$("#add").fadeOut("fast",function(){
			$(".right").css("background-color","#B1B1B1");
			$("#right").fadeIn("fast");
			$("#notice_"+group+"").removeClass("hide");
			$("#notice_"+group+"").find(".notice").first().removeClass("hide");
		});
	});
	
	$("#right").delegate(".fetch","click",function(){
		group=$(this).parent().find(".group").val();
		count=$(this).parent().find(".count").val();
		off=parseInt(count,10)+1;
		url='notice/'+group+'?off='+off+'';
		$.get(url,function(data){
			if (data.message!==false)
			{
				var obj=data			
				$(".notice").addClass("hide");
				$("#notice_"+group+"").find(".notice").last().find(".next").removeClass("fetch").addClass("fetched");
				$("#notice_"+group+"").find(".notice").last().after(
				'<div class="notice"><p class="previous">Previous</p><p class="title">'+obj.message[0].title+
				'</p><p class="time">'+obj.message[0].time+
				'</p><p class="text">'+obj.message[0].msg+
				'</p><p class="next fetch">Next</p><input class="count" type="hidden" value="'+off+'"/><input class="group" type="hidden" value="'+group+'"/></div>');
			}
			else
			{
				alert("no more notices!");
			}
		});
	});
	
	$("#right").delegate(".fetched","click",function(){
		$(this).parent().addClass("hide");
		$(this).parent().next().removeClass("hide");
	});
	
	$("#right").delegate(".previous","click",function(){
		$(this).parent().addClass("hide");
		$(this).parent().prev().removeClass("hide");
	});
	
	$(".create").click(function(){
		$("#right").fadeOut("fast",function(){
			$(".right").css("background-color","white");
			$("#add").fadeIn("fast");
		});
	});
		
	$(".add_submit").click(function(){
		superthis=$(this);
		$(this).next().removeClass("hide");
		//获取数据
		to    = $(this).parent().find('[name="to"]').val();
		title = $(this).parent().find('[name="title"]').val();
		text  = $(this).parent().find('[name="text"]').val();
		//测试一下是否获取到数据：
		
		//开始用ajax：如果用post的话先用.post()这个吧：
		//generate url
		url = 'notice/'+to+'/create';
		$.post(url,{
			"title":title,
			"text":text
		},function(data,status){
			if (status=='success')
			{
				geturl = site_url+'/notice/'+to;
				$.get(geturl,function(responseTxt,statusTxt,xhr){
							if(statusTxt=="success")//加载成功：
							{
								alert(responseTxt);
							}
							else
							{
								alert(statusTxt);
							}
						});
			}
			else
				alert(status);
		});
	});
});	
