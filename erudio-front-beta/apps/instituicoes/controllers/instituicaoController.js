(function (){
    var instituicoes = angular.module('instituicoes',['ngMaterial', 'util', 'erudioConfig']);
    instituicoes.controller('InstituicaoController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Instituições');
        
        //INSTITUICAO EM USO
        $scope.instituicao = null;
        
        //SETA COLUNAS DA LISTA
        $scope.subheaders =[{label: 'Nome da Instituição'}];
        
        //OPCOES
        $scope.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover'}];
        
        //OPCOES DO BOTAO FAB
        $scope.link = ErudioConfig.dominio + '/#!/instituicoes/';
        $scope.fab = {tooltip: 'Adicionar Instituição', icone: 'add', href: $scope.link+'novo'};
        
        //PAGINA DA LISTA
        $scope.pagina = 0;
        
        //BUSCANDO INSTITUICOES
        $scope.buscarInstituicoes = function () {
            var promise = Util.buscar('instituicoes',{page: $scope.pagina});
            promise.then(function(response){ $scope.objetos = response.data; });
        };
        $scope.buscarInstituicoes();
        
        //BUSCANDO TEMPLATE DA LISTA GERAL E ESPECIFICA
        $scope.lista = Util.getTemplateLista();
        $scope.listaEspecifica = Util.getTemplateListaEspecifica('instituicoes');
        
        //BUSCANDO TEMPLATE DA BUSCA SIMPLES
        $scope.buscaSimples = Util.getTemplateBuscaSimples();
        
        //EXECUTANDO OPCOES
        $scope.executarOpcao = function (event, opcao, objeto) { $scope.instituicao = objeto; $scope.modalExclusão(event); };
        
        //ABRINDO MODAL DE EXCLUSAO
        $scope.modalExclusão = function (event) {
            var confirm = Util.modalExclusao(event, 'Remover Instituição', 'Deseja remover esta instituição?', 'remover', $mdDialog);
            $mdDialog.show(confirm).then(function() { 
                var remocao = Util.remover($scope.instituicao, 'Instituicão', 'f'); remocao.then(function(){ $scope.buscarInstituicoes(); });
            }, function() {});
        };
        
        //ESCUTANDO VARIAVEL DE BUSCA
        $scope.busca = '';
        $scope.verificaBusca = function (query) { if(Util.isVazio(query)){ $scope.buscarInstituicoes(); } else { $scope.executarBusca(query); } };
        
        //BUSCA COM PARAMETRO
        $scope.executarBusca = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (Util.isVazio(query)) { query = ''; } var tamanho = query.length;
                if (tamanho > 3) {
                    var res = Util.buscar('instituicoes',{'nome':query});
                    res.then(function(response){ $scope.objetos = response.data; });
                }
            }, 800);
        };
        
        //PAGINANDO
        $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarInstituicoes(); };
        $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarInstituicoes(); } };
        
        //INICIAR
        Util.inicializar();
        Util.mudarImagemToolbar('instituicoes/assets/images/instituicoes.jpg');
    }]);
})();