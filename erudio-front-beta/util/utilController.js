(function (){
    var util = angular.module('util',['structure', 'validator', 'angular-md5', 'angular-sha1', 'erudioConfig', 'ngMaterial']);
    
    util.service('Util', ['$timeout', 'Structure', 'Validator', 'Restangular', 'md5', 'sha1', 'ErudioConfig', '$mdToast', '$filter', function($timeout, Structure, Validator, Restangular, md5, sha1, ErudioConfig, $mdToast, $filter) {
        
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
        };
        
        //VALIDA CAMPO
        this.validaCampo = function() {
            $('input').change(function(){ if (!$(this).hasClass('ng-invalid')) { $(this).parent().parent().find('.message-list').removeAttr('style'); } });
            $('md-select').blur(function(){ if (!$(this).hasClass('ng-invalid')) { $(this).parent().parent().find('.message-list').removeAttr('style'); } });
        };
        
        // BUSCAR PROMISE
        this.buscarPromise = function(endereco) {
            var rest = this.preparaRestangular();
            rest.setFullResponse(true);
            return rest.all(endereco);
        };
        
        //PREPARA HEADER X-WSSE
        this.criarHeader = function () {
            var username = sessionStorage.getItem('username'); var key = sessionStorage.getItem('key');
            var created = moment().format(); var nonce = this.guid(); var nonceSend = btoa(nonce);
            var digest = btoa(sha1.hash(nonce + created + key));
            var header = 'UsernameToken Username="' + username + '", PasswordDigest="' + digest + '", Nonce="' + nonceSend + '", Created="' + created + '"';
            return header;
        };
        
        //GERADOR DE NONCE
        this.guid = function () {
            function s4() { return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1); }
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
        };

        //INSERE O HEADER NA CHAMADA REST
        this.preparaRestangular = function () {
            var header = this.criarHeader();
            var rest = Restangular.withConfig(function(conf){ conf.setDefaultHeaders({ "X-WSSE": header }); });
            return rest;
        };
        
        //CONTROLE DA TELA DE PROGRESSO
        this.abreProgresso = function () { $('.progresso').show(); $('.cortina').show(); };
        this.fechaProgresso = function () { $('.progresso').hide(); $('.cortina').hide(); };
        
        //MOSTRA MODAL DE EXCLUSAO
        this.modalExclusao = function(event, title, content, label, $mdDialog) {
            var confirm = $mdDialog.confirm().title(title).textContent(content).ariaLabel(label).targetEvent(event).ok('Remover').cancel('Cancelar');
            return confirm;
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
        
        //BUSCA POR LISTA
        this.buscar = function(endereco, opcoes) {
            this.abreProgresso(); var rest = this.preparaRestangular(); rest.setFullResponse(true);
            var retorno = rest.all(endereco).getList(opcoes);
            retorno.then(function(){ $('.progresso').hide(); $('.cortina').hide(); }); return retorno;
        };
        
        //BUSCAR POR ID
        this.um = function(endereco, id) {
            this.abreProgresso(); var rest = this.preparaRestangular(); rest.setFullResponse(true);
            var retorno = rest.one(endereco, id).get();
            retorno.then(function(){ $('.progresso').hide(); $('.cortina').hide(); }); return retorno;
        };
        
        //HELPER PARA ORIENTACAO DAS PALAVRAS
        this.orientarLabel = function (label, orientacao){ var retorno = ''; if (orientacao === "m") { retorno = label+"o"; } else if (orientacao === "f") { retorno = label+"a"; } else { retorno = label+"e"; } return retorno; };
        
        //REMOVER OBJETO
        this.remover = function (objeto, label, orientacao) {
            var result = objeto.remove();
            var sufix = this.orientarLabel('removid', orientacao);
            result.then(function (data){ if (label !== null && label !== '') { $mdToast.show($mdToast.simple().textContent(label+' '+sufix)); } });
            return result;
        };
        
        //VERIFICA VARIAVEL VAZIA
        this.isVazio = function (value) { if (value === null || value === undefined || value === "") { return true; } else { return false; } };
        
        //VERIFICA SE É CADASTRO OU EDICAO
        this.isNovoLabel = function (value) { if (value === 'novo') { return 'Cadastrar'; } else { return 'Editar'; } };
        
        //VERIFICA SE É CADASTRO OU EDICAO
        this.isNovo = function (value) { if (value === 'novo') { return true; } else { return false; } };
        
        //BUSCA ESTRUTURA DE OBJETO 
        this.getEstrutura = function (tipo) { return Structure.getObjeto(tipo); };
        
        //VALIDA FORM
        this.validar = function (formId) {
            var errors = ''; var contador = 0; $('.form-errors').html('').append('<strong>Por favor, verifique os seguintes itens:</strong><br />');
            var inputs = $('#'+formId).find('input[required]'); var retorno = true;
            for (var i=0; i<inputs.length; i++) {
                if (this.isVazio($(inputs[i]).val())) { contador++; var parent = $(inputs[i]).parent().parent(); var field = parent.find('label').html(); errors += field+', '; }
                if (i === inputs.length-1 && errors.length > 0) { 
                    var inicioToast = "<br />Os campos "; var finalToast = " são obrigatórios."; if (contador === 1) { inicioToast = "O campo "; finalToast = " é obrigatório."; } 
                    errors = errors.slice(0,-2); $('.form-errors').append(inicioToast+errors+finalToast); $('.form-errors').show(); retorno = false;
                }
            }
            
            var invalidInputs = $('#'+formId).find('input.ng-invalid');
            if (invalidInputs.length > 0) {
                for (var i=0; i<invalidInputs.length; i++) {
                    if ($(invalidInputs[i]).hasClass('ng-untouched')) {
                        $(invalidInputs[i]).parent().parent().addClass('md-input-invalid');
                        $(invalidInputs[i]).removeClass('ng-untouched').addClass('ng-touched');
                    }
                }
                $('.form-errors').append('<br />Os campos em vermelho contém dados inválidos.'); $('.form-errors').show(); retorno = false;
            }
            
            var invalidSelect = $('#'+formId).find('md-select.ng-invalid');
            if (invalidSelect.length > 0) { 
                for (var i=0; i<invalidSelect.length; i++) {
                    if ($(invalidSelect[i]).hasClass('ng-untouched')) {
                        $(invalidSelect[i]).parent().parent().addClass('md-input-invalid');
                        $(invalidSelect[i]).removeClass('ng-untouched').addClass('ng-touched');
                    }
                }
                $('.form-errors').append('<br />Os campos de seleção em vermelho contém dados inválidos.'); $('.form-errors').show(); retorno = false;
            }
            
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
        
        //SALVAR
        this.salvar = function(objeto,endereco,label,gen) {
            var result = []; var artigo = 'e';
            if (gen === 'M') { artigo = 'o'; } else if (gen === 'F') { artigo = 'a'; }
            if (objeto !== null && objeto.id) {
                if (objeto.route === undefined || objeto.route === null) { objeto.route = endereco; } result = objeto.put();
                result.then(function (data){
                    if (data.status >= 200 || data.status <= 204) {
                        if (label !== null && label !== '' && label !== undefined) { $mdToast.show($mdToast.simple().textContent(label+' modificad'+artigo+' com sucesso.')); }
                    } else { result = false; }
                }, function(error) {});
            } else {
                var promise = this.buscarPromise(endereco);
                result = promise.post(objeto);
                result.then(function(data) {
                    if (data.status >= 200 || data.status <= 204) {
                        if (label !== null && label !== '' && label !== undefined) { $mdToast.show($mdToast.simple().textContent(label+' salv'+artigo+' com sucesso.')); }
                    } else { result = false; }
                }, function(error) {});
            }
            return result;
        };
        
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
    }]);
})();