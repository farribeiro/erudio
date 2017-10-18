$(document).ready(function () {
	var count = 0;
	
	$.ajax({
		url: $('#notification_area').attr('label'),
		type: 'POST',
		data: { user_id: $('.fundo').attr('label') },
		success: function (data)
		{
			if (data != "error") {
				$(".notification_total").html(data);
			} else {
				
			}
		}
	});
	
	$(".notification_switch").click(function (){		
                $('#notificationsDiv').css('z-index', 9);
		$("#notification_area").html("<div id='notifications_title'>Notificações</div>");		
		$("#notification_area").slideToggle('slow',function (){
			if ($("#notification_area").is(":hidden")) {
				$(".notification_switch").removeClass('icon_active');
				$(".notification_switch").addClass('icon_closed');	            				
                                $('#notificationsDiv').css('z-index', -1);
			} else {
			  	$(".notification_switch").addClass('icon_active');
			  	$(".notification_switch").removeClass('icon_closed');
			  	
			  	$.ajax({
					url: $('#notification_area').attr('alt'),
					type: "GET",
					global: false,
					success: function (data)
					{
						if (data != "error") {
                                                    $("#notification_area").html(data);
						} else {
							
						}
					}
				});
			}
		});
	});
});