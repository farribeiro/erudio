var url = document.location.toString();

if (url.match('#')) {
    $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
} 

$("#link_recovery").click(function() {
        $("#form_login").hide();
        $("#form_recovery").css('marginRight', '10%').show();
        $(this).hide();
        $("#link_login").show();
});

$("#link_login").click(function() {
        $("#form_login").show();
        $("#form_recovery").hide();
        $(this).hide();
        $("#link_recovery").show();
});

$('.cpf').keypress(function(event) {
        var key = (window.event) ? event.keyCode : event.which;
        if ((key > 47 && key < 58)) return true;
        else {
            if (key == 8 || key == 0 || key == 9) return true;
            else return false;
                }
});

$('.getEmail').blur(function() {
    if ($(this).val() !== '')
    {
        var url = $('.setEmail').attr('label');
        $.ajax({
            url: url, dataType:'text', type: 'POST', data:{ 'cpf': $(this).val() }, success: function (e) {
                if (e == 'no_email') {
                        $.bootstrapGrowl("Nenhum email foi encontrado para este cadastro, , tente mais tarde ou entre em contato com a Acessoria de Informatização da SME.", { type: 'danger', delay: 3000 });
                } else if (e == 'no_person') {
                        $.bootstrapGrowl("Não há usuário para este CPF, tente mais tarde ou entre em contato com a Assessoria de Informatização da SME.", { type: 'danger', delay: 3000 });
                } else {
                        $("#email").val(e);
                }
            }
        });
    }
});

$(".enter").click(function (){
        $(".loading").show();
});