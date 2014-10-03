// JavaScript Document

$(document).ready(function(){
	//shifting up	
	$("#up").click(function(){
		currentid=$(".current").attr("id");
		newcurrent=Number(currentid.charAt(currentid.length-1))-1;
		if (newcurrent!=0)
		{
			$(".current").removeClass("current");
			$("#cluster"+newcurrent).addClass("current");
		}
		
	});
	
	//shifting down
	$("#down").click(function(){
		currentid=$(".current").attr("id");
		newcurrent=Number(currentid.charAt(currentid.length-1))+1;
		if (newcurrent!=(cluster_num+1))
		{
			$(".current").removeClass("current");
			$("#cluster"+newcurrent).addClass("current");
		}
	});
	
	//open in frame
	$(".frameo").click(function(){
		$("#dframe").show();
		$("#main").animate({"width": "0"},300,"swing",function(){
			$(this).hide();
			$(this).css("height","0");
			
			//organize the css within the frame
			$("#dframe").contents().find('html').css({"overflow":"scroll","overflow-x":"hidden"});
			$("#dframe").contents().find("head").append("<link>");
			acss = $("#dframe").contents().find("head").children(":last");
			acss.attr({
				  rel:  "stylesheet",
				  type: "text/css",
				  href: "/css/scrolls/scroll_iphone.css"
			});
			
			$("#dframe").css("width","760px");
			$("#dframe").animate({"height":"400px"},800,"swing",function(){
				$("#closeframe").show();
			});
		});
	});
	
	//close the frame
	$("#closeframe").click(function(){
		$("#main").show();
		$("#closeframe").hide();
		$("#dframe").animate({"width": "0"},300,"swing",function(){
			$(this).hide();
			$(this).css("height","0");
			$("#main").css("width","760px");
			$("#main").animate({"height":"400px"},800,"swing",function(){
			});
		});
	});
});