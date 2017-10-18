$.ajax({
    'url': $('#urlFormacoes').val(),
    'type': 'GET',
    'success': function (data){
        if (data.length > 0) {
            table = document.createElement('table');
            $(table).addClass('table table-striped table-hover');
            thead = document.createElement('thead');
            tr = document.createElement('tr');
            thNome = document.createElement('th');
            $(thNome).html('Nome');
            thPublico = document.createElement('th');
            $(thPublico).html('Público Alvo');
            thDataInicio = document.createElement('th');
            $(thDataInicio).html('Data de Início');
            thDataTermino = document.createElement('th');
            $(thDataTermino).html('Data de Término');
            thLimiteVagas = document.createElement('th');
            $(thLimiteVagas).html('Vagas Disponíveis');
            thOpcoes = document.createElement('th');
            $(thOpcoes).html('Opções');

            $(tr).append(thNome);
            $(tr).append(thPublico);
            $(tr).append(thDataInicio);
            $(tr).append(thDataTermino);
            $(tr).append(thLimiteVagas);
            $(tr).append(thOpcoes);

            $(thead).append(tr);
            $(table).append(thead);

            tbody = document.createElement('tbody');
            for (i=0; i<data.length; i++) {
                trItem = document.createElement('tr');

                tdNome = document.createElement('td');
                aNome = document.createElement('a');
                link = $('#urlFormacao').val();
                link = link.replace("/0", "/"+data[i].id);
                $(aNome).html(data[i].nome).attr('href',link).css('background', 'transparent').addClass('detalhesFormacao');
                $(tdNome).append(aNome);
                $(trItem).append(tdNome);

                tdPublico = document.createElement('td');
                $(tdPublico).html(data[i].publicoAlvo);
                $(trItem).append(tdPublico);

                tdDataInicio = document.createElement('td');
                $(tdDataInicio).html(data[i].dataInicio);
                $(trItem).append(tdDataInicio);

                tdDataTermino = document.createElement('td');
                $(tdDataTermino).html(data[i].dataTermino);
                $(trItem).append(tdDataTermino);

                tdLimiteVagas = document.createElement('td');
                if (data[i].limiteVagas) {
                    $(tdLimiteVagas).html(data[i].limiteVagas);
                } else {
                    $(tdLimiteVagas).html('Vagas Ilimitadas');
                }
                $(trItem).append(tdLimiteVagas);

                tdOpcoes = document.createElement('td');
                aOpcoes = document.createElement('a');
                link = $('#urlFormacaoExterna').val();
                link = link.replace("/0", "/"+data[i].id);
                $(aOpcoes).html('Inscrever-se').attr('href',link).css('background', 'transparent').addClass('matriculaFormacao');
                $(tdOpcoes).html(aOpcoes);
                $(trItem).append(tdOpcoes);

                $(tbody).append(trItem);
            }
            $(table).append(tbody);

            $("#formacoes").append(table);

            $(".detalhesFormacao").click(function(ev){
                ev.preventDefault();

                $.ajax({
                    type: "GET",
                    url: $(this).attr("href"),
                    success: function(retorno) {
                        $("#divModal").html(retorno);
                    }
                });
                $('#modalDialog').modal("show");

            });

            $(".matriculaFormacao").click(function(ev){
                $.ajax({
                    type: "GET",
                    url: $(this).attr("href"),
                    success: function(retorno) {
                        $("#divModalForm").html(retorno);
                    }
                });
                $('#modalDialogForm').modal("show");
                ev.preventDefault();
            });
        } else {
            $("#formacoes").append('<hr /> No momento, não há nenhuma formação pública disponível.<br /><br />');
        }
    }
});