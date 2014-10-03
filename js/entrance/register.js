// JavaScript Document
//by LXS

$(document).ready(function(){
    //input effect
    /*$(".login_box").each(function(){
        if($(this).val()!="")
            {
                $(this).prev().css("display","none");
            }
                                       
    });

    $("input").blur(function(){
        if ($(this).val()=='')
            {
                $(this).prev().css("display","inline");
            }
            
    });
    
    $("input").focus(function(){
        $(this).prev().css("display","none");
    });*/
	
	$(".ensubmit").mouseover(function(){
		$(this).css("color","red");
	});
	$(".ensubmit").mouseout(function(){
		$(this).css("color","#0C034D");
	});
                  
    $("#next").click(function(){
		$(".err").empty();
		if ($("#pwf").val()==$("#pwc").val())
			{
				$(".pw").fadeOut("slow");
				$("input.login_box").attr("readonly","readonly");
				$(".login_box").css("width","250px");
				$("input").css("box-shadow","none");
				$(".first").fadeOut("slow",function(){
					$("#sub").fadeTo("slow",1);
					$(".next").fadeIn("slow");
				});
			}
		else
			{
				$("#pwerr").css("display","inline");
			}
	});
	
	$("#back2").click(function(){
		$("#pwf").empty();
		$("#pwc").empty();
		$("input.login_box").removeAttr("readonly");
		$(".login_box").css("width","150px");
		$("#sub").fadeTo("slow",0);
		$(".next").fadeOut("slow",function(){
			$(".first").fadeIn("slow");
			$(".pw").fadeIn("slow");
			$(".login_box").css("box-shadow","0.5px 0.5px 4px 1px rgba(153, 153, 153, 0.53)");
		});
	});
	
	//rsa encrypt
	var e="10001";
    var rsa = new RSAKey();
    $("#send").click(function(){      
		rsa.setPublic(n,e);
        var res=rsa.encrypt(hex_sha1($("#pwf").val()));
        $("#pwf").val(hex2b64(res));
        //加密完发送
        $("#register").submit();		
	});	
});
	
