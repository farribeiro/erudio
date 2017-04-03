/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

(function (){
    var etapaModule = angular.module('etapaModule', ['servidorModule','etapaDirectives','disciplinaModule','erudioConfig']);
    etapaModule.controller('EtapaController', ['$scope','$rootScope','$timeout','Servidor','DisciplinaService','$templateCache','ErudioConfig', function($scope,$rootScope,$timeout,Servidor,DisciplinaService, $templateCache, ErudioConfig){
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll();
        $scope.escrita = Servidor.verificaEscrita('ETAPA');
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('etapas'); $scope.lista = true;
        //ATRIBUTOS
        $scope.etapas = []; $scope.curso = {'id': null}; $scope.titulo = "Etapas"; $scope.nomeCurso = '';
        $scope.acao = 'Adicionar'; $scope.progresso = false; $scope.nomeUnidade = '';
        $scope.cortina = false; $scope.pagina = 0; $scope.DisciplinaService = DisciplinaService;
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //CHAMANDO BUSCA DE ETAPA
        $scope.selecionaCurso = function(){ $scope.etapas = []; $scope.buscarEtapas(); $rootScope.etapaCurso = angular.copy($scope.curso); };
        //INICIALIZAR
        $scope.inicializar = function () { $('.tooltipped').tooltip('remove'); Servidor.entradaPagina(); $('.title-module').html($scope.titulo); $('#modal-ajuda-curso').modal(); $('.material-tooltip').remove(); };
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-etapa').modal(); };
        //PASSA ARGUMENTO PARA O MÓDULO DE DISCIPLINAS
        $scope.intraForms = function (etapa){ $rootScope.etapaDisciplina = etapa; };
        //REMOVER ETAPA
        $scope.remover = function(){ $scope.mostraProgresso(); Servidor.remover($scope.etapaRemover, 'Etapa'); $timeout(function (){ $scope.fechaProgresso(); $scope.buscarEtapas(); }, 150); };
        
        //PREPARA REMOCAO DA ETAPA
        $scope.prepararRemover = function (etapa){
            var promise = Servidor.buscar('turmas', {'etapa': etapa.id, 'encerrado': false});
            promise.then(function(response) {
                if (response.data) { $('.remove-content').html('Há turmas ativas em <strong>' + etapa.nomeExibicao + '</strong>, você realmente deseja remover esta etapa?' ); } else { $('.remove-content').text('Você realmente deseja remover esta etapa?'); }
                $scope.etapaRemover = etapa; $('#remove-modal-etapa').modal();
            });
        };

        //BUSCAR ETAPAS
        $scope.buscarEtapas = function(){
            $scope.mostraProgresso(); var promise = null;
            if ($scope.curso.id !== null){ promise = Servidor.buscar('etapas',{'curso':$scope.curso.id, 'page': $scope.pagina}); } else { promise = Servidor.buscar('etapas',{'page': $scope.pagina}); }
            promise.then(function (response){
                if (response.data.length > 0) {
                    $scope.etapas = response.data; $('.tooltipped').tooltip('remove'); $timeout(function(){ $('.tooltipped').tooltip({delay: 50}); $scope.fechaProgresso(); });
                } else { $scope.pagina--; $scope.fechaProgreso(); }
            });
        };
        
        //INFORMAÇÃO DA ETAPA
        $scope.carregarInfo = function (etapa) {
            $scope.mostraProgresso(); var promise = Servidor.buscarUm('etapas',etapa.id);
            promise.then(function (response) { $scope.etapa = response.data; $('#info-modal-etapa').modal(); });
            $timeout(function(){ $('.opcoesEtapa' + etapa.id).hide(); $scope.fechaProgresso(); }, 300);
        };

        //BUSCANDO CURSOS
        $scope.buscarCursos = function() {
            var promise = Servidor.buscar('cursos', null);
            promise.then(function (response){
                if (response.data.length > 0) {
                    $scope.cursos = response.data; $timeout(function (){ $('#curso').material_select('destroy'); $('#curso').material_select(); },250);
                } else { Materialize.toast('Nenhum curso encontrado.', 1000); }
            });
        };
        
        //INICIALIZANDO
        $scope.inicializar(); if ($rootScope.etapaCurso !== undefined) { $scope.curso = angular.copy($rootScope.etapaCurso); $scope.buscarEtapas(); } else { $scope.buscarEtapas(); }
        $timeout(function(){ $scope.buscarCursos(); },250);
    }]);
})();