$(document).on('click', '.searchCertify',function (event){
    event.preventDefault();
    
    $.ajax({
        url: $('#searchForm').attr('action'),
        type: 'POST',
        data: $('#searchForm').serialize(),
        /*data: { CPFMatricula: $('#form_CPFMatricula').val() },*/
        success: function (data) {
            $('.certifyResults').html(data);
        }
    });
});
