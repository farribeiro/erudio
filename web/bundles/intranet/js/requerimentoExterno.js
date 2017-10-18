$(document).ready(function (){
    $('.outro').hide();
    
    $.ajax({
        url: $('.title_url').attr('id'),
        type: 'GET',
        success: function (data){
            var select = "<select class='form-control selForm' name='selectForm'><option>Selecione abaixo</option>";
            for (var i=0; i<data.length; i++) {
                select += "<option value='" + data[i].id + "'>" + data[i].nome + "</option>";
            }
            select += "</select>";
            
            $('#chooseForm').append(select);
            
            $('.selForm').change(function (){
                $('.form-requerimento').hide();
                $("#" + $(this).val()).show();
            });
        }
    });
    
    $('.btnRSimples').click(function (event){
        event.preventDefault();
        var url = $('#formRSimples').attr('action');
        
        $.ajax({
            url: url,
            type: 'post',
            data: $('#formRSimples').serialize(),
            success: function (data) {
                if (data.success) {
                    var urlProtocolo = $('.divSimples').attr('label');
                    urlProtocolo = urlProtocolo.replace("00", data.id);
                    
                    $.bootstrapGrowl("Requerimento feito com sucesso! Clique no link abaixo para imprimir seu requerimento.", { type: 'success', delay: 3000 });
                    $('.divSimples').html("Este é o número do seu protocolo, por favor, anote: " + data.protocolo + " <br /><br /> <a href='" + urlProtocolo + "'>Clique aqui para imprimir sua requisição.<a>");
                } else {
                    $.bootstrapGrowl("Houve um problema com a requisição do requerimento.<br /><br />Erro: " + data.error, { type: 'danger', delay: 3000 });
                }
            }
        });
    });
    
    $('#objective').change(function (){
        if ($(this).val() === "Outro") {
            $('.outro').show();
        } else {
            $('.outro').hide();
        }
    });
    
    $('.btnRTempo').click(function (event){
        event.preventDefault();
        var url = $('#formRTempo').attr('action');
        
        if ($('.objetivoTempo').val() === 'Outro') {
            var objetivo = $('.outroTempo').val();
        } else {
            var objetivo = $('.objetivoTempo').val();
        }
        
        $.ajax({
            url: url,
            type: 'post',
            data: { 
                "anoInicio": $('.anoInicioTempo').val(), 
                "celular": $('.celularTempo').val(), 
                "cpf": $('.cpfTempo').val(), 
                "email": $('.emailTempo').val(), 
                "telefone": $('.foneTempo').val(), 
                "nome": $('.nomeTempo').val(), 
                "objetivo": objetivo, 
                "outro": $('.outroTempo').val(), 
                "rg": $('.rgTempo').val() 
            },
            success: function (data) {
                if (data.success) {
                    var urlProtocolo = $('.divTempo').attr('label');
                    urlProtocolo = urlProtocolo.replace("00", data.id);
                    
                    $.bootstrapGrowl("Requerimento feito com sucesso! Clique no link abaixo para imprimir seu requerimento.", { type: 'success', delay: 3000 });
                    $('.divTempo').html("Este é o número do seu protocolo, por favor, anote: " + data.protocolo + " <br /><br /> <a href='" + urlProtocolo + "'>Clique aqui para imprimir sua requisição.<a>");
                } else {
                    $.bootstrapGrowl("Houve um problema com a requisição do requerimento.<br /><br />Erro: " + data.error, { type: 'danger', delay: 3000 });
                }
            }
        });
    });
});