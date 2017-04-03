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
    var frequenciaModule = angular.module('frequenciaModule', ['frequenciaDirectives', 'servidorModule', 'erudioConfig']);
    frequenciaModule.controller('FrequenciaController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('FREQUENCIA'); $scope.isAdmin = Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('frequencia'); $scope.lista = true;
        //ATRIBUTOS
        $scope.titulo = "Frequências"; $scope.unidade = {id:null}; $scope.unidades = []; $scope.nomeUnidade = null; $scope.busca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}};
        $scope.disciplinas = []; $scope.alunos = []; $scope.aulas = []; $scope.quantidadeAulas = 0; $scope.disciplina = {id:null};
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-frqeuencia').modal('open'); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        
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
        
        //CARREGA O SELECT DE ETAPAS
        $scope.buscarEtapas = function (id) {
            if (id) {
                var promise = Servidor.buscar('etapas', {'curso': id});
                promise.then(function (response) {
                    $scope.etapas = response.data; if ($scope.etapas.length === 0) { Materialize.toast('Nenhuma etapa cadastrada', 1500); }
                    $timeout(function () { $('#etapa').material_select('destroy'); $('#etapa').material_select(); }, 100);
                });
            }
        };
        
        //REINICIA A BUSCA
        $scope.reiniciarBusca = function () {
            $scope.nomeUnidade = null; $scope.busca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}}; $scope.cursos = []; $scope.etapas = []; $scope.turmas = [];
            $timeout(function () { $('#unidade, #etapa, #curso, #turma').material_select('destroy'); $('#unidade, #etapa, #curso, #turma').material_select(); }, 100);
        };
        
        //BUSCA DE TURMAS
        $scope.buscarTurmas = function (origem) {
            $scope.mostraProgresso();
            if (!$scope.unidade.id && origem === 'formBusca') {
                Servidor.customToast('Selecione uma unidade para realizar a busca de turmas'); $scope.fechaProgresso(); $scope.turmas = [];
            } else if ($scope.unidade.id) {
                var promise = Servidor.buscar('turmas', {'unidadeEnsino': $scope.unidade.id, 'etapa': $scope.busca.etapa.id, 'curso': $scope.busca.curso.id});
                promise.then(function (response) {
                    $scope.turmas = response.data;
                   if ($scope.turmas.length === 0) { Servidor.customToast('Nenhuma turma encontrada. Verifique os parâmetros de busca e tente novamente.'); }
                   else { $timeout(function(){ $('#turma').material_select('destroy'); $('#turma').material_select(); },500); } $scope.fechaProgresso();
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
            $scope.unidade = unidade; $timeout(function(){ Servidor.verificaLabels(); $scope.buscarCursos(); $('.dropdown-button').dropdown('close'); },100);
        };
        
        //LISTAR ALUNOS
        $scope.listarDisciplinas = function () {
            $scope.mostraProgresso(); var promiseD = Servidor.buscar('disciplinas-ofertadas',{turma: $scope.busca.turma.id});
            promiseD.then(function(response){ 
                if ($scope.disciplina.id === null) { $scope.disciplinas = response.data; $scope.disciplina.id = response.data[0].id; }
                var promiseAulas = Servidor.buscar('turmas/'+$scope.busca.turma.id+'/aulas',{disciplina: $scope.disciplina.id, mes: 3});
                promiseAulas.then(function(response){ 
                    $scope.aulas = response.data; $scope.quantidadeAulas = new Array(response.data.length);
                    var promise = Servidor.buscar('enturmacoes',{turma: $scope.busca.turma.id});
                    promise.then(function(response){ $scope.alunos = response.data; $scope.fechaProgresso(); });
                });
            });
        };
        
        //DAR FREQUENCIA
        $scope.darFrequencia = function ($index, aluno){
            if ($("#"+$index+"i"+aluno.id+"i").hasClass('temFalta')) {
                $("#"+$index+"i"+aluno.id+"i").html('location_on').css('color','#388e3c').removeClass('temFalta'); Servidor.customToast("Presença computada.");
                $("#"+$index+"i"+aluno.id+"j").hide();
            } else {
                $("#"+$index+"i"+aluno.id+"i").html('location_off').css('color','#f44336').addClass('temFalta'); Servidor.customToast("Falta computada.");
                $("#"+$index+"i"+aluno.id+"j").show();
            }
            
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $scope.mostraProgresso(); $('.material-tooltip').remove(); $scope.buscarUnidades();
            $timeout(function () {
                $('.titulo-typo').html($scope.titulo); $('#modal-ajuda-frquencia').modal(); $('select').material_select('destroy'); $('select').material_select(); $('.tooltipped').tooltip({delay: 50});
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                $('.dropdown-button').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                $scope.fechaProgresso();
            }, 300);
        };
            
        $scope.inicializar();
    }]);
})();
