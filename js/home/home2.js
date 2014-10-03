// JavaScript Document

$(document).ready(function(){
	var t;
	var i=1;
	t=setInterval(function(){
		$("#slidebox"+i).animate({left:"-25%"},2000);
		j=i+1;
		if (j>3) j=1;
		$("#slidebox"+j).animate({left:"0%"},2500);
		k=j+1;
		if (k>3) k=1;
		$("#slidebox"+k).css("left","25%");
		i=i+1;
		if(i>3) i=1;
	},3000);	
	
	$(".tags").click(function(){
		clearInterval(t);
		tid=$(this).find(".tid").val();
		if (tid!=i)
			if (Math.abs(tid-i)==1){//相邻
				if (tid>i)
				{
					$("#slidebox"+i).animate({left:"-25%"},1000);
					j=i+1;
					if (j>3) j=1;
					$("#slidebox"+j).animate({left:"0%"},1300);
					k=j+1;
					if (k>3) k=1;
					$("#slidebox"+k).css("left","25%");
					i=j;
				}
				else
				{
					$("#slidebox"+i).animate({left:"25%"},1000);
					j=i-1;
					if (j<1) j=3;
					$("#slidebox"+j).animate({left:"0%"},1300);
					k=j-1;
					if (k<1) k=3;
					$("#slidebox"+k).css("left","-25%");
					i=j;
				}
			}
			else if(Math.abs(tid-i)==2){
				if (i==1)
				{
					$("#slidebox3").addClass("back");
					$("#slidebox3").css("left","50%");
					$("#slidebox3").removeClass("back");
					$("#slidebox1").animate({left:"-50%"},1000);
					$("#slidebox2").animate({left:"-25%"},1100);
					$("#slidebox3").animate({left:"0%"},1300);
					$("#slidebox1").addClass("back");
					$("#slidebox1").css("left","25%");
					$("#slidebox1").removeClass("back");
					i=3;
				}
				else
				{
					$("#slidebox1").addClass("back");
					$("#slidebox1").css("left","-50%");
					$("#slidebox1").removeClass("back");
					$("#slidebox3").animate({left:"50%"},1000);
					$("#slidebox2").animate({left:"25%"},1100);
					$("#slidebox1").animate({left:"0%"},1300);
					$("#slidebox3").addClass("back");
					$("#slidebox3").css("left","-25%");
					$("#slidebox3").removeClass("back");
					i=1;
				}
			}
		t=setInterval(function(){
			$("#slidebox"+i).animate({left:"-25%"},2000);
			j=i+1;
			if (j>3) j=1;
			$("#slidebox"+j).animate({left:"0%"},2500);
			k=j+1;
			if (k>3) k=1;
			$("#slidebox"+k).css("left","25%");
			i=i+1;
			if(i>3) i=1;
		},3000);	
	});
		
});
