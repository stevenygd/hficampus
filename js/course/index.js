// JavaScript Document
$(document).ready(function(){
	
	$(".box").click(function(){
		$(".clicked").hide();
		$(".clicked").removeClass("clicked");
		$(this).next(".list").fadeIn("slow");
		$(this).next(".list").addClass("clicked");
	});
	
	//effect
	$(".block").mouseover(function(){
		$(this).find(".name").css("color","red");
	});
	
	$(".block").mouseout(function(){
		$(this).find(".name").css("color","#0C034D");
	});
	
	$("#my").click(function(){
		$(".create").css("margin","184px 0px 0px 610px");
	});
	
	$("#other").click(function(){
		$(".create").css("margin","192px 0px 0px 154px");
	});
	
	$(".block").mouseover(function(){
		$(this).find(".name").css("color","red");
	});
	
	$(".block").mouseout(function(){
		$(this).find(".name").css("color","#0C034D");
	});
	
	$(".create").mouseover(function(){
		$(this).find("p").css("color","red");
	});
	
	$(".create").mouseout(function(){
		$(this).find("p").css("color","#0C034D");
	});
	//end effect
	
	//join courses
	$(".stu").click(function(){
		$(".clicked").hide();
		$(".clicked").removeClass("clicked");
		$(".create").css("margin","186px 0px 0px 154px");
		$(this).next(".list").fadeIn("slow");
		$(this).next(".list").addClass("clicked");
	});
	
	$(".app").click(function(){
		$(".slided").slideToggle();
		$(".slided").removeClass("slided");
		$(this).next(".apply").slideToggle();
		$(this).next(".apply").addClass("slided");
	});
	
	// end join
	
	//create new course
	$(".tea").click(function(){
		$(".clicked").hide();
		$(".clicked").removeClass("clicked");
		$(".create").css("margin","186px 0px 0px 154px");
		$(this).next(".new").fadeIn("slow");
		$(this).next(".new").addClass("clicked");
	});
	//end create
	$("#search").click(function(){
		$("#all_courses_list").hide();
		$("#sort").show();
		$("#cancel_search").show();
	});
	
	$("#cancel_search").click(function(){
		$("#sort").hide();
		$("#cancel_search").hide();
		$("#all_courses_list").show();
	});
	
	$(".type").click(function(){
		$("#type").find(".tag_clicked").removeClass("tag_clicked");
		$(this).addClass("tag_clicked");
		type=$(this).find("input").val();
	});
	
	$(".subtype").click(function(){
		$("#subtype").find(".tag_clicked").removeClass("tag_clicked");
		$(this).addClass("tag_clicked");
		subtype=$(this).find("input").val();
	});
	
	$(".teacher").click(function(){
		$("#teacher").find(".tag_clicked").removeClass("tag_clicked");
		$(this).addClass("tag_clicked");
		teacher=$(this).find("input").val();
	});
	
	$(".year").click(function(){
		$("#year").find(".tag_clicked").removeClass("tag_clicked");
		$(this).addClass("tag_clicked");
		year=$(this).find("input").val();
	});
	
	$("#sort_submit").click(function(){
		$(this).next(".status").show();
		$.get(site_url+"/course/get?fetch=fetch"+"type="+type+"&subtype="+subtype+"&teacher="+teacher+"&year="+year,
			  function(data,status){
				  $("#sort").hide()
				  $("#sort_submit").next(".status").hide();
				  $("#cancel_search").hide();

				  $("#all_courses_list").empty();
				  for(x in data.course)
				  {
					$("#all_courses_list").append(
						'<div class="block"><a href="'+site_url+'/course/'+data.course[x].id+'">'+
							'<p class="name">'+data.course[x].name+'</p>'+
							'<p class="author">by '+data.course[x].cnfn+' '+data.course[x].cnln+'</p>'+
						'</a></div>'
					);
				  }
				  $("#all_courses_list").fadeIn('fast');
			  });
	});
	
});