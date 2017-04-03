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
    var etapaFormModule = angular.module('etapaFormModule', ['servidorModule','etapaDirectives','disciplinaModule','erudioConfig']);
    etapaFormModule.controller('EtapaFormController', ['$scope', '$timeout', 'Servidor', 'DisciplinaService','$templateCache','ErudioConfig','$routeParams','$rootScope', function($scope, $timeout, Servidor, DisciplinaService, $templateCache, ErudioConfig,$routeParams,$rootScope){
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll();
        $scope.escrita = Servidor.verificaEscrita('ETAPA');
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateForm('etapas');
        //ATRIBUTOS
        $scope.cursos = []; $scope.curso = { 'id':null }; $scope.sistemaAvaliacoes = [];
        $scope.modulos = []; $scope.modeloQuadroHorarios = []; $scope.acao = 'Adicionar'; $scope.progresso = false;
        $scope.cortina = false; $scope.pagina = 0; $scope.DisciplinaService = DisciplinaService;
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //ESTRUTURA
        $scope.etapa = { nome: null, nomeExibicao: null, ordem: null, modulo:{ id:null }, modeloQuadroHorario:{ id:null }, sistemaAvaliacao:{ id:null }, limiteAlunos: null, integral: true, curso: { id:null } };
        //MODAL DE CERTEZA PARA VOLTAR
        $scope.prepararVoltar = function(objeto) {  if (objeto.nome && !objeto.id) { $('#modal-certeza').modal(); } else { window.location = "/#/etapas"; } };
        //BUSCAR SISTEMAS DE AVALIACAO
        $scope.buscarSistemasAvaliacao = function() { var promise = Servidor.buscar('sistemas-avaliacao',null); promise.then(function (response){ $scope.sistemaAvaliacoes = response.data; $scope.buscarModulos(); }); };
        //VALIDAR FORM
        $scope.validar = function (id) { return Servidor.validar(id); };
        //INICIA SELECTS
        $scope.initSelect = function () { $('#modulo, #sistemaAvaliacao, #modeloQuadroHorario').material_select('destroy'); $('#modulo, #sistemaAvaliacao, #modeloQuadroHorario').material_select(); };
        
        //BUSCAR MODULOS
        $scope.buscarModulos = function() {
            var promise = Servidor.buscar('modulos',{ 'curso': $scope.curso.id });
            promise.then(function (response){
                if (response.data.length) { $scope.modulos = response.data; } else { $scope.modulos = []; Materialize.toast('Este curso nao possui módulos.', 2500); }
                $scope.buscarModelosHorarios(); $timeout(function() { $('#modulo').material_select(); }, 250);
            });
        };

        //BUSCAR MODELO QUADRO HORARIO
        $scope.buscarModelosHorarios = function() {
            var promise = Servidor.buscar('modelo-quadro-horarios',{ 'curso':$scope.curso.id });
            promise.then(function (response){ $scope.modeloQuadroHorarios = response.data; $timeout(function() { $scope.initSelect(); }, 250); });
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.title-module').html($scope.titulo); $('#modal-ajuda-curso').modal(); $('.material-tooltip').remove();
            $('.counter').each(function(){  $(this).characterCounter(); });
            $('#etapaForm').keydown(function(event){ var keyCode = (event.keyCode ? event.keyCode : event.which); if (keyCode === 13) { $('#salvarEtapa').trigger('click'); } });
            Servidor.entradaPagina();
        };
        
        //CARREGAR MODULOS
        $scope.carregarModulo = function() {
            var promise = Servidor.buscarUm('cursos', $scope.etapa.curso.id);
            promise.then(function(response) { $scope.modulo = { 'curso': response.data, 'nome': '' }; $('#form-modal-modulo').modal(); $timeout(function() { Servidor.verificaLabels(); }, 150); });
        };

        //SALVAR MODULOS
        $scope.salvarModulo = function() {
            if ($scope.modulo.nome) {
                var promise = Servidor.finalizar($scope.modulo, 'modulos', 'Modulo');
                promise.then(function(response) {
                    $('#form-modal-modulo').closeModal(); $scope.modulos.push(response.data);
                    $timeout(function() { $('#modulo').material_select(); }, 150); $scope.etapa.modulo = response.data;
                });
            }
        };
        
        //CARREGAR ETAPA
        $scope.carregar = function (id){
            $scope.mostraProgresso();
            if (id === 'novo') {
                $('.title-module').html($scope.acao + ' Etapa'); $scope.buscarSistemasAvaliacao();
                if ($rootScope.etapaCurso !== undefined) { $scope.curso = $rootScope.etapaCurso; $scope.etapa.curso.id = $rootScope.etapaCurso.id; } else { window.location = '/#/etapas'; }
            } else {
                $scope.acao = "Editar"; var promise = Servidor.buscarUm('etapas',id); $('.title-module').html($scope.acao + ' Etapa');
                promise.then(function (response) { 
                    if (response.data !== undefined) { $scope.etapa = response.data; $scope.buscarSistemasAvaliacao(); } else { window.location = '/#/etapas'; }
                });
            }
            $timeout(function() {
                Servidor.verificaLabels(); $('#nomeEtapa').focus(); $scope.fechaProgresso();
            }, 500);
        };
        
        //SALVAR ETAPA
        $scope.finalizar = function (novaEtapa) {            
            if($scope.modeloQuadroHorarios.length === 1) { $scope.etapa.modeloQuadroHorario.id = $scope.modeloQuadroHorarios[0].id; }
            if ($scope.validar('validateEtapa') === true) {
                $scope.mostraProgresso(); $scope.etapa.sistemaAvaliacao = {id: $scope.etapa.sistemaAvaliacao.id };
                var result = Servidor.finalizar($scope.etapa, 'etapas', 'Etapa');
                result.then(function () { $scope.fechaProgresso(); window.location = '/#/etapas'; });
            }
        };

        //VERIFICA SELECT MODULO
        $scope.verificaSelectModulo = function (id) { if (id === $scope.etapa.modulo.id) { return 'selected'; } };
        //VERIFICA SELECT CURSO
        $scope.verificaSelectCurso = function (id) { if (id === $scope.curso.id) { return 'selected'; } };
        //VERIFICA SELECT SISTEMA DE AVALIACAO
        $scope.verificaSelectSistemaAvaliacao = function (id) { if (id === $scope.etapa.sistemaAvaliacao.id) {  $('#sistemaAvaliacao').material_select('destroy'); $('#sistemaAvaliacao').material_select(); return 'selected'; } };
        //VERIFICA SELECT MODELO QUADRO HORARIO
        $scope.verificaSelectModeloQuadroHorario = function (id) { if (id === $scope.etapa.modeloQuadroHorario.id) { $('#modeloQuadroHorario').material_select('destroy'); $('#modeloQuadroHorario').material_select(); return 'selected'; } };
        
        //INICIALIZANDO
        $scope.carregar($routeParams.id); $scope.inicializar();
    }]);
})();
