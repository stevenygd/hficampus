// JavaScript Document
$(document).ready(function(){
	//show particular section
	$(".title").click(function(){
		if ($(this).next().hasClass("hide"))
		{
			$(this).next().removeClass("hide");
			$(this).css("background-color","#0C034D");
			$(this).css("color","white");
		}
		else
		{
			$(this).next().addClass("hide");
			$(this).css("background-color","white");
			$(this).css("color","#0C034D");
		}
	});
	
	/**
	 * Page
	 */
	//load page
	$(".pclick").click(function(){
		$("#page_current").find(".limb").remove();
		url=site_url+'/course/'+cid+'/page';
		$.get(url,function(data,status){
			if (status=='success')
			{
				for (x in data.page_list)
				{
					$("#page_current").find(".head").after(
						'<tr class="limb">'
							+'<th>'+data.page_list[x].id+'</th>'
							+'<th>'+data.page_list[x].title+'</th>'
							+'<th>'+data.page_list[x].created_time+'</th>'
							+'<th>'+data.page_list[x].latest_update+'</th>'
							+'<th>'
							+'<a href="'+site_url+'/course/'+cid+'/page/'+data.page_list[x].id+'/editor">Edit</a></br>'
							+'<a href="'+site_url+'/course/'+cid+'/page/'+data.page_list[x].id+'/delete">Delete</a>'
							+'</th>'
						+'</tr>'
					);
				}
			}
			else
				alert(status);
		});
	});
	 
	/**
	 * Question
	 */
	//load question
	$(".qclick").click(function(){
		$("#question_current").find(".limb").remove();
		url=site_url+'/course/'+cid+'/question';
		$.get(url,function(data,status){
			if (status=='success')
			{
				for (x in data.questions)
				{
					$("#question_current").find(".head").after(
						'<tr class="limb">'
							+'<th>'+data.questions[x].id+'</th>'
							+'<th>'+data.questions[x].cnfn+'&nbsp;'+data.questions[x].cnln+'('+data.questions[x].enn+')'+'</th>'
							+'<th>'+data.questions[x].title+'</th>'
							+'<th>'+data.questions[x].text+'</th>'
							+'<th>'+data.questions[x].created_time+'</th>'
							+'<th>'
								+'<!--a href="'+site_url+'/course/'+cid+'/question/'+data.questions[x].id+'/editor">Edit</a></br-->'
								+'<a href="'+site_url+'/course/'+cid+'/question/'+data.questions[x].id+'/delete">Delete</a>'
							+'</th>'
						+'</tr>'
					);
				}
			}
			else
				alert(status);
		});
	});
	
	/**
	 * user
	 */
	//show or hide add user section
	$("#madd").click(function(){
		if ($(this).val()=='Adds More')
		{
			$(this).next().show();
			$(this).val('Cancel');
		}
		else
		{
			$(this).next().hide();
			$(this).val('Adds More');
		}
	});
	
	//serach
	$("#search_text").keyup(function(){
		if ($(this).val()=="")
		{
			$.get("/index.php/message/getuserinfo",
			function(data,status){
				$("#user_list").find(".limb").remove();
				if (data.length > 0)
					for (x in data)
					{
						if (x != 'length')
							$("#user_list .head").after(
								'<tr class="limb">'
								+'<th class="uid">'+x+'</th>'
								+'<th class="name">'+data[x].cnfn+'&nbsp;'+data[x].cnln+'&nbsp;('+data[x].enn+')'+'</th>'
								+'<th class="op">'+'<input type="button" class="addwait" value="Add" />'+'</th>'
								+'</tr>'	
							);
					} 
				}
		
			);
		}
		else
		{	
			$.get("/index.php/message/search",
				{
					fsearch: $("#search_text").val()	
				},
				function(data,status){
					$("#user_list").find(".limb").remove();
					if (data.length > 0)
						for (x in data)
						{
							$("#user_list .head").after(
								'<tr class="limb">'
								+'<th class="uid">'+data[x].uid+'</th>'
								+'<th class="name">'+data[x].cnfn+'&nbsp;'+data[x].cnln+'&nbsp;('+data[x].enn+')'+'</th>'
								+'<th class="op">'+'<input type="button" class="addwait" value="Add" />'+'</th>'
								+'</tr>'	
							);
						}
				}
			);
		}
	});
	
	//add user to the wait list
	$(".addwait").live("click",function(){
		uid=$(this).parents('.limb').find('.uid').html();
		name=$(this).parents('.limb').find('.name').html();
		
		bol = false;
		key = $("#user_wait").find(".head");
		low = 0
		$("#user_wait").find(".limb").find(".uid").each(function(){
			if ($(this).html() == uid)
			{
				bol = true;
			}
			else
				if (Number($(this).html()) < Number(uid))
				{
					if (Number($(this).html()) > low)
					{
						low = Number($(this).html());
						key = $(this).parent(".limb");
					}
				}
		});
		
		if (! bol)
		{
			key.after(
				'<tr class="limb">'
				+'<th class="uid">'+uid+'</th>'
				+'<th class="name">'+name+'</th>'
				+'<th class="op">'+'<input type="button" class="delwait" value="Delete" />'+'</th>'
				+'</tr>'	
			);
		}
		else
		{
			alert('You have already added this one to the wailt list~');
		}
	});
	
	//delete user from wait list
	$(".delwait").live("click",function(){
		$(this).parents('.limb').remove();
	});
	
	//submit a list of user to add
	$("#multiadd").click(function(){
		$("#user_wait").find(".limb").find(".op").html('Processing...');
		
		url=site_url+'/course/'+cid+'/create';
		uids=new Array();
		limbs=new Array();
		
		$("#user_wait").find(".limb").each(function(){
			uids[uids.length]=Number($(this).find('uid').html());
			
			limbs[limbs.length]=$(this);
			
			count=0;
			$.post(url,{'execution': 'invite',
						'uid'      : $(this).find('.uid').html()
						},function(data,status){
							if (status=='success')
								if (data == 0)
								{
									limbs[count].find(".op").html('Complete');
								}
								else
								{
									alert('sth wrong');
								}
								
							count=count+1;
			});
		});
	});
});