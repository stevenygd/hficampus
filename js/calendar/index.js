// JavaScript Document
// by LXS
$(document).ready(function() {
    
        // page is now ready, initialize the calendar...
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            
            $('#calendar').fullCalendar({
                header: {
                    left:   'prev,next today',
                    center: 'title',
                    right:  'month,basicWeek,basicDay'
                },
                editable: true,
				events:{
						url: site_url+'/calendar/'+cid+'/event',
						type: 'GET',
						
/*  					data: {
							custom_param1: 'something',
							custom_param2: 'somethingelse'
						},
*/
						error: function() {
							alert('there was an error while fetching events!');
						},
						//color: 'yellow',   // a non-ajax option
						//textColor: 'black' // a non-ajax option

				},
				eventClick:function(calEvent,jsEvent,view){
					$("#event").slideUp(function(){
						if (calEvent.end==null)
						{
							end=calEvent.start.toDateString().substr(0,16);	
						}
						else
						{
							end=calEvent.end.toDateString().substr(0,16)	
						}
						if (calEvent.gid==0)
						{
							gname="Self"	
						}
						else
						{
							gname=calEvent.name	
						}
						$("#event").html('<p class="label">From</p><p class="content">'
						+gname+'</p><p class="label">Title</p><p class="content">'
						+calEvent.title+'</p><p class="label">Start</p><p class="content">'
						+calEvent.start.toDateString().substr(0,16)+'</p><p class="label">End</p><p class="content">'
						+end+'</p><p class="label">Details</p><p class="content">'
						+calEvent.description+'</p><p id="close2" class="close">Close</p>');
						$("#calendar").animate({opacity:'0.5'});
						$("#event").slideDown();
					});
				}
            });
			
			$("#event").delegate("#close2","click",function(){
				$("#event").slideUp(function(){
					$("#event").empty();
				});
				$("#calendar").animate({opacity:'1'});
				$("#calendar").removeClass("close");
			});
			
			$("#add_events").mouseover(function(){
				$(this).addClass("fc-state-hover");
			});
			
			$("#add_events").mouseout(function(){
				$(this).removeClass("fc-state-hover");
			});
			
			$("#add_events").mousedown(function(){
				$(this).addClass("fc-state-down");
			});
			
			$("#add_events").click(function(){
				$("#calendar").animate({opacity:'0.5'});
				$("#add_form").slideDown();
			});
						
			$("#submit").click(function(){
				gid=$(".group").val();
				ename=$(".name").val();
				smonth=$(".smonth").val();
				sday=$(".sday").val()
				syear=$(".syear").val()
				emonth=$(".emonth").val()
				eday=$(".eday").val()
				eyear=$(".eyear").val()
				description=$(".description").val();
				$.post('calendar/'+gid+'/event/create',
				{
					"name":ename,
					"start":smonth+' '+sday+' '+syear,
					"end":emonth+' '+eday+' '+eyear,
					"description": description,
					"type": 1
				},
				function(data){
					if (data==0)
					{
						alert("success");
						window.location.reload();
					}
					else
					{
						alert(data);
					}
				});
			});
			
			$("#close1").click(function(){
				$("#add_form").slideUp();
				$("#add_events").removeClass("fc-state-down");
				$("#calendar").animate({opacity:'1'});
			});
});
    