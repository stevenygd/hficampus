// JavaScript Document

//LXS


$(document).ready(function(){
    
	$(".link").mouseover(function(){
		$(this).find("a").css("color","red");
	});
	
	$(".link").mouseout(function(){
		$(this).find("a").css("color","#0C034D");
	});
	
    $(".login_box").each(function(){
        if($(this).val()!="")
            {
                  $(this).prev().css("display","none");
            }
            
    });
    
    $("input").blur(function(){

		if ($(this).val()=="")

			{

			$(this).prev().css("display","inline");

			}
        else
            {
            $(this).prev().css("display","none");
            }

	});

	$("input").focus(function(){

		$(this).prev().css("display","none");

	});
    
    /*

	*LOGIN SAMPLE

	*related URL:

	*http://www.w3school.com.cn/htmldom/met_form_submit.asp

	*http://www.w3school.com.cn/htmldom/prop_form_onsubmit.asp

	*http://www.w3school.com.cn/jquery/event_submit.asp		

	*/	

	//单击submit按钮的时候运行下面程序：

	$("#submit").click(function(){

		//alert($("#password").val());

		//检测password的值,并根据是否是空来用sha1加密

		if ($("#password").val() !== "")

		{

			encrypt=hex_sha1(hex_sha1($("#password").val())+$("#login_key").val());

			//alert(encrypt);

			$("#password").val(encrypt);

			//alert("GOOD!");		

		}

		else{

			encrypt=hex_sha1(hex_sha1("")+$("#login_key").val());

			//alert(encrypt);

			$("#password").val(encrypt);

			//alert("GOOD!");	

		}
        
		//加密完之后发送加密过的信息

        $("#login").submit();

	});

})

