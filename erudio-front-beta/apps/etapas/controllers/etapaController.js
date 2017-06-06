(function (){
    var etapas = angular.module('etapas',['ngMaterial', 'util', 'erudioConfig', 'shared']);
    etapas.controller('EtapaController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', 'Shared', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, Shared, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Etapas');
        
        //ETAPA EM USO
        $scope.etapa = null;
        
        //ATRIBUTOS EXTRAS
        $scope.curso = {id: null};
        
        //SETA BUSCA CUSTOM
        $scope.buscaCustom = Util.setBuscaCustom('/apps/etapas/partials');
        
        //SETA COLUNAS DA LISTA
        $scope.subheaders =[{label: 'Nome da Etapa'}];
        
        //OPCOES
        $scope.opcoes = [{tooltip: 'Disciplinas', icone: 'import_contacts', opcao: 'disciplinas'},{tooltip: 'Remover', icone: 'delete', opcao: 'remover'}];
        
        //OPCOES DO BOTAO FAB
        $scope.link = ErudioConfig.dominio + '/#!/etapas/';
        $scope.fab = {tooltip: 'Adicionar Etapa', icone: 'add', href: $scope.link+'novo'};
        
        //PAGINA DA LISTA
        $scope.pagina = 0;
        
        //BUSCANDO CURSOS
        $scope.buscarCursos = function () {
            var cursoEtapa = Shared.getCursoEtapa();
            if (Util.isVazio(cursoEtapa)) { var promise = Util.buscar('cursos',null); promise.then(function(response){ $scope.cursos = response.data; });
            } else { $scope.curso.id = cursoEtapa; $scope.buscarEtapas(); }
        };
        
        //BUSCANDO ETAPAS
        $scope.buscarEtapas = function () {
            Shared.setCursoEtapa($scope.curso.id);
            var promise = Util.buscar('etapas',{page: $scope.pagina, curso: $scope.curso.id});
            promise.then(function(response){ $scope.objetos = response.data; });
        };
        
        //BUSCANDO TEMPLATE DA LISTA GERAL E ESPECIFICA
        $scope.lista = Util.getTemplateLista();
        $scope.listaEspecifica = Util.getTemplateListaEspecifica('etapas');
        
        //BUSCANDO TEMPLATE DA BUSCA 
        $scope.buscaCustomTemplate = Util.getTemplateBuscaCustom();
        
        //EXECUTANDO OPCOES
        $scope.executarOpcao = function (event, opcao, objeto) {
            $scope.etapa = objeto; 
            switch (opcao.opcao) {
                case 'remover': $scope.modalExclusão(event); break;
                case 'disciplinas': $scope.verDisciplinas(); break;
                default: return false; break;
            }
        };
        
        //ABRINDO MODAL DE EXCLUSAO
        $scope.modalExclusão = function (event) {
            var confirm = Util.modalExclusao(event, 'Remover Etapa', 'Deseja remover esta Etapa?', 'remover', $mdDialog);
            $mdDialog.show(confirm).then(function() { 
                var remocao = Util.remover($scope.etapa, 'Etapa', 'f'); remocao.then(function(){ $scope.buscarEtapas(); });
            }, function() {});
        };
        
        //ABRIR INFORMACOES
        $scope.verDisciplinas = function () {
            Shared.setEtapaDisciplina($scope.etapa.id);
            Util.redirect(ErudioConfig.dominio + '/#!/disciplinas');
        };
        
        //PAGINANDO
        $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarEtapas(); };
        $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarEtapas(); } };
        
        //INICIAR
        Util.inicializar(); $scope.buscarCursos();
        Util.mudarImagemToolbar('etapas/assets/images/etapas.jpg');
    }]);
})();