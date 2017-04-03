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

(function () {
    var turmaModule = angular.module('turmaModule', ['servidorModule', 'turmaDirectives','erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    turmaModule.controller('TurmaController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$rootScope', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $rootScope) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('TURMA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('turmas'); $scope.lista = true;
        //ATRIBUTOS
        $scope.titulo = "Turmas"; $scope.progresso = false; $scope.cortina = false; $scope.unidades = []; $scope.cursos = []; $scope.etapas = [];
        $scope.turmas = []; $scope.turmaBusca = {curso: {id:null}, etapa:{id:null}}; $scope.nomeUnidade = null; $scope.unidade = {id:null};
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-turma').modal('open'); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        
        //CARREGA SELECT CURSOS
        $scope.buscarCursos = function () {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('cursos-ofertados', {unidadeEnsino: $scope.unidade.id});
            promise.then(function(response) {
                $scope.cursos = response.data;
                if(response.data.length) {
                    if($scope.cursos.length === 1) { $scope.buscarEtapas($scope.cursos[0].curso.id); }
                    $timeout(function () { $('#curso').material_select('destroy'); $('#curso').material_select(); $scope.fechaProgresso(); }, 500);
                } else { $scope.fechaProgresso(); Servidor.customToast('Não há cursos nesta unidade.'); }
            });
        };
        
        //PREPARAÇÃO PARA REMOÇÃO DE TURMA
        $scope.prepararRemover = function (turma) {
            if(turma.quantidadeAlunos > 0) { Servidor.customToast('Esta turma possui enturmações, portanto não é possível removê-la.');
            } else { $scope.turmaRemover = turma; $('#remove-modal-turma').modal('open'); }                
        };

        //REMOÇÃO DE TURMA
        $scope.remover = function () {
            $scope.mostraProgresso(); Servidor.remover($scope.turmaRemover, 'Turma');
            $timeout(function () { $scope.buscarTurmas(); $scope.fechaProgresso(); }, 500);
        };
        
        //REINICIA A BUSCA
        $scope.reiniciarBusca = function () {
            $scope.nomeUnidade = null; $scope.turmaBusca = {curso: {id:null}, etapa:{id:null}};
            $timeout(function () { $('#unidade, #etapa, #curso').material_select('destroy'); $('#unidade, #etapa, #curso').material_select(); }, 100);
        };
        
        //CARREGA O SELECT DE UNIDADES
        $scope.buscarUnidades = function () {
            if ($scope.nomeUnidade !== undefined && $scope.nomeUnidade !== null) {
                if ($scope.nomeUnidade.length > 4) { $scope.mostraProgresso(); $scope.verificaAlocacao($scope.nomeUnidade); } else { $scope.unidades = []; }
            } else { $scope.mostraProgresso(); $scope.verificaAlocacao(null); }
        };
        
        //VERIFICA SE HÁ ALOCAÇÃO SELECIONADA
        $scope.verificaAlocacao = function (nomeUnidade) {
            if ($scope.escrita) {
                var promise = Servidor.buscar('unidades-ensino', {'nome': nomeUnidade});
                promise.then(function (response) {
                    $scope.unidades = response.data; $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); $scope.fechaProgresso(); }, 500);
                });
            } else {
                if (Servidor.verificarPermissoes('TURMA')) {
                    var promise = Servidor.buscar('users',{username:sessionStorage.getItem('username')});
                    promise.then(function(response) {
                        var user = response.data[0];
                        $scope.atribuicoes = user.atribuicoes;
                        $timeout(function () {
                            for (var i=0; $scope.atribuicoes.length; i++) {
                                if ($scope.atribuicoes[i].instituicao.instituicaoPai !== undefined) { $scope.unidades.push($scope.atribuicoes[i].instituicao); }
                                if (i === $scope.atribuicoes.length-1) {
                                    if ($scope.unidades.length === 1) { $scope.unidade = $scope.unidades[0]; $scope.buscarCursos(); }
                                    $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); $scope.fechaProgresso(); }, 500);
                                }
                            }
                        },500);
                    });
                }
            }
        };
        
        //CARREGA O SELECT DE ETAPAS
        $scope.buscarEtapas = function (id) {
            if (id) {
                //$scope.turmaBusca.etapa.id = null;
                var promise = Servidor.buscar('etapas', {'curso': id});
                promise.then(function (response) {
                    $scope.etapas = response.data; if ($scope.etapas.length === 0) { Materialize.toast('Nenhuma etapa cadastrada', 1500); }
                    if ($rootScope.ultimaBuscaTurma === undefined) {
                        $timeout(function () { $('#etapa').material_select('destroy'); $('#etapa').material_select(); }, 100);
                    }
                });
            }
        };
        
        //BUSCA DE TURMAS
        $scope.buscarTurmas = function (origem) {
            $scope.mostraProgresso();
            if (!$scope.unidade.id && origem === 'formBusca') {
                Servidor.customToast('Selecione uma unidade para realizar a busca de turmas'); $scope.fechaProgresso(); $scope.turmas = [];
            } else if ($scope.unidade.id) {
                var promise = Servidor.buscar('turmas', {'unidadeEnsino': $scope.unidade.id, 'etapa': $scope.turmaBusca.etapa.id, 'curso': $scope.turmaBusca.curso.id});
                promise.then(function (response) {
                    $scope.turmas = response.data; $rootScope.ultimaBuscaTurma = $scope.turmaBusca; 
                   if ($scope.turmas.length > 0) {
                        for (var i =0; i<$scope.turmas.length; i++) {
                            var promise = Servidor.buscar('vagas', {'turma': $scope.turmas[i].id});
                            promise.then(function(response){
                                var vagas = response.data; for (var j=0; j<vagas.length; j++) { if (vagas[j].solicitacaoVaga !== undefined) { $scope.turmas[i].quantidadeAlunos++; } }
                            });
                        }
                        $('.tooltipped').tooltip('remove');
                        $timeout(function () { $('.modal-trigger').modal(); $('.tooltipped').tooltip({delay: 50}); }, 500);
                    } else { Servidor.customToast('Nenhuma turma encontrada. Verifique os parâmetros de busca e tente novamente.'); }
                    $scope.fechaProgresso();
                });
            } else { $scope.reiniciarBusca(); }
        };
        
        //SELECAO DE ETAPA
        $scope.selecionarEtapa = function(etapaId) {
            var promise = Servidor.buscar('disciplinas', {etapa: etapaId});
            promise.then(function(response) {
                var disciplinas = response.data; var opcional = true; $scope.requisicoes = 0;
                disciplinas.forEach(function(d) {
                    $scope.requisicoes++; var promise = Servidor.buscarUm('disciplinas', d.id);
                    promise.then(function(response) {
                        if (!response.data.opcional) { opcional = false; }
                    });
                });
            });
        };
        
        //SELECIONA UNIDADE DE ENSINO
        $scope.selecionaUnidade = function(unidade) {
            if (unidade.tipo === undefined) { unidade.tipo = {sigla:''}; } $scope.nomeUnidade = unidade.tipo.sigla + ' ' + unidade.nome;
            $scope.unidade = unidade; $rootScope.ultimaUnidadeBuscaTurma = unidade; $timeout(function(){ Servidor.verificaLabels(); $scope.buscarCursos(); },100);
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.title-module').html($scope.titulo); $('.material-tooltip').remove();
            if ($rootScope.ultimaBuscaTurma !== null && $rootScope.ultimaBuscaTurma !== undefined) { 
                $scope.selecionaUnidade($rootScope.ultimaUnidadeBuscaTurma); $scope.turmaBusca = $rootScope.ultimaBuscaTurma; $scope.buscarTurmas();
                $scope.buscarEtapas($rootScope.ultimaBuscaTurma.curso.id);
            }
            $timeout(function () {
                $('.tooltipped').tooltip({delay: 50}); $('ul.tabs').tabs(); $('#modal-ajuda-turma').modal(); 
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                $('select').material_select('destroy'); $('select').material_select(); Servidor.entradaPagina();
            }, 1000);
        };

        //INICIALIZANDO
        $scope.buscarUnidades(); $scope.inicializar();
    }]);
})();
