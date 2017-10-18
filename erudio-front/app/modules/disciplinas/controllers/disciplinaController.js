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
    var disciplinaModule = angular.module('disciplinaModule', ['servidorModule', 'disciplinaDirectives', 'etapaDirectives']);

    disciplinaModule.service('DisciplinaService', [function () {
            this.recebeCurso = false;
            this.abrirFormulario = false;
            this.disciplinas = {};
            this.etapa = {};
            this.abreForm = function () {this.abrirFormulario = true;};
            this.fechaForm = function () {this.abrirFormulario = false;};
        }]);

    /* Controller */
    disciplinaModule.controller('DisciplinaController', ['$scope', '$timeout', 'Servidor', 'Restangular', 'DisciplinaService', 'EtapaService', '$templateCache', function ($scope, $timeout, Servidor, Restangular, DisciplinaService, EtapaService, $templateCache){
            $templateCache.removeAll();
            
            $scope.escrita = Servidor.verificaEscrita('DISCIPLINA');            
            /* Atributos Específicos */
            $scope.disciplinas = [];
            $scope.etapas = [];
            $scope.curso = {'id': null};
            $scope.etapa = {'id': null, 'curso': {'id': null} };
            $scope.EtapaService = EtapaService;
            $scope.DisciplinaService = DisciplinaService;
            $scope.alterarDisciplina = '';

            /* Estrutura de Disciplina */
            $scope.disciplina = {nome: null, nomeExibicao: null, cargaHoraria: null, opcional: false, curso: {id: null}, etapa: {id: null}};
            $scope.limparDisciplina =  function() { $scope.disciplina = {nome: null, nomeExibicao: null, cargaHoraria: null, opcional: false, curso: {id: null}, etapa: {id: null}}; };
            $scope.limparBusca = $scope.etapa = {'id': null, 'curso': {'id': null} };
            $scope.disciplinasModulares = [];
            
            /* Validar formulário */
            $scope.validacao = function (id){ return Servidor.validar(id); };

            /* Recebe os dados de Etapa */
            $scope.$watch("DisciplinaService", function (query){
                if (DisciplinaService.abrirFormulario && !DisciplinaService.recebeCurso){
                    var etapa = $scope.EtapaService.etapa;
                    var promise = Servidor.buscarUm('etapas', etapa.id);
                    promise.then(function (response) {
                        $scope.buscarEtapas(response.data.curso.id);
                        $scope.etapa = response.data;
                        $scope.curso = response.data.curso;
                        $scope.buscarDisciplinas(etapa.id);
                    });
                }
            });

            /* Carregar o Formulario */
            $scope.carregarForm = function (disciplina, alterar){
                $scope.alterarDisciplina = alterar;
                if (disciplina) {
                    $scope.disciplina = disciplina;
                    $('#cargaHoraria').prop('disabled', true);
                } else {
                    $scope.disciplina.etapa.id = $scope.etapa.id;
                    $scope.disciplina.curso.id = $scope.curso.id;
                    $('#cargaHoraria').prop('disabled', false);
                }
                $timeout(function(){
                    Servidor.verificaLabels();
                    $('#nomeDisciplina').focus();
                    Servidor.inputNumero();
                }, 50);
                $scope.editando = true;
            };

            $scope.intraForms = function(curso) {
                EtapaService.curso = curso;
                EtapaService.abreForm();
            };

            /* Salvar Disciplina */
            $scope.salvarDisciplina = function(disciplina){
                if($scope.validacao('validateDisciplina')===true){
                    var result = Servidor.finalizar($scope.disciplina, 'disciplinas', 'Disciplina');
                        result.then(function (response) {
                            $scope.editando = false;
                            $scope.limparDisciplina();
                            $scope.buscarDisciplinas(response.data.etapa.id);
                    });
                };
            };

            /* Buscar etapa */
            $scope.buscarEtapas = function(cursoId){
                $scope.disciplinas = [];
                $scope.etapas = [];
                var promise = Servidor.buscar('etapas', {'curso': cursoId});
                promise.then(function(response){
                    if (response.data.length) {
                        $scope.etapas = response.data;
                    } else {
                        Materialize.toast('Nenhuma etapa encontrada.', 1500);
                    }
                    if (DisciplinaService.recebeCurso) {
                        DisciplinaService.recebeCurso = false;
                        $scope.etapa.id = EtapaService.etapa.id;
                        $scope.selecionaEtapa();
                    }
                    $timeout(function () {
                         $('#etapa').material_select('destroy');
                         $('#etapa').material_select();
                    }, 150);

                });
            };

            /* Verifica Select Curso */
            $scope.verificaSelectCurso = function(id){
                if (id === $scope.curso.id){ return true; }
            };

            /* Verifica Select etapa */
            $scope.verificaSelectEtapa = function(id){
                if (id === $scope.etapa.id){return true;}
            };

            /* Verifica Label de Etapa */
            $scope.selecionaEtapa = function (){
                var etapaSelecionada = '';
                for (var i = 0; i < $scope.etapas.length; i++) {
                    if ($scope.etapas[i].id === parseInt($scope.etapa.id)) {
                        etapaSelecionada = $scope.etapas[i];
                    }
                }
                if (etapaSelecionada.id){
                    $scope.etapa.id = etapaSelecionada.id;
                    $scope.buscarDisciplinas($scope.etapa.id);
                };
            };

            /* buscar curso */
            $scope.buscarCursos = function () {
                var promise = Servidor.buscar('cursos', null);
                promise.then(function (response) {
                    $scope.cursos = response.data;
                    if(DisciplinaService.recebeCurso) {
                        $scope.curso = {'id': $scope.EtapaService.etapa.curso.id };
                        $scope.selecionaCurso();
                    };
                    $timeout(function () {
                        $('#curso').material_select('destroy');
                        $('#curso').material_select();
                    }, 250);
                });
            };

            $scope.prepararVoltar = function(objeto) {
                if (objeto.nome && !objeto.id) {
                    $('#modal-certeza').openModal();
                } else {
                    $scope.fecharFormulario();
                }
            };

            /* Verifica Label de Curso */
            $scope.selecionaCurso = function () {
                var cursoSelecionado = '';
                for (var i = 0; i < $scope.cursos.length; i++){
                    if ($scope.cursos[i].id === parseInt($scope.curso.id)) {
                        cursoSelecionado = $scope.cursos[i];
                    }
                }
                if(cursoSelecionado.id){
                    $scope.curso.id = cursoSelecionado.id;
                    $scope.buscarEtapas($scope.curso.id);
                };
            };

            /* buscar Disciplina */
            $scope.buscarDisciplinas = function (id){
                $scope.disciplinas = [];
                var promise = Servidor.buscar('disciplinas',{'etapa':id});
                promise.then(function (response) {
                    //$scope.disciplinas = response.data;
                    for (var i = 0 ; i < response.data.length; i++) {
                        var promise = Servidor.buscarUm('disciplinas',response.data[i].id);
                        promise.then(function(responsed){
                            $scope.disciplinas.push (responsed.data);
                            if ($scope.disciplinas.length === response.data.length) {
                                $('.tooltipped').tooltip('remove');
                                $timeout(function() { $('.tooltipped').tooltip({delay: 50}); }, 250);
                            }
                        });
                    }
                });
            };

            /* Fecha o Formulário */
            $scope.fecharFormulario = function () {
                $scope.limparDisciplina();
                $scope.buscarDisciplinas($scope.etapa.id);
                $scope.editando = false;
                $timeout(function (){
                    Servidor.verificaLabels();
                }, 500);
            };

            /* Abrir modal para remover */
            $scope.prepararRemover = function (disciplina) {
                $scope.disciplinaRemover = disciplina;
                $('#remove-modal-disciplina').openModal();
            };

            /* Remover Disciplina */
            $scope.remover = function () {
                Servidor.remover($scope.disciplinaRemover, 'disciplina');
                $timeout(function(){
                    $scope.buscarDisciplinas($scope.etapa.id);
                },500);
            };

            /* Inicializando */
            $scope.inicializar = function (inicializaContador) {
                $('.tooltipped').tooltip('remove');
                $timeout(function (){
                    Servidor.removeTooltipp();
                    DisciplinaService.abrirFormulario = false;
                    $('#etapa').material_select();
                    if (inicializaContador){
                        $('.counter').each(function () {
                            $(this).characterCounter();
                        });
                    }
                    Servidor.verificaLabels();
                    if(!EtapaService.abrirFormulario){
                        $scope.buscarCursos();
                    };
                    $('.tooltipped').tooltip({delay: 50});
                    $('ul.tabs').tabs();
                    $('.modal-trigger-disciplina').leanModal();
                    //Mask Carga Horária
                    $('.tooltipped').tooltip({delay: 50});
                    $('.modal-trigger').leanModal({dismissible: true, complete: function () {
                            $('.lean-overlay').hide();
                        }});

                    /*Mascara de hora*/
                    $('.time').mask('00:00');
                    $("#cargaHoraria").keypress(function (e) {
                        var tecla = (window.event) ? event.keyCode : e.which;
                        if ((tecla > 47 && tecla < 58)) {
                            return true;
                        } else {
                            if (tecla === 8 || tecla === 0) {
                                return true;
                            }
                            else
                                return false;
                        }
                    });
                    
                    // Inicializando controles via Jquery Mobile
                    if ($(window).width() < 993) {
                        $(".swipeable").on("swiperight", function () {
                            $('.swipeable').removeClass('move-right');
                            $(this).addClass('move-right');
                        });
                        $(".swipeable").on("swipeleft", function () {
                            $('.swipeable').removeClass('move-right');
                        });
                    }
                    Servidor.entradaPagina();
                }, 700);
            };
            $scope.inicializar();
        }]);
})();
