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
    //DEFINIÇÃO DO CONTROLADOR
    turmaFormModule.controller('TurmaFormController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$routeParams', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $routeParams) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.isAdmin = Servidor.verificaAdmin(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('TURMA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateForm('turmas');
        //ATRIBUTOS
        $scope.progresso = false; $scope.cortina = false; $scope.unidades = []; $scope.cursos = []; $scope.etapas = []; $scope.disciplinas = []; $scope.disciplina = {id:null}; $scope.disciplinasSalvas = [];
        $scope.disciplinasOfertadas = []; $scope.turmas = []; $scope.turmaBusca = {curso: {id:null}, etapa:{id:null}}; $scope.nomeUnidadeForm = null; $scope.acao = 'Adicionar'; $scope.recemRemovidas = []; $scope.recemAdicionadas = [];
        $scope.turma = { 'nome': '', 'apelido': '', 'calendario': {id: null}, 'limiteAlunos': null, 'turno': {id: null}, 'etapa': {id: null}, 'unidadeEnsino': {id: null}, 'quadroHorario': {id: null}, 'disciplinas': [] };
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
        $scope.prepararVoltar = function (objeto) { if (objeto.nome && !objeto.id) { $('#modal-certeza').modal('open'); } else { window.location = $scope.config.dominio+'/#/turmas'; } };
        //MOSTRA LABELS MENU FAB
        $scope.mostrarLabels = function () { $('.toolchip').fadeToggle(250); };
        //MOSTRA DISCIPLINAS OFERTADAS
        $scope.$watch("turma.etapa.id", function(query){ $scope.buscaDisciplinaOfertada(); });
        
        //BUSCA DISCIPLINA OFERTADA
        $scope.buscaDisciplinaOfertada = function () {
            var promise = Servidor.buscarUm('etapas',$scope.turma.etapa.id);
            promise.then(function(response){
                if (response.data.integral) {
                    if ($routeParams.id !== 'novo') {
                        var promiseDisciplinas = Servidor.buscar('disciplinas',{etapa: $scope.turma.etapa.id});
                        promiseDisciplinas.then(function(response){
                            $scope.disciplinas = response.data;
                            var promiseDisciplinasOfertada = Servidor.buscar('disciplinas-ofertadas',{turma: $scope.turma.id});
                            promiseDisciplinasOfertada.then(function(response){
                                $scope.disciplinasOfertadas = response.data;
                                $timeout(function(){ for (var i=0; i<$scope.disciplinasOfertadas.length; i++) { $('.d'+$scope.disciplinasOfertadas[i].disciplina.id).addClass('light-blue accent-2'); } },500);
                            });
                        });
                        $timeout(function(){$('.chip').click(function(){ $scope.situacaoDisciplinaEdit($(this).attr('id')); });},500);
                    } else {
                        var promiseDisciplinas = Servidor.buscar('disciplinas',{etapa: $scope.turma.etapa.id});
                        promiseDisciplinas.then(function(response){ $scope.disciplinas = response.data; });
                    }
                    
                }
            });
        };
        
        //SELECIONA DISCIPLINA OFERTADA
        $scope.situacaoDisciplina = function (disciplina) {
            if ($routeParams.id === 'novo') {
                if ($scope.disciplinasOfertadas.length === 0) {
                    $('.d'+disciplina.id).addClass('light-blue accent-2'); $scope.disciplinasOfertadas.push(disciplina.id);
                } else {
                    var i = $scope.disciplinasOfertadas.indexOf(disciplina.id);
                    if (i >= 0) { $('.d'+disciplina.id).removeClass('light-blue accent-2'); $scope.disciplinasOfertadas.splice(i,1);
                    } else { $('.d'+disciplina.id).addClass('light-blue accent-2'); $scope.disciplinasOfertadas.push(disciplina.id); }
                }
            }
        };
        
        //SELECIONA DISCIPLINA OFERTADA
        $scope.situacaoDisciplinaEdit = function (id) {
            id = parseInt(id);
            if ($scope.disciplinasOfertadas.length === 0) {
                $('.d'+id).addClass('light-blue accent-2'); $scope.disciplinasOfertadas.push(id);
            } else {
                $timeout(function(){
                    var indexes = [];
                    for (var i=0; i<$scope.disciplinasOfertadas.length; i++) { indexes.push($scope.disciplinasOfertadas[i].disciplina.id); }
                    $timeout(function(){
                        var ind = indexes.indexOf(id);
                        if (ind >= 0) {
                            $scope.mostraProgresso();
                            var removePromise = Servidor.buscarUm('disciplinas-ofertadas',$scope.disciplinasOfertadas[ind].id);
                            removePromise.then(function(response){ $('.d'+id).removeClass('light-blue accent-2'); Servidor.remover(response.data,'Disciplina'); $scope.fechaProgresso(); });
                            $timeout(function(){ $scope.buscaDisciplinaOfertada(); },500);
                        } else {
                            $('.d'+id).addClass('light-blue accent-2');
                            var promise = Servidor.finalizar({disciplina: {id:id}, turma: {id:$scope.turma.id}},'disciplinas-ofertadas','Disciplina');
                            promise.then(function(response){ $scope.buscaDisciplinaOfertada(); });
                        }
                    },500);
                },500);
            }
        };
        
        //CARREGA SELECT CURSOS
        $scope.buscarCursos = function (todos) {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('cursos-ofertados', {unidadeEnsino: $scope.unidade.id});
            promise.then(function(response) {
                $scope.cursos = response.data;
                if(response.data.length) {
                    if($scope.cursos.length === 1) { $scope.buscarEtapas($scope.cursos[0].curso.id); }
                    $timeout(function () { $('#cursoForm').material_select('destroy'); $('#cursoForm').material_select(); $scope.fechaProgresso(); }, 500);
                } else { $scope.fechaProgresso(); Servidor.customToast('Não há cursos nesta unidade.'); }
            });
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
            $("#unidadeTurmaAutoCompleteForm").val(unidade.tipo.sigla + ' ' + unidade.nome); $('#dropUnidadesTurmaForm').hide();
            $scope.turma.unidadeEnsino.id = unidade.id; $timeout(function(){ Servidor.verificaLabels(); $scope.buscarCursos(); },100);
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
                if ($scope.disciplinasOfertadas.length > 0) {
                    for (var i=0; i<$scope.disciplinasOfertadas.length; i++) {
                        var ofertada = angular.copy($scope.disciplina); ofertada.id = $scope.disciplinasOfertadas[i];
                        if (i === $scope.disciplinasOfertadas.length-1) {
                            if ($routeParams.id === 'novo') { $scope.disciplinasOfertadas[i] = {disciplina: ofertada}; $scope.turma.disciplinas = $scope.disciplinasOfertadas; }
                            var promise = Servidor.finalizar($scope.turma, 'turmas', 'Turma');
                            promise.then(function (response) { $scope.turma = response.data; $scope.fechaProgresso(); window.location = ErudioConfig.dominio + "/#/turmas/"+$scope.turma.id; });
                        }
                    }
                } else { Servidor.customToast("A etapa desta turma não possui disciplinas."); $scope.fechaProgresso(); }
                
                /*var promise = Servidor.buscar('disciplinas', {etapa: $scope.turma.etapa.id});
                promise.then(function(response) {
                    if(response.data.length) {
                        $scope.turma.unidadeEnsino = {id: $scope.turma.unidadeEnsino.id}; $scope.turma.calendario = {id: $scope.turma.calendario.id};
                        $scope.turma.etapa = {id: $scope.turma.etapa.id}; $scope.turma.turno = {id: $scope.turma.turno.id};
                        
                    } else { Servidor.customToast("A etapa desta turma não possui disciplinas."); $scope.fechaProgresso(); }
                });*/
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
                        $scope.buscarEtapas($scope.turma.etapa.curso.id); $scope.buscarTurnos(); $scope.buscarUnidades();
                        $scope.buscarCalendarios($scope.turma.unidadeEnsino.id); $scope.buscarQuadroHorarios(); 
                        $timeout(function () { Servidor.verificaLabels(); $scope.fechaProgresso(); }, 500);
                    }, 500);
                });
            } else {
                $scope.acao = "Cadastrar"; $('#alunos').removeClass('yellow waves-effect'); $('.title-module').html($scope.acao + ' Turma');
                $timeout(function(){ $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' }); },100);
                $timeout(function () {
                    Servidor.verificaLabels(); $scope.fechaProgresso();
                    $scope.buscarCalendarios(); $scope.buscarTurnos(); $scope.buscarUnidades(); $scope.buscarQuadroHorarios();
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
