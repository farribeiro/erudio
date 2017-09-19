(function (){
    var util = angular.module('util',['structure', 'validator', 'angular-md5', 'angular-sha1', 'erudioConfig', 'ngMaterial', 'auth']);
    
    util.service('Util', ['$timeout', 'Structure', 'Validator', 'Restangular', 'md5', 'sha1', 'ErudioConfig', '$mdToast', '$filter', '$http', 'Auth', function($timeout, Structure, Validator, Restangular, md5, sha1, ErudioConfig, $mdToast, $filter, $http, Auth) {
        
        this.teste = function(){ console.log('teste'); };
        
        //SETA TITULO DA TOOLBAR
        this.setTitulo = function (titulo) { $('.titulo-toolbar').html(titulo); };
        
        //BUSCA O ENDERECO DO TEMPLATE DE LISTA
        this.getTemplateLista = function () { return ErudioConfig.dominio+ErudioConfig.urlTemplates+'lista.html'; };
        
        //BUSCA O ENDERECO DO TEMPLATE DE BUSCA
        this.getTemplateBuscaSimples = function () { return ErudioConfig.dominio+ErudioConfig.urlTemplates+'buscaSimples.html'; };
        
        //BUSCA O ENDERECO DO TEMPLATE DE BUSCA CUSTOM
        this.getTemplateBuscaCustom = function () { return ErudioConfig.dominio+ErudioConfig.urlTemplates+'buscaCustom.html'; };
        
        //BUSCA O ENDERECO DO TEMPLATE DE LISTA ESPECIFICA
        this.getTemplateListaEspecifica = function (modulo) { return ErudioConfig.dominio+'/apps/'+modulo+'/partials/lista.html'; };
        
        //BUSCA O ENDERECO DO TEMPLATE DE FORM
        this.getTemplateForm = function () { return ErudioConfig.dominio+ErudioConfig.urlTemplates+'form.html'; };
        
        //BUSCA O ENDERECO DO TEMPLATE DE LEITURA
        this.getTemplateLeitura = function () { return ErudioConfig.dominio+ErudioConfig.urlTemplates+'leitura.html'; };
        
        //BUSCA BLOCO DE INPUT CUSTOM
        this.getInputBlockCustom = function (modulo, arquivo) { return ErudioConfig.dominio+'/apps/'+modulo+'/partials/'+arquivo+'.html'; };
        
        //RETORNA ENDERECO BUSCA CUSTOM
        this.setBuscaCustom = function (url) { return ErudioConfig.dominio + url + '/busca.html'; };
        
        //APLICAR MASCARAS
        this.aplicarMascaras = function () {
            $('.maskCNPJ').mask('99999999999999');
            $('.maskCEP').mask('99999999');
            $('.maskTelefone').mask('(99)999999999');
            $('.maskNumeroCasa').mask('99999');
            $('.maskOrdem').mask('999');
            $('.maskAlunos').mask('999');
            $('.maskCargaHoraria').mask('999999');
            $('.maskHora').mask('99:99');
            $('.maskDoisNumeros').mask('99');
            $('.maskData').mask('99/99/9999');
        };
        
        //VALIDA CAMPO
        this.validaCampo = function() {
            $('input').change(function(){ 
                if (!$(this).hasClass('md-input-invalid')) { 
                    $(this).parent().removeClass('md-input-invalid');
                    if ($(this).parent().parent().parent().parent().hasClass('autocomplete-wrap')) {
                        $(this).parent().parent().parent().parent().find('.md-input-message-animation').removeAttr('style');
                    }
                    $(this).parent().find('.md-input-message-animation').removeAttr('style');
                }
            });
            $('md-select').blur(function(){ 
                if (!$(this).hasClass('md-input-invalid')) { 
                    $(this).parent().removeClass('md-input-invalid');
                    $(this).parent().find('.md-input-message-animation').removeAttr('style');
                }
            });
        };

        //FORÇAR VALIDAR CAMPO
        this.validarCampos = function () {
            var self = this;
            $('input').each(function(){ 
                if (!$(this).hasClass('md-input-invalid')) { 
                    $(this).parent().removeClass('md-input-invalid');
                    if ($(this).parent().parent().parent().parent().hasClass('autocomplete-wrap')) {
                        $(this).parent().parent().parent().parent().find('.md-input-messages-animation').removeAttr('style');
                    }
                    $(this).parent().find('.md-input-message-animation').removeAttr('style');
                }
            });
            $('md-select').each(function(){ 
                if (!$(this).hasClass('ng-invalid')) { 
                    $(this).parent().removeClass('md-input-invalid');
                    $(this).parent().find('.md-input-message-animation').removeAttr('style');
                } else {
                    var elem = $(this).find('md-option[aria-selected="true"]');
                    if (!self.isVazio(elem.attr('value'))) {
                        $(this).removeClass('ng-invalid').removeClass('ng-invalid-required').addClass('ng-valid').addClass('ng-valid-required');
                        $(this).parent().removeClass('md-input-invalid');
                        $(this).parent().find('.md-input-message-animation').removeAttr('style');
                    }
                }
            });
        };

        this.inicializaEnter = function (){
            $("input").keypress(function(event){
                var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { return true; } else { return false; }
            });
        };

        //MOSTRA MODAL DE EXCLUSAO
        this.modalExclusao = function(event, title, content, label, $mdDialog) {
            var confirm = $mdDialog.confirm().title(title).textContent(content).ariaLabel(label).targetEvent(event).ok('Remover').cancel('Cancelar');
            return confirm;
        };
        
        //PEGA INDICE DO OBJETO NA LISTA
        this.buscaIndice = function (id, list) {
            var retorno = false;
            list.forEach((item, i) => { if (id === item.id) { retorno = i; } });
            return retorno;
        };
        
        //MOSTRA MODAL SIMPLES
        this.modal = function(event, url, $mdDialog) { $mdDialog.show({templateUrl: url, targetEvent: event, clickOutsideToClose:true}); };
        
        //MOSTRA ALERT SIMPLES
        this.alert = function(event, title, content, label, $mdDialog) {
            var confirm = $mdDialog.alert().title(title).clickOutsideToClose(true).textContent(content).ariaLabel(label).targetEvent(event).ok('Entendi!');
            return confirm;
        };
        
        //BUSCAR ESTADOS
        this.getEstados = function () { return this.buscar('estados',null); };
        
        //BUSCAR CIDADES
        this.getCidades = function (estado) { return this.buscar('cidades',{ estado: estado }); };
        
        //VERIFICA VARIAVEL VAZIA
        this.isVazio = function (value) { if (value === null || value === undefined || value === "" || value.length === 0) { return true; } else { return false; } };
        
        //VERIFICA SE É CADASTRO OU EDICAO
        this.isNovoLabel = function (value) { if (value === 'novo') { return 'Cadastrar'; } else { return 'Editar'; } };
        
        //VERIFICA SE É CADASTRO OU EDICAO
        this.isNovo = function (value) { if (value === 'novo') { return true; } else { return false; } };
        
        //VERIIFCA SE É NOVO OBJETO
        this.isNovoObjeto = function (obj) { if (this.isVazio(obj.id)) { return true; } else { return false; } };
        
        //BUSCA ESTRUTURA DE OBJETO 
        this.getEstrutura = function (tipo) { return Structure.getObjeto(tipo); };
        
        //VALIDA FORM
        this.validar = function (formId) {
            var self = this; var retorno = true;

            $("form#"+formId+" :input").each(function(){
                if ( !self.isVazio($(this).attr('required')) && self.isVazio($(this).val()) ) {
                    $(this).parent().addClass('md-input-invalid');
                    $(this).parent().find('.md-input-message-animation').attr('style','opacity: 1; margin-top: 0px;');
                    var auto = $(this).parent().parent().parent().parent();
                    if (auto.hasClass('autocomplete-wrap')) {
                        auto.find('.md-input-messages-animation').attr('style','height: 19px; opacity: 1; position: absolute; left: 38px;');
                        if (!auto.hasClass('fix-wrap')) { auto.find('.md-input-messages-animation').css('margin-top','-38px;'); }
                        auto.find('.md-input-message-animation').attr('style','opacity: 1; margin-top: -4px;');
                    }
                    retorno = false;
                } 
            });

            $("md-select").each(function(){
                if ( !self.isVazio($(this).attr('required')) && $(this).hasClass('ng-invalid') ) {
                    $(this).parent().addClass('md-input-invalid');
                    $(this).parent().find('.md-input-message-animation').attr('style','opacity: 1; margin-top: 0px;');
                    retorno = false;
                } 
            });

            if (!retorno) { $('.fit-screen').scrollTop(0); }
            
            return retorno;
        };
        
        //RETORNA QTDE DE ERROS OBRIGATORIOS
        this.getErrosInputObrigatorios = function (formId){
            var inputs = $('#'+formId).find('input[required]'); var contador = 0;
            for (var i=0; i<inputs.length; i++) { if (this.isVazio($(inputs[i]).val())) { contador++; } }
        };
        
        //VALIDA CNPJ
        this.validarCNPJ = function (cnpj) { 
            var result = Validator.cnpj(cnpj);
            if (!result) { $('.form-errors').show(); $('.form-errors').append('<br />CNPJ Inválido.'); }
            return result;
        };
        
        //CUSTOM TOAST
        this.toast = function (msg) { $mdToast.show($mdToast.simple().textContent(msg)); };
        
        //REDIRECIONAR
        this.redirect = function (link) { window.location = link; };
        
        //INICIALIZAR PÀGINA
        this.inicializar = function () {
            $('md-content').scroll(function () {
                var top = $(this).scrollTop();
                if (top > 128) { 
                    $('.scroll-toolbar').css('top',0);  $('.back-btn').css('position','fixed');
                } else { 
                    $('.scroll-toolbar').css('top','-64px'); $('.back-btn').css('position','');
                }
            });
        };
        
        //MUDAR IMAGEM TOOLBAR
        this.mudarImagemToolbar = function (url) {
            var link = ErudioConfig.dominio + '/apps/'+url;
            $('.content-bar').attr('style',"background: linear-gradient(to bottom,rgba(0, 0, 0, 1), rgba(123, 121, 121, 0.3),rgba(1,0,0,0)), url('"+link+"') center center no-repeat !important;");
        };
        
        //FILTRAR AUTOCOMPLETE
        this.filtrar = function (query, items){
            var results = [];
            if (query) { results = $filter('filter')(items,{nome: query}); } else { results = items; }
            return results;
        };
        
        //VERIFICAR PERMISSAO
        this.verificaPermissao = function (role) {
            role = 'ROLE_'+role; var attr = JSON.parse(sessionStorage.getItem('atribuicoes')); var roles = attr.roles;
            var retorno = roles.includes(role);
            if (!retorno) { retorno = roles.includes("ROLE_SUPER_ADMIN"); }
            return retorno;
        };
        
        //VERIFICA ESCRITA
        this.verificaEscrita = function (role) {
            role = 'ROLE_'+role; var attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
            this.retornoEscrita = false;
            for (var i=0; i<attr.atribuicoes.length; i++) {
                for (var j=0; j<attr.atribuicoes[i].grupo.permissoes.length; j++) {
                    if (attr.atribuicoes[i].grupo.permissoes[j].permissao.nomeIdentificacao === role || attr.atribuicoes[i].grupo.permissoes[j].permissao.nomeIdentificacao === "ROLE_SUPER_ADMIN") {
                        if (attr.atribuicoes[i].grupo.permissoes[j].tipoAcesso === "E") { this.retornoEscrita = true; }
                    }
                }
            }
            return this.retornoEscrita;
        };
        
        //VERIFICA ADMIN
        this.isAdmin = function () { return this.verificaPermissao("SUPER_ADMIN"); };
        
        //VALIDA ESCRITA
        this.validarEscrita = function (opcao, opcoes, escrita) {
            this.validacaoEscrita = false;
            for (var i=0; i<opcoes.length; i++) { if (opcoes[i].opcao === opcao) { this.validacaoEscrita = escrita; } else { this.validacaoEscrita === true; } }
            return this.validacaoEscrita;
        };
        
        //ELIMINA MENUS
        this.ajustaMenus = function () {
            var size = 0; var sizeHidden = 0;
            $(".submenu").each(function(j){
                size = $(this).find('li').length; sizeHidden = 0;
                $(this).find('li').each(function(i){
                    if ($(this).hasClass('ng-hide')) { sizeHidden++; }
                    if (i+1 === size) { if (size === sizeHidden) { $(this).parents().eq(1).find('.menu-titulo').hide(); } else { $(this).parents().eq(1).find('.menu-titulo').css('opacity', 1); } }
                });
            }).promise().done(function(){ $('#menu-lateral').fadeIn(150); });
        };
        
        //HABILITA CORTINA SEM PERMISSAO
        this.semPermissao = function () { $('.sem-permissao-cortina, .sem-permissao-texto').show(); };
        
        //REMOVE CORTINA SEM PERMISSAO
        this.comPermissao = function () { $('.sem-permissao-cortina, .sem-permissao-texto').hide(); };
        
        //CONVERTE DATA PT-BR PARA EN
        this.converteData = function (strData) { var arrData = strData.split('/'); return arrData[2] + '-' + arrData[1] + '-' + arrData[0]; };
        
        //CONVERTE DATA EN PARA PT-BR
        this.formataData = function (strData) { var arrData = strData.split('-'); return arrData[2] + '/' + arrData[1] + '/' + arrData[0]; };
        
        //DESABILITA TODO O FORM
        this.desabilitaForm = function (formId) { $(formId+" input").prop("disabled",true); };
        
        //BUSCA DIAS NO MES
        this.diasNoMes = function (mes,ano) { return new Date(ano, mes+1, 0).getDate(); };
        
        //RETORNA NOME DO MES
        this.nomeMeses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
        this.nomeMes = function (mes){ return this.nomeMeses[mes]; };
        this.nomeMesAbreviado = function (mes){ var nome = this.nomeMeses[mes]; return nome.substring(0,3); };
        
        //RETORNA NOME DO DIA
        this.nomeDias = ["Segunda-Feira","Terça-Feira","Quarta-Feira","Quinta-Feira","Sexta-Feira","Sábado","Domingo"];
        this.nomeDia = function (dia) { return this.nomeDias[dia]; };
        this.nomeDiaReduzido = function (dia) { var nome = this.nomeDias[dia]; return nome.replace('-feira',''); };
        this.nomeDiaAbreviado = function (dia) { var nome = this.nomeDias[dia]; return nome.substring(0,3); };
        
        //MOSTRAR LISTA
        //this.enterList = function (elem) { $(elem).each(function(i){ setTimeout(function(){ $(elem).eq(i).addClass('mostrar'); },175); }); };

        //BUSCA CEP
        this.buscaCEP = function (cep) {
            return $.getJSON("https://viacep.com.br/ws/"+cep+"/json/?callback=?", (dados) => { return dados; });
        };
    }]);
})();