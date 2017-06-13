(function (){
    var quadroHorarios = angular.module('quadroHorarios',['ngMaterial', 'util', 'erudioConfig', 'shared']);
    quadroHorarios.controller('QuadroHorarioController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', 'Shared', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, Shared, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Quadros de Horário');
        
        //QUADROS EM USO
        $scope.quadro = null;
        
        //ATRIBUTOS EXTRAS
        $scope.items = []; $scope.item = {id: null}; $scope.itemBusca = ''; $scope.autoPlaceholder = 'Selecione uma Unidade de Ensino';
        
        //SETA BUSCA CUSTOM
        $scope.buscaCustom = Util.setBuscaCustom('/apps/quadroHorarios/partials');
        
        //SETA COLUNAS DA LISTA
        $scope.subheaders =[{label: 'Nome do Quadro'}];
        
        //OPCOES
        $scope.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover'}];
        
        //OPCOES DO BOTAO FAB
        $scope.link = ErudioConfig.dominio + '/#!/quadros-horario/';
        $scope.fab = {tooltip: 'Adicionar Quadro de Horário', icone: 'add', href: $scope.link+'novo'};
        
        //PAGINA DA LISTA
        $scope.pagina = 0;
        
        //BUSCAR UNIDADES
        $scope.buscarUnidades = function (){
            var promise = Util.buscar('unidades-ensino',null);
            promise.then(function(response){ $scope.items = response.data; });
        };
        
        //FILTRANDO AUTOCOMPLETE
        $scope.filtrar = function (query){ return Util.filtrar(query, $scope.items); };
        
        //BUSCANDO QUADROS
        $scope.buscarQuadros = function () {
            var promise = Util.buscar('quadro-horarios',{page: $scope.pagina, unidadeEnsino: $scope.unidade.id});
            promise.then(function(response){ $scope.objetos = response.data; });
        };
        
        //BUSCANDO TEMPLATE DA LISTA GERAL E ESPECIFICA
        $scope.lista = Util.getTemplateLista();
        $scope.listaEspecifica = Util.getTemplateListaEspecifica('quadroHorarios');
        
        //BUSCANDO TEMPLATE DA BUSCA 
        $scope.buscaCustomTemplate = Util.getTemplateBuscaCustom();
        
        //EXECUTANDO OPCOES
        $scope.executarOpcao = function (event, opcao, objeto) { $scope.quadro = objeto; $scope.modalExclusão(event); };
        
        //ABRINDO MODAL DE EXCLUSAO
        $scope.modalExclusão = function (event) {
            var confirm = Util.modalExclusao(event, 'Remover Quadro de Horário', 'Deseja remover este Quadro de Horário?', 'remover', $mdDialog);
            $mdDialog.show(confirm).then(function() { 
                var remocao = Util.remover($scope.quadro, 'Quadro de Horário', 'f'); remocao.then(function(){ $scope.buscarQuadros(); });
            }, function() {});
        };
        
        //PAGINANDO
        $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarQuadros(); };
        $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarQuadros(); } };
        
        //INICIAR
        Util.inicializar(); $scope.buscarUnidades();
        Util.mudarImagemToolbar('quadroHorarios/assets/images/quadroHorarios.jpg');
    }]);
})();