var oldVal = null;

$(document).on('dblclick', '.member-item', function (){
	var parent = $(this).parent();
	var text = $(this).html();
	oldVal = text;
	var url = $(this).attr('id');
	$(this).remove();
	
	text = $.trim(text);
	var input = document.createElement("input");
	input.type = "text";
	input.alt = url;
	input.id = "editInline";
    input.value = text;
    input.placeholder = "Digite o nome da equipe.";
    input.className = "form-control";
    //input.style = "width: 100%;";
    
	parent.prepend(input);
});

$(document).on('blur', '#editInline', function (){
	var val = $(this).val();
	var parent = $(this).parent();
	var url = $(this).attr('alt');	
	$(this).remove();
	
	if (val != '') {
		parent.prepend("<span id='"+url+"' class='member-item border temp'>Salvando...</span>");
		
		$.ajax({
			url : url,
			type: 'POST',
			data: { 'nome': val },
			success: function (data) {
				if (data == 'success') {
					$('.temp').remove();
					parent.prepend("<span id='"+url+"' class='member-item border'>"+val+"</span>");
					$.bootstrapGrowl("Equipe editada com sucesso.", { type: 'success', delay: 5000 });
				} else {
					$.bootstrapGrowl(data, { type: 'danger', delay: 5000 });
				}
			}
		});
	} else {
            parent.prepend("<span id='"+url+"' class='member-item border'>"+oldVal+"</span>");
            $.bootstrapGrowl("A equipe não pode ter um nome em branco.", { type: 'danger', delay: 5000 });
	}
});