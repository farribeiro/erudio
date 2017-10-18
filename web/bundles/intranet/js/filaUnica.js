$(".searchFila").click(function (ev){
    ev.preventDefault();

    $.ajax({
        url: $("#urlFilaUnica").val(),
        type: 'GET',
        data: { 'protocolo': $("#protocolo").val() },
        success: function (data) {
            if (data.status == true) {
                $(".ajax-itens").html('');
                var dataInscricaoBox = null;
                for (i=0; i < data.fila.length; i++) {
                    var classe = '';
                    var bold = '';
                    var dataInscricao = data.fila[i].dataInscricao.date;        						
                    if (data.inscricao.protocolo == data.fila[i].protocolo) { dataInscricaoBox = dataInscricao; classe = 'class="success"'; bold = 'style="font-weight: bold;" '; }
                    $(".ajax-itens").append("<tr "+ bold + classe +"><td>" + (i+1) + "</td><td>" + data.fila[i].protocolo + "</td><td>" + dataInscricao + "</td></tr>");
                }

                $(".status").html(data.inscricao.status);
                $(".zone").html(data.inscricao.zoneamento);
                $(".room").html(data.inscricao.anoEscolar);
                $(".startDate").html(dataInscricaoBox);

                if ($("#results").is(":hidden")) {
                    $("#results").slideToggle();
                }
            } else {
                $.bootstrapGrowl(data.mensagem, { type: 'danger', delay: 3000 });
            }
        }
    });       		
});