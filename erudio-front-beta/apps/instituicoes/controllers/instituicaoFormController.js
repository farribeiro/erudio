(function (){
    var instituicoesForm = angular.module('instituicoesForm',['ngMaterial', 'util', 'erudioConfig']);
    instituicoesForm.controller('InstituicaoFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', function($scope, Util, ErudioConfig, $routeParams, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Instituições');
        
        //INSTITUICAO EM USO
        $scope.instituicao = Util.getEstrutura('instituicao');
        $scope.instituicao.endereco = Util.getEstrutura('endereco');
        $scope.telefone = Util.getEstrutura('telefone');
        
        //ATRIBUTOS EXTRAS
        $scope.telefones = [];
        
        //SETA SUBHEADER DO FORM
        $scope.subheaders =[{label: 'Informações Instituicionais'}, {label: 'Contatos'}, {label: 'Endereço'}];
        
        //TEMPLATE DOS BLOCOS DE INPUTS
        $scope.inputs = [{ href: Util.getInputBlockCustom('instituicoes','informacoesPessoais') }, { href: Util.getInputBlockCustom('instituicoes','contatos') }, { href: Util.getInputBlockCustom('instituicoes','endereco') }];
        
        //CRIAR FORMS
        $scope.forms = [{ nome: 'instituicoesForm', subheaders: $scope.subheaders }];
        
        //OPCOES DO BOTAO VOLTAR
        $scope.link = '/#!/instituicoes/';
        $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
        
        //BUSCANDO INSTITUICOES
        $scope.buscarInstituicao = function () {
            $scope.telefones = []; $scope.telefone = Util.getEstrutura('telefone');
            if (!Util.isNovo($routeParams.id)) {
                var promise = Util.um('instituicoes',$routeParams.id);
                promise.then(function(response){
                    $scope.instituicao = response.data; $scope.buscarEstados();
                    if ($scope.instituicao.telefones !== undefined) { $scope.getTelefones($scope.instituicao.telefones); }
                    if (!Util.isVazio($scope.instituicao.endereco)) { $scope.getEndereco($scope.instituicao.endereco.id); }
                    Util.aplicarMascaras(); $timeout(function(){ $('#instituicaoContatoNumero').find('input').change(function(){ $scope.adicionarTelefone(); }); },300);
                });
            } else { $timeout(function(){ Util.aplicarMascaras(); $('#instituicaoContatoNumero').find('input').change(function(){ $scope.adicionarTelefone(); }); },300); $scope.buscarEstados(); }
        };
        
        //RECUPERANDO ENDERECO
        $scope.getEndereco = function (id) {
            var promise = Util.um('enderecos',id);
            promise.then(function (response) { $scope.instituicao.endereco = response.data; $scope.buscarCidades($scope.instituicao.endereco.cidade.estado.id); });
        };
        
        //RECUPERANDO TELEFONES
        $scope.getTelefones = function (telefones) {
            if (telefones.length > 0) {
                $('md-divider.hide').show(); $('.md-subheader.hide').show();
                for (var i=0; i<telefones.length; i++) { var promise = Util.um('telefones',telefones[i].id); promise.then(function (response) { $scope.telefones.push(response.data); }); }
            } else { $('md-divider.hide').hide(); $('.md-subheader.hide').hide(); }
        };
        
        //VALIDA CAMPO
        $scope.validaCampo = function () { Util.validaCampo(); };
        
        //BUSCANDO ESTADOS
        $scope.buscarEstados = function () { var promiseEstados = Util.getEstados(); promiseEstados.then(function (response){ $scope.estados = response.data; }); };
        
        //BUSCANDO CIDADES
        $scope.buscarCidades = function (estado) { var promiseCidade = Util.getCidades(estado); promiseCidade.then(function(response){ $scope.cidades = response.data; }); };
        
        //OPÇÕES DE TELEFONE
        $scope.tiposTelefone = ['CELULAR','COMERCIAL','RESIDENCIAL'];
        
        //SALVAR INSTITUICAO
        $scope.salvar = function () {
            if ($scope.validar('instituicoesForm')) {
                var endereco = $scope.instituicao.endereco;
                delete $scope.instituicao.endereco; delete $scope.instituicao.telefones;
                var resultado = Util.salvar(endereco,'enderecos');
                resultado.then(function (response){
                    $scope.instituicao.endereco = { id: response.data.id }; 
                    var resultadoPessoa = Util.salvar($scope.instituicao,'instituicoes','Instituição','F');
                    resultadoPessoa.then(function(response){
                        var telefones = $scope.telefones; var tipo_pessoa = response.data.tipo_pessoa;
                        if (telefones.length > 0) {
                            for (var i=0; i<telefones.length; i++) { 
                                telefones[i].pessoa.id = response.data.id; telefones[i].pessoa.tipo_pessoa = tipo_pessoa; var resultadoTelefone = Util.salvar(telefones[i],'telefones');
                                if (i === telefones.length-1) { resultadoTelefone.then(function(){ $scope.buscarInstituicao(); Util.redirect($scope.fab.href);; }); }
                            }
                        } else { $scope.buscarInstituicao(); Util.redirect($scope.fab.href); }
                    });
                });
            }
        };
        
        //VALIDAR FORM
        $scope.validar = function (formId) { 
            var obrigatorios = Util.validar(formId); var cnpj = null;
            if (!Util.isVazio($scope.instituicao.cpfCnpj)) { 
                cnpj = Util.validarCNPJ($scope.instituicao.cpfCnpj);
                if (obrigatorios && cnpj) { return true; } else { return false; }
            } else { if (obrigatorios) { return true; } else { return false; } }
        };
        
        //ADICIONAR TELEFONE
        $scope.adicionarTelefone = function () { 
            if (!Util.isVazio($scope.telefone.numero) && !Util.isVazio($scope.telefone.descricao)) {
                $scope.telefones.push($scope.telefone); $('md-divider.hide').show(); $('.md-subheader.hide').show();
                $scope.instituicao.telefones = $scope.telefones; Util.toast('Telefone adicionado, salve para garantir as modificações.');
                $scope.telefone = Util.getEstrutura('telefone');
            } else {
                Util.toast('Ambos os campos devem ser preenchidos para adicionar um telefone.');
            }
        };
        
        //REMOVER TELEFONE
        $scope.removerTelefone = function (telefone, index) {
            var promise = Util.remover(telefone, 'Telefone', 'm');
            promise.then(function(){ 
                $scope.telefones.splice(index,1);
                if ($scope.telefones.length > 0) { $('md-divider.hide').show(); $('.md-subheader.hide').show(); } else { $('md-divider.hide').hide(); $('.md-subheader.hide').hide(); }
            });
        };
        
        //INICIANDO
        $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarInstituicao();
        Util.mudarImagemToolbar('instituicoes/assets/images/instituicoes.jpg');
    }]);
})();