// JavaScript Document
// ulregister.js by Vic
// Edited by Steven Yang
$ (document).ready (function(){
	
	var num=0;
	var sids = new Array();
	
	//check which to show
	if ($("#error").html()!= ""){
		$("#register_container").fadeIn();
		$("#register").hide();
		$("#description").css("opacity","0.1");
	}else{
		$("#register_container").hide();
	}
	
	//show register
	$("#register").click(function(){
		$("#register_container").fadeIn();
		$(this).hide();
		$("#description").css("opacity","0.1");
	});
	
	//hidding register form function@todo
	$("#close").click(function(){
		$("#register_container").fadeOut();
		$("#description").css("opacity","1");
		$("#register").show();
		$(".addition").remove();
		
		//reset
		num = 0;
		var sids = new Array();
	});
	
	//adding members 
	$("#show").click(function(){
		num=num+1;
		if (num==4){
			$("#show").hide();
		};
			
		$("#more").before(
			'<div class="addition input_unit">'+
				'<p>Member '+num+'</p>'+
				'<div id="sid'+num+'" class="sid">'+
					'<label for="sid'+num+'">Student ID</label>'+
					'<input class="sidinput inputbox" type="text" name="sid'+num+'"  />'+
				'</div>'+
				'<div id="ulemail'+num+'" class="ulemail">'+
					'<label for="ulemail'+num+'">Email</label>'+
					'<input type="email" class="inputbox" name="ulemail'+num+'"  />'+
				'</div>'+
				'<div class="sidcheck"></div>'+
			'</div>'
		);
	});
	
	//deleting members @todo
	
	//confirm id
	$(".confirm_sid").live("click",function(){
		//@totest
		present_sid = $(this).parent().prevAll(".sid").find('input').val();
		exist = false;
		if (sids.length != 0){
			for (x in sids){
				if (sids[x] == present_sid){
					exist = true;
					break;
				}
			}
		}
		
		if (exist == false){
			sids.push(present_sid);
			$(this).parent().prevAll(".sid").find(".sidinput").attr('readonly','readonly');
			$(this).parent().prevAll(".sid").find(".sidinput").addClass("confirmed");
			$(this).fadeOut();
		}else{
			alert("Can't use the same Student ID more than once!");
		}
	});
	
	//api
	$('.sidinput').live("blur",function(){
		//first, check whether this field has been confirmed
		if ($(this).attr("readonly")!='readonly'){
			sid=$(this).val();
			
			//checking whether this student has confirmed this student id @todo
			exist = false;
			if (sids.length != 0){
				for (x in sids){
					if (sids[x] == sid){
						exist = true;
						break;
					}
				}
			}
			
			if (exist){
				savethis.parent().nextAll('.sidcheck').html('You have used this Student ID before');
			}else{
				url=site_url+'/ulibrary/api/sidcheck';
				savethis=$(this);
				savethis.parent().nextAll('.sidcheck').html('Checking for the Student ID');
				$.get(url,{'sid':sid},function(data,status){
					if (status=='success'){
						if (data['msg']=='Target located'){
							if (data['regi']){
								savethis.parent().nextAll('.sidcheck').html('<p>This Student ID has been registered</p>');
							}else{
								savethis.parent().nextAll('.sidcheck').html(
									'<div class="inside">'+
										'<p><span>English Name:</span>'+data['en']+'</p>'+
										'<p><span>Family Name:</span>'+data['ln']+'</p>'+
									'</div>'+
									'<div class="inside">'+
										'<p><span>First Name:</span>'+data['fn']+'</p>'+
										'<p><span>Class:</span>'+data['class']+'</p>'+
									'</div>'+
									'<button type="button" class="confirm_sid">Confirmed</button>'+
									'<p class="submit_check"></p>'
								);
							}
						}
						else{
							savethis.parent().nextAll('.sidcheck').html(data['msg']);
						}
					}
					else{
						//do something if it doesn't work
						alert('API failed! Please refresh the page. Sorry for the inconviency');
					}
				});		
			}
		}
	});
	
	//单击submit按钮的时候运行下面程序：检查output
	$("#enform").submit(function(e){
		//check whether required fields were filled
		//check sids
		var sids_check = true;
		$(".sid input").each(function(){
			if ($(this).val() == ''){
				sids_check = false;
				$(this).parent().nextAll(".sidcheck").html("Student ID is required");
			}else{
				//check whether this sid is confirmed
				if (! $(this).hasClass("confirmed")){
					sids_check = false;
					//do something @todo
					if ($(this).parent().nextAll(".sidcheck").find(".submit_check").html() != 'Please confirm the information'){
						$(this).parent().nextAll(".sidcheck").find(".submit_check").html("Please confirm the information");
					}
				}
			}
		});
		
		//check the responsible person's email
		if ($("#ulemail").val() == ''){
			email_check = false;
			if ($("#submit_check").html() != "Responsible person's email is required."){
				$("#submit_check").html("Responsible person's email is required.");
			}
		}else{
			email_check = true;
		}
		
		if (!(email_check && sids_check)){
			e.preventDefault();
		}
	});
	
});