$(document).ready(function(){
	$("#btnAtualizar").click(function(ev){		
		if($("#password").val() != $("#repeat_password").val()){
			$.bootstrapGrowl("Os campos 'Modificar Senha' e 'Repetir Senha' devem ser iguais, por favor, verifique.", { type: 'danger', delay: 6000 });
			ev.preventDefault();
			return false;
		}
	});
	
	$("#btnAtualizarAjax").click(function(ev){
		ev.preventDefault();
		
		if($("#password").val() != $("#repeat_password").val()){
			$.bootstrapGrowl("Os campos 'Modificar Senha' e 'Repetir Senha' devem ser iguais, por favor, verifique.", { type: 'danger', delay: 6000 });
			return false;
		}
		
		$.ajax({
			url: $(this).attr('label'),
			type: 'POST',
			data: { password: $("#password").val() },
			success: function (data)
			{
				if (data != "error") {
					$.bootstrapGrowl("Senha alterada com sucesso.", { type: 'success', delay: 3000 });
					$("#ajaxContent").html(data);
				} else {
					$.bootstrapGrowl("O sistema não pôde mostrar os vínculos, aguarde um pouco e tente novamente.", { type: 'danger', delay: 3000 });
				}
			}
		});
	});
	
	$("#btnCreateUser").click(function (e){
		e.preventDefault();

		if ($("#newUsername").val() == "")
		{
			$.bootstrapGrowl("Não é possível criar um usuário com o CPF em branco.", { type: 'danger', delay: 6000 });
			return false;
		}
		
		if ($("#newPassword").val() == "" && $("#new_repeat_password").val() == "")
		{
			$.bootstrapGrowl("Não é possível criar uma senha em branco.", { type: 'danger', delay: 6000 });
			return false;
		}
		
		if ($("#newPassword").val() || $("#new_repeat_password").val())
		{
			if($("#newPassword").val() != $("#new_repeat_password").val()){
				$.bootstrapGrowl("Os campos 'Modificar/Nova Senha' e 'Repetir Senha' devem ser iguais, por favor, verifique.", { type: 'danger', delay: 6000 });
				return false;
			}
		}
		
		var reg = /^[0-9a-zA-Z]{6,}$/;
		if($("#newPassword").val() && !reg.test($("#newPassword").val())){
			$.bootstrapGrowl("As senhas devem possuir pelo menos 6 caracteres, por favor, verifique.", { type: 'danger', delay: 6000 });
			return false;
		}
		
		$.ajax({url: $(this).attr('label'), dataType:'text', type: 'POST', data: $("#formCreateUser").serialize(), success: function (e){
			$("#newPassword, #new_repeat_password, #newUsername").val("");
			
			if (e == 'exist') {
				$.bootstrapGrowl("Já existe um usuário para este CPF, caso não lembre sua senha, utilize o link 'esqueci minha senha' e siga os passos descritos abaixo.", { type: 'info', delay: 3000 });
			} else if (e == 'no_person') {
				$.bootstrapGrowl("Não há cadastro de pessoa para este CPF, tente mais tarde ou entre em contato com o DGP.", { type: 'danger', delay: 3000 });
			} else if (e == 'success_profile') {
				$.bootstrapGrowl("Seu usuário já existe e foi atualizado, utilize seu CPF e Senha para acessar o sistema, caso não lembre sua senha, utilize o link 'esqueci minha senha' e siga os passos descritos abaixo.", { type: 'success', delay: 3000 });
			} else {
				$.bootstrapGrowl("Usuário criado com sucesso.", { type: 'success', delay: 3000 });
			}
		}});
	});
	
	$("#newPassword").blur(function (){
		var reg = /^[0-9a-zA-Z]{6,}$/;
		if($(this).val() && !reg.test($(this).val())){
			$.bootstrapGrowl("As senhas devem possuir pelo menos 6 caracteres, por favor, verifique.", { type: 'danger', delay: 6000 });
		}
	});
	
	$("#newPassword, #new_repeat_password").blur(function(){
		if ($("#password").val() && $("#repeat_password").val())
		{
			if($("#password").val() != $("#repeat_password").val()){
				$.bootstrapGrowl("Os campos 'Modificar/Nova Senha' e 'Repetir Senha' devem ser iguais, por favor, verifique.", { type: 'danger', delay: 6000 });
			}
		}
		
	});
	
	$("#btnSendNewPassword").click(function(ev){
		if ($("#password").val() == "" && $("#repeat_password").val() == "")
		{
			$.bootstrapGrowl("Não é possível criar uma senha em branco.", { type: 'danger', delay: 6000 });
			ev.preventDefault();
		}
		
		if ($("#password").val() || $("#repeat_password").val())
		{
			if($("#password").val() != $("#repeat_password").val()){
				$.bootstrapGrowl("Os campos 'Modificar/Nova Senha' e 'Repetir Senha' devem ser iguais, por favor, verifique.", { type: 'danger', delay: 6000 });
				ev.preventDefault();
			}
		}
	});
	
	$("#password").blur(function (){
		var reg = /^[0-9a-zA-Z]{6,}$/;
		if($(this).val() && !reg.test($(this).val())){
			$.bootstrapGrowl("As senhas devem possuir pelo menos 6 caracteres, por favor, verifique.", { type: 'danger', delay: 6000 });
		}
	});
	
	$("#password, #repeat_password").blur(function(){
		if ($("#password").val() && $("#repeat_password").val())
		{
			if($("#password").val() != $("#repeat_password").val()){
				$.bootstrapGrowl("Os campos 'Modificar/Nova Senha' e 'Repetir Senha' devem ser iguais, por favor, verifique.", { type: 'danger', delay: 6000 });
			}
		}
		
	});
});