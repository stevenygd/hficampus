// JavaScript Document
$(document).ready(function(){
	$(".switch_next").click(function(){
		if ($(this).val() == 'Show')
		{
			$(this).val('Hide');
			$(this).next().show();
		}
		else
		if ($(this).val() == 'Hide')
		{
			$(this).val('Show');
			$(this).next().hide();
		}
	});
	
	//add an authority to a group
	$("#add_auth_to_group").click(function(){
		form=$("#add_auth");
		$.post(site_url+'/test/addauth/edit',{ 
			   gid   : form.find("input[name='gid']").val(),
			   mtype : form.find("input[name='mtype']").val(),
			   mid   : form.find("input[name='mid']").val(),
			   ntype : form.find("input[name='ntype']").val(),
			   nid   : form.find("input[name='nid']").val(),
			   auth  : form.find("input[name='auth']").val() 
			   },function(data,status){
				   if (data.group)
				   {
					   alert('Sucess');
					   window.location.reload();
				   }
				   else
				   {
					   alert(data.error);
				   }
			   });
	});
	
	//change the authority of a group
	$(".chauth").click(function(){
		//alert('not yet available');
		
		a=$(this).parent();
		gid   = a.find("input[name='gid']").val(),
		mtype = a.find("input[name='mtype']").val(),
		mid   = a.find("input[name='mid']").val(),
		ntype = a.find("input[name='ntype']").val(),
		nid   = a.find("input[name='nid']").val(),
		auth  = a.find("input[name='auth']").val() 
		$.post(site_url+'/test/addauth/edit',{ 
			   gid   : gid,
			   mtype : mtype,
			   mid   : mid,
			   ntype : ntype,
			   nid   : nid,
			   auth  : auth 
			   },function(data,status){
				   if (data.group)
				   {
					   alert('Sucess');
					   a.parent().find('.auth_content').html('url:'+mtype+'/'+mid+'/'+ntype+'/'+nid+':'+auth);
				   }
				   else
				   {
					   alert(data.error);
				   }
			   });
	});
	
	//delete an authority of a group
	$(".delauth").click(function(){
		a=$(this).parent();
		gid   = a.find("input[name='gid']").val(),
		mtype = a.find("input[name='mtype']").val(),
		mid   = a.find("input[name='mid']").val(),
		ntype = a.find("input[name='ntype']").val(),
		nid   = a.find("input[name='nid']").val(),
		$.get(site_url+'/test/delauth/delete',{ 
			   gid   : gid,
			   mtype : mtype,
			   mid   : mid,
			   ntype : ntype,
			   nid   : nid,
			   },function(data,status){
				   if (data.group)
				   {
					   alert('Sucess');
					   newnum=Number(a.parent().parent().parent().find(".num").html())-1;
					   a.parent().parent().parent().find(".num").html(newnum);
					   if (newnum == 0)
					   {
						   a.parent().parent().parent().find(".switch_next").val('Hide');
						   a.parent().parent().parent().find(".switch_next").next().hide();
					   }
					   a.parent().remove();
				   }
				   else
				   {
					   alert(data.error);
				   }
			   });
	});
	
	/**
	 * user
	 */	
	//serach
	$(".auser_search_text").keyup(function(){
		athis=$(this);
		if ($(this).val()!="")
		{
			$.get(site_url+"/account/search",{
				search:athis.val()
			},
			function(data,status){
				athis.parent().find('table').find(".limb").remove();
				if (data.length > 0)
					for (x in data)
					{
						if (x != 'length')
							athis.parent().find(".head").after(
								'<tr class="limb">'
									+'<th class="uid">'+data[x].uid+'</th>'
									+'<th class="name">'+data[x].cnfn+'&nbsp;'+data[x].cnln+'&nbsp;('+data[x].enn+')'+'</th>'
									+'<th class="op">'
										+'<input type="button" class="submitadduser" value="Add" />'
									+'</th>'
								+'</tr>'	
							);
					} 
				}
			);
		}
		else
		{	
			$.get(site_url+"/account/fetch",{
				},
				function(data,status){
					athis.parent().find('table').find(".limb").remove();
					if (data.length > 0)
						for (x in data)
							if (x != 'length')
							{
								athis.parent().find(".head").after(
									'<tr class="limb">'
										+'<th class="uid">'+x+'</th>'
										+'<th class="name">'+data[x].cnfn+'&nbsp;'+data[x].cnln+'&nbsp;('+data[x].enn+')'+'</th>'
										+'<th class="op">'
											+'<input type="button" class="submitadduser" value="Add" />'
										+'</th>'
									+'</tr>'	
								);
							}
				}
			);
		}
	});
			
	//submit a list of user to add
	$(".submitadduser").live("click",function(){
		submitadduser=$(this).parent();
		
		url=site_url+'/test/adduser/create';
		uid=submitadduser.prev().prev().html();
		gid=submitadduser.parents('.group_row').find('.group_id').html();
		submitadduser.html('Processing...');
				
		count=0;
		$.post(url,{
			'gid' : gid,
			'uid' : uid
		},function(data,status){
			if (status=='success')
				if (data == 0)
				{
					window.location.reload();
/*
					$.get(site_url+"/account/fetch",{
						id  : uid,
						lim : 1
					},function(data,status){
						if (status == 'success')
						{
							if (data.length > 0)
								for (x in data)
								{
									submitadduser.parent().parent().find('.head').after(
										'<tr class="limb">'
										+'<th class="uid">'+data[x].uid+'</th>'
										+'<th class="name">'+data[x].cnfn+'&nbsp;'+data[x].cnln+'&nbsp;('+data[x].enn+')'+'</th>'
										+'<th class="op">'+'<input type="button" class="submitadduser" value="Add" />'+'</th>'
										+'</tr>'	
									);
								}
							else
								alert(data);
						}
						else
						{
							alert('Something Wrong!');
						}
					});
*/
				}
				else
				{
					alert('sth wrong');
				}
				
			count=count+1;
		});
	});

	//kick users
	$(".kickbutton").click(function(){
		uid=$(this).parent().find("input[name='uid']").val();
		gid=$(this).parent().find("input[name='gid']").val();
		kthis=$(this);
		$.get(site_url+'/test/deluser/delete',{ 
			   gid   : gid,
			   uid   : uid,
			   },function(data,status){
				   if (data == 0)
				   {
					   kthis.parent().parent().remove();
					   alert('Successfully kick user whose uid is'+uid);
				   }
				   else
				   {
					   alert(data);
				   }
		});
	});
	
	
});