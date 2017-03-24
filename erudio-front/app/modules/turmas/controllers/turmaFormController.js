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
    var turmaFormModule = angular.module('turmaFormModule', ['servidorModule', 'turmaDirectives','erudioConfig']);
    
    /*turmaFormModule.service('TurmaService', [function () {
        this.abrirFormulario = false;
        this.abreForm = function () { this.abrirFormulario = true; };
        this.fechaForm = function() { this.abrirFormulario = false; this.setEnturmacao({id:null}); };
        this.enturmacao;
        this.setEnturmacao = function(enturmacao) { this.enturmacao = enturmacao; };
        this.turma;
        this.setTurma = function(turma) { this.turma = turma; };
    }]);*/

    //DEFINIÇÃO DO CONTROLADOR
    turmaFormModule.controller('TurmaFormController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$routeParams', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $routeParams) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.isAdmin = Servidor.verificaAdmin(); $scope.config = ErudioConfig;
        $scope.escrita = Servidor.verificaEscrita('TURMA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateForm('turmas');
        //ATRIBUTOS
        $scope.progresso = false; $scope.cortina = false; $scope.unidades = []; $scope.cursos = []; $scope.etapas = [];
        $scope.turmas = []; $scope.turmaBusca = {curso: {id:null}, etapa:{id:null}}; $scope.nomeUnidadeForm = null; $scope.acao = 'Adicionar';
        $scope.turma = { 'nome': '', 'apelido': '', 'calendario': {id: null}, 'limiteAlunos': null, 'turno': {id: null}, 'etapa': {id: null}, 'unidadeEnsino': {id: null}, 'quadroHorario': {id: null} };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //CONTROLE SELECTS
        $scope.verificaUnidade = function (id) { if (id === $scope.turma.unidadeEnsino.id) { return true; } };
        $scope.verificaCurso = function (id) { if ($scope.turma.id) { if (id === $scope.turma.etapa.curso.id) { return true; } } };
        $scope.verificaEtapa = function (id) { if (id === $scope.turma.etapa.id) { return true; } };
        $scope.verificaTurno = function (id) { if (id === $scope.turma.turno.id) { return true; } };
        $scope.verificaQuadroHorario = function (id) { if (id === $scope.turma.quadroHorario.id) { return true; } };
        $scope.verificaCalendario = function (id) { if (id === $scope.turma.calendario.id) { return true; } };
        //PREPARA VOLTAR
        $scope.prepararVoltar = function (objeto) { if (objeto.nome && !objeto.id) { $('#modal-certeza').openModal(); } else { window.location = '/#/turmas'; } };
        //MOSTRA LABELS MENU FAB
        $scope.mostrarLabels = function () { $('.toolchip').fadeToggle(250); };
        
        //CARREGA SELECT CURSOS
        $scope.buscarCursos = function (todos) {
            $scope.mostraProgresso();
            if(todos) {
                var promise = Servidor.buscar('cursos', null);
                promise.then(function (response) { $scope.cursos = response.data; $timeout(function () { $('#cursoForm').material_select('destroy'); $('#cursoForm').material_select(); $scope.fechaProgresso(); }, 1000); });
            } else {
                var promise = Servidor.buscarUm('unidades-ensino', sessionStorage.getItem('unidade'));
                promise.then(function(response) {
                    $scope.cursos = response.data.cursos;
                    if(response.data.cursos.length) {
                        if($scope.cursos.length === 1) { $scope.buscarEtapas($scope.cursos[0].id); }
                        $timeout(function () { $('#cursoForm').material_select('destroy'); $('#cursoForm').material_select(); $scope.fechaProgresso(); }, 1000);
                    } else { $scope.buscarCursos(true); }
                });
            }                    
        };
        
        //PREPARA BUSCA UNIDADES
        $scope.preparaBuscaUnidades = function (str) { $scope.nomeUnidadeForm = str; $scope.buscarUnidades(); };
        
        //CARREGA O SELECT DE UNIDADES
        $scope.buscarUnidades = function () {
            if ($scope.nomeUnidadeForm !== undefined && $scope.nomeUnidadeForm !== null) {
                if ($scope.nomeUnidadeForm.length > 4) { $scope.mostraProgresso(); $scope.verificaAlocacao($scope.nomeUnidadeForm); } else { $scope.unidades = []; }
            } else { $scope.mostraProgresso(); $scope.verificaAlocacao(null); }
        };
        
        //VERIFICA SE HÁ ALOCAÇÃO SELECIONADA
        $scope.verificaAlocacao = function (nomeUnidade) {
            var alocacao = sessionStorage.getItem('alocacao');
            if ($scope.escrita) {
                var promise = Servidor.buscar('unidades-ensino', {'nome': nomeUnidade});
                promise.then(function (response) {
                    $scope.unidades = response.data;
                    $timeout(function () { $('#unidadeForm').material_select('destroy'); $('#unidadeForm').material_select(); $scope.fechaProgresso(); }, 500);
                });
            } else {
                if (Servidor.verificarPermissoes('TURMA')) {
                    var promise = Servidor.buscarUm('alocacoes', alocacao);
                    promise.then(function (response) {
                        $scope.alocacao = response.data; $scope.unidades = [$scope.alocacao.instituicao];
                        if ($scope.unidades.length === 1) { $scope.unidade = $scope.alocacao.instituicao; }
                        $timeout(function () { $('#unidadeForm').material_select('destroy'); $('#unidadeForm').material_select(); $scope.fechaProgresso(); }, 500);
                    });
                }
            }
        };
        
        //CARREGA O SELECT DE ETAPAS
        $scope.buscarEtapas = function (id) {
            if (id) {
                $scope.turmaBusca.etapa.id = null; var promise = Servidor.buscar('etapas', {'curso': id});
                promise.then(function (response) {
                    $scope.etapas = response.data; if ($scope.etapas.length === 0) { Materialize.toast('Nenhuma etapa cadastrada', 1500); }
                    $timeout(function () { $('#etapaForm').material_select('destroy'); $('#etapaForm').material_select(); }, 100);
                });
            }
        };
        
        //CARREGA ARRAY DE TURNOS
        $scope.buscarTurnos = function () {
            var promise = Servidor.buscar('turnos', null);
            promise.then(function (response) { $scope.turnos = response.data; $timeout(function() { $('#turnoTurmaForm').material_select('destroy'); $('#turnoTurmaForm').material_select(); }, 500); });
        };
        
        //CARREGA CALENDARIOS
        $scope.buscarCalendarios = function (unidade) {
            if (!unidade) { unidade = sessionStorage.getItem('unidade'); }
            unidade = ($scope.isAdmin) ? unidade : sessionStorage.getItem('unidade');
            var promise = Servidor.buscar('calendarios', {instituicao: unidade});
            promise.then(function (response) {
                $scope.calendarios = response.data;
                if ($scope.calendarios.length > 1) {
                    $scope.calendarios.forEach(function(calendario) { if (calendario.instituicao.id === $scope.instituicao) { $scope.turma.calendario = calendario; }});
                    $timeout(function() { $('#calendarioTurmaForm').material_select('destroy'); $('#calendarioTurmaForm').material_select(); }, 500);
                } else { $scope.turma.calendario = $scope.calendarios[0]; $timeout(function() { $('#calendarioTurmaForm').material_select('destroy'); $('#calendarioTurmaForm').material_select(); }, 500); }
            });
        };
        
        //SELECIONA UNIDADE DE ENSINO
        $scope.selecionaUnidade = function(unidade) {
            if (unidade.tipo === undefined) { unidade.tipo = {sigla:''}; }
            $scope.nomeUnidadeForm = unidade.tipo.sigla + ' ' + unidade.nome; $scope.unidade = unidade;
            $("#unidadeTurmaAutoCompleteForm").val(unidade.tipo.sigla + ' ' + unidade.nome);
            $scope.turma.unidadeEnsino.id = unidade.id; $timeout(function(){ Servidor.verificaLabels(); },100);
        };
        
        //CARREGANDO QUADRO HORARIOS DO TURNO
        $scope.carregarQuadroHorariosCompativeis = function(turnoId) {
            $scope.quadroHorariosCompativeis = []; $scope.turma.quadroHorario = {id: null}; var inicio; var termino;
            $('#turnoTurmaForm').material_select('destroy'); $('#turnoTurmaForm').material_select(); turnoId = parseInt(turnoId);
            $scope.turnos.forEach(function(t) { if(turnoId === t.id) { var turno = t; inicio = turno.inicio.replace(':',''); inicio = parseInt(inicio); termino = turno.termino.replace(':',''); termino = parseInt(termino); }  });
            $scope.quadroHorarios.forEach(function(qh) {
                var qhInicio = qh.inicio.replace(':',''); qhInicio = parseInt(qhInicio);
                var qhTermino = qh.termino.replace(':',''); qhTermino = parseInt(qhTermino);
                if(qhInicio >= inicio && qhTermino <= termino) { $scope.quadroHorariosCompativeis.push(angular.copy(qh)); }
            });
            setTimeout(function() {
                $('#quadroHorarioTurmaFormulario').material_select('destroy'); $('#quadroHorarioTurmaFormulario').material_select();
                if(!$scope.quadroHorariosCompativeis.length) { Servidor.customToast('Não há nenhum quadro de horarios compatível com este turno.'); }
            }, 50);              
        };
        
            
        //CARREGA ARRAY DE QUADRO DE HORARIOS
        $scope.buscarQuadroHorarios = function (unidade) {
            if(unidade === undefined || !unidade) { unidade = ($scope.isAdmin) ? $scope.turma.unidadeEnsino.id : sessionStorage.getItem('unidade'); }
            var promise = Servidor.buscar('quadro-horarios', {unidadeEnsino: unidade});
            promise.then(function (response) {
                $scope.quadroHorarios = response.data;                    
                if ($scope.quadroHorarios.length === 1) { $scope.turma.quadroHorario = $scope.quadroHorarios[0]; }
                $timeout(function() { $('#quadroHorarioTurmaFormulario').material_select(); }, 500);
            });
        };

        /* VALIDAÇÃO DE FORMULÁRIO */
        $scope.validar = function (id) { if (Servidor.validar(id)) { return true; } };

        /* SALVAR TURMA */
        $scope.finalizar = function () {
            $scope.mostraProgresso();
            if ($scope.validar('validate-turma')) {
                var promise = Servidor.buscar('disciplinas', {etapa: $scope.turma.etapa.id});
                promise.then(function(response) {
                    if(response.data.length) {
                        $scope.turma.unidadeEnsino = {id: $scope.turma.unidadeEnsino.id}; $scope.turma.calendario = {id: $scope.turma.calendario.id};
                        $scope.turma.etapa = {id: $scope.turma.etapa.id}; $scope.turma.turno = {id: $scope.turma.turno.id};
                        var promise = Servidor.finalizar($scope.turma, 'turmas', 'Turma');
                        promise.then(function (response) { $scope.turma = response.data; $scope.fechaProgresso(); window.location = "/#/turmas/"+$scope.turma.id; });
                    } else { Servidor.customToast("A etapa desta turma não possui disciplinas."); $scope.fechaProgresso(); }
                });
            }
        };

        /* CARREGA TURMA PARA EDIÇÃO */
        $scope.carregarTurma = function (turma) {
            $('#turmaForm').show(); $('#form').addClass('active');
            $('#nome').focus(); $scope.mostraProgresso();
            if (turma !== 'novo') {
                $scope.acao = "Editar"; $('.title-module').html($scope.acao + ' Turma');
                var promise = Servidor.buscarUm('turmas', turma);
                promise.then(function (result) {
                    $scope.turma = result.data; $scope.quadroHorariosCompativeis = []; $scope.quadroHorariosCompativeis.push(result.data.quadroHorario); 
                    $timeout(function () {
                        $scope.buscarEtapas($scope.turma.etapa.curso.id); $scope.buscarTurnos(); $scope.buscarCursos(true); $scope.buscarUnidades();
                        $scope.buscarCalendarios($scope.turma.unidadeEnsino.id); $scope.buscarQuadroHorarios(); 
                        $timeout(function () { Servidor.verificaLabels(); $scope.fechaProgresso(); }, 500);
                    }, 500);
                });
            } else {
                $scope.acao = "Cadastrar"; $('#alunos').removeClass('yellow waves-effect'); $('.title-module').html($scope.acao + ' Turma');
                $timeout(function(){ $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' }); },100);
                $timeout(function () {
                    Servidor.verificaLabels(); $scope.fechaProgresso();
                    $scope.buscarCalendarios(); $scope.buscarTurnos(); $scope.buscarCursos(true); $scope.buscarUnidades(); $scope.buscarQuadroHorarios();
                }, 500);
            }                
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.material-tooltip').remove();
            $timeout(function () {
                $('.tooltipped').tooltip({delay: 50}); $('ul.tabs').tabs(); $('.counter').each(function () { $(this).characterCounter(); });
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                $('select').material_select('destroy'); $('select').material_select(); Servidor.entradaPagina();
            }, 1000);
        };
        
        $scope.inicializar(); $scope.carregarTurma($routeParams.id); 
    }]);
})();
