(function (){
    var disciplina = angular.module('disciplinas',['ngMaterial', 'util', 'erudioConfig', 'shared']);
    disciplina.controller('DisciplinaController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', 'Shared', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, Shared, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Disciplina');
        
        //DISCIPLINA EM USO
        $scope.disciplina = null;
        
        //ATRIBUTOS EXTRAS
        $scope.curso = {id: null}; $scope.etapa = {id: null};
        $scope.cursos = []; $scope.etapas = [];
        
        //SETA BUSCA CUSTOM
        $scope.buscaCustom = Util.setBuscaCustom('/apps/disciplinas/partials');
        
        //SETA COLUNAS DA LISTA
        $scope.subheaders =[{label: 'Nome da Disciplina'}];
        
        //OPCOES
        $scope.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover'}];
        
        //OPCOES DO BOTAO FAB
        $scope.link = ErudioConfig.dominio + '/#!/disciplinas/';
        $scope.fab = {tooltip: 'Adicionar Disciplina', icone: 'add', href: $scope.link+'novo'};
        
        //PAGINA DA LISTA
        $scope.pagina = 0;
        
        //BUSCANDO CURSOS
        $scope.buscarCursos = function () {
            var etapaDisciplina = Shared.getEtapaDisciplina();
            if (!Util.isVazio(etapaDisciplina)) { var promise = Util.um('etapas',etapaDisciplina); promise.then(function(response){ $scope.etapa.id = response.data.id; $scope.curso.id = response.data.curso.id; }); }
            var promise = Util.buscar('cursos',null); promise.then(function(response){ $scope.cursos = response.data; $scope.buscarEtapas(); });
        };
        
        //BUSCANDO ETAPAS
        $scope.buscarEtapas = function () {
            var promise = Util.buscar('etapas',{curso: $scope.curso.id});
            promise.then(function(response){ $scope.etapas = response.data; if (!Util.isVazio($scope.etapa.id)) { $scope.buscarDisciplinas(); } });
        };
        
        //BUSCANDO DISCIPLINAS
        $scope.buscarDisciplinas = function () {
            Shared.setEtapaDisciplina($scope.etapa.id); console.log($scope.etapa.id);
            var promise = Util.buscar('disciplinas',{page: $scope.pagina, etapa: $scope.etapa.id});
            promise.then(function(response){ $scope.objetos = response.data; });
        };
        
        //BUSCANDO TEMPLATE DA LISTA GERAL E ESPECIFICA
        $scope.lista = Util.getTemplateLista();
        $scope.listaEspecifica = Util.getTemplateListaEspecifica('disciplinas');
        
        //BUSCANDO TEMPLATE DA BUSCA 
        $scope.buscaCustomTemplate = Util.getTemplateBuscaCustom();
        
        //EXECUTANDO OPCOES
        $scope.executarOpcao = function (event, opcao, objeto) {
            $scope.disciplina = objeto; 
            switch (opcao.opcao) { case 'remover': $scope.modalExclusão(event); break; default: return false; break; }
        };
        
        //ABRINDO MODAL DE EXCLUSAO
        $scope.modalExclusão = function (event) {
            var confirm = Util.modalExclusao(event, 'Remover Disciplina', 'Deseja remover esta Disciplina?', 'remover', $mdDialog);
            $mdDialog.show(confirm).then(function() { 
                var remocao = Util.remover($scope.disciplina, 'Disciplina', 'f'); remocao.then(function(){ $scope.buscarDisciplinas(); });
            }, function() {});
        };
        
        //ABRIR INFORMACOES
        $scope.verDisciplinas = function () { Shared.setEtapaDisciplina($scope.etapa.id); Util.redirect(ErudioConfig.dominio + '/#!/disciplinas'); };
        
        //PAGINANDO
        $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarDisciplinas(); };
        $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarDisciplinas(); } };
        
        //INICIAR
        Util.inicializar(); $scope.buscarCursos();
        Util.mudarImagemToolbar('disciplinas/assets/images/disciplinas.jpg');
    }]);
})();