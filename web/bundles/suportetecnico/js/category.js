$(document).ready(function (){


    $(document).on('blur', '#editInline', function (){
        var val = $(this).val();
        var url = $(this).attr('alt');
        var parent = $(this).parent().html(val);

        $.ajax({
            url : url,
            type: 'POST',
            data: { 'nome': val }
        });
    });

    $('.add_category').click(function (ev){
        ev.preventDefault();
        $.ajax({
            url : $(this).attr('href'),
            type: 'GET',
            global: false,
            success: function (data) {
                $(".innerCategory").html('');
                $(".new_category").html(data);
            }
        });
    });

    $('.add_sub_category').click(function (){
        var id = $(this).attr('alt');
            $.ajax({
                url : $(".add_category").attr('label'),
                type: 'POST',
                data: { 'categoria': $(this).attr('label') },
                global: false,
                success: function (data) {
                    $(".new_category").html('');
                    $(".innerCategory").html('');
                    $("#"+id).append("<ul><li class='innerCategory'>"+data+"</li></ul>");
                }
            });
    });

    $('.remove_category').click(function (ev){
        ev.preventDefault();
        var id = $(this).attr('data-id');
        var url = $(this).attr('href');
        if($( ".arrow-"+id ).has( "span" ).length > 0) {
            bootbox.confirm({
                title: "<h3>Atenção!</h3>",
                message: "Esta categoria <strong>possui</strong> sub-categorias, se você confirmar a exclusão, <strong>todas</strong> as sub-categorias pertencentes a ela serão <strong>eliminadas</strong> também.<br /><br />Você <strong>confirma</strong> a ação?",
                buttons: {
                    'cancel': {
                        label: 'Cancelar',
                        className: 'btn-default'
                    },
                    'confirm': {
                        label: 'Excluir',
                        className: 'btn-primary'
                    }
                },
                callback: function (result) {
                    if (result) {
                        window.location = url;
                    }
                }
            }); 
        } else {
            window.location = url;
        }
    });
        
        $(".categoryitem, .arrowlist").click(function (){
            if($( this ).has( "input" ).length === 0) {
                var id = $(this).attr("data-id");
                var arrow = $(".arrow-"+id).html();
                if (arrow !== '') {
                    if ($('.cat-sign-'+id).hasClass('glyphicon-chevron-down')) {
                        $('.cat-sign-'+id).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
                        $('.cat-'+id).show();
                    } else {
                        $('.cat-sign-'+id).removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
                        $('.cat-'+id).hide();
                    }
                }
            }
        });
        
        $(".subcategoryitem, .subarrowlist").click(function (){
            if($( this ).has( "input" ).length === 0) {
                var id = $(this).attr("data-id");
                var arrow = $(".arrow-"+id).html();
                if (arrow !== '') {
                    if ($('.cat-sign-'+id).hasClass('glyphicon-chevron-down')) {
                        $('.cat-sign-'+id).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
                        $('.cat-'+id).show();
                    } else {
                        $('.cat-sign-'+id).removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
                        $('.cat-'+id).hide();
                    }
                }
            }
        });
        
        $('.cat-add').click(function (){
            $('.add-cat').show();
        });
        
        $('.cancel-add-cat').click(function (){
            $('#nomeCategoria').val('');
            $('.add-cat').hide();
        });
});