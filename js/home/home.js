// JavaScript Document

$(document).ready(function(){
	var t;
	var i=1;
	var j=2;
	t=setInterval(function(){
/*		
			$("#slidebox"+i+","+"#slidebox"+j).animate(function(){
				left:"-=-20%";
			},3000);
*/
//			$("#slidebox"+i).css("left","-50%");
			$("#slidebox"+i).animate({left:"-50%"},3000);		
//			$("#slidebox"+j).css("left","0%");
			$("#slidebox"+j).animate({left:"0%"},3000);
			$("#slidebox"+j).removeClass("right");
			$("#slidebox"+i).addClass("back");

			i=i+1;
			if(i>4) i=1;
			j=j+1;
			if(j>4) j=1;
			$("#slidebox"+j).removeClass("back");
			$("#slidebox"+j).addClass("right");
			$("#slidebox"+j).css("left","50%");
			
		},5000);	
	
	$(".tags").click(function(){
		clearInterval(t);
		tid=$(this).find(".tid").val();
		t=setInterval();
	});
});