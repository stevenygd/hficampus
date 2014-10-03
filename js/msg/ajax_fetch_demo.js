// JavaScript Document

$(document).ready(function(){
	
	var t;
	t=self.setInterval(
		function(){
			$('.box').each(function(){
				eid=$(this).find('.uid').val();
				lid=$(this).find('.lid').val();
				lim=1;
				$.get("http://127.0.0.1/index.php/msg/update/getmsg?eid="+eid+"&lim="+lim+"&lid="+lid,
					  function(data,status){
					   //json
					   var obj=eval("("+data+")");
					   //deal something with obj
					   
					   //change the content
					   if ((obj.from != false)&&(obj.to!=false))
					   {
						   if (obj.from[0].id < obj.to[0].id)
						   {
							   $("#box"+eid).find(".msg").html(obj.to[0].msg);
							   $("#box"+eid).find(".msg").html(obj.to[0].msg);
						   }
						   else
						   {
							   $("#box"+eid).find(".msg").html(obj.from[0].msg);
						   }
					   }
					   else
						  if(obj.from==false)
						  {
							 $("#box"+eid).find(".msg").html(obj.to[0].msg);
						  }
						  else
						  {
							 $("#box"+eid).find(".msg").html(obj.to[0].msg);
						  }
						//alert(obj.from);
				});		
			});
		}
	,5000);
});