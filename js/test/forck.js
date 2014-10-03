// JavaScript Document
$(document).ready(function(){
	
	var time;
	
	$('#a').click(function(){
		clearInterval(time);
	});
	
	$('#b').click(function(){
			eid=$("#eid").val();
			lid=$("#lid").val();
			$.get("/index.php/msg/update/getmsg?eid="+eid+"&lid="+lid,
				function(data,status){
					if (status=="success")
					{
						obj = eval ("(" + data + ")");
						for(x in obj)
						{
							$("#a").after('<p>'+obj[x].msg+'by'+obj[x].auth+'</p>');
						}
						alert("success");
					}
					else
					{
						alert(status);
					}
					
				});
	});
});