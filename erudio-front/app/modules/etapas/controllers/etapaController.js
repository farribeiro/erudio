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
    var etapaModule = angular.module('etapaModule', ['servidorModule','etapaDirectives','disciplinaModule']);

    etapaModule.service('EtapaService', [function () {
            this.abrirFormulario = false;
            this.etapa = {'id':null, 'curso': {'id': null}};
            this.curso = {'id': null};
            this.abreForm = function () {this.abrirFormulario = true;};
            this.fechaForm = function () {this.abrirFormulario = false;};
        }]);

    /* Controller */
    etapaModule.controller('EtapaController', ['$scope', '$compile', '$timeout', 'Servidor', 'Restangular','CursoService', 'EtapaService', 'DisciplinaService','$templateCache' , '$compile', function($scope, $compile, $timeout, Servidor, Restangular, CursoService, EtapaService, DisciplinaService, $templateCache, $compile){
        $templateCache.removeAll();
        $scope.escrita = Servidor.verificaEscrita('ETAPA');

        /* Atributos Específicos */
        $scope.cursos = [];
        $scope.curso = null;
        $scope.etapas = [];
        $scope.sistemaAvaliacoes = [];
        $scope.modulos = [];
        $scope.modeloQuadroHorarios = [];

        /* Atributos de controle da página */
        $scope.editando = false;
        $scope.loader = false;
        $scope.existeEtapa = false;
        $scope.nomeCurso = '';
        $scope.acao = 'Adicionar';
        $scope.etapaService = EtapaService;
        $scope.editandoMobile = false;
        $scope.cortina = false;
        $scope.pagina = 0;
        $scope.primeiroScroll = true;
        $scope.index = null;
        $scope.habilitaClique = true;
        $scope.CursoService = CursoService;
        $scope.EtapaService = EtapaService;
        $scope.DisciplinaService = DisciplinaService;

        /* Recebe os dados de Curso */
        $scope.$watch("EtapaService", function (query){
            if (EtapaService.abrirFormulario){
                var curso = $scope.EtapaService.curso;
                if (!curso.id){
                    var promise = Servidor.finalizar(curso, 'cursos', 'Curso');
                    promise.then(function (response) {
                        $scope.curso = response.data.id;
                        $timeout(function() { $('#curso').material_select(); }, 500);                        
                        $scope.carregar(null, true);
                        $scope.EtapaService.curso = [];
                    });
                }
                else{
                    $scope.curso = curso.id;
                    $scope.pagina = 0;
                    $scope.buscarEtapas();
                }
                $timeout(function() { $('#curso').material_select(); }, 500);
                $scope.buscarModulos();
            }            
        });

        /* Estrutura de Etapa */
        $scope.etapa = { nome: null, nomeExibicao: null, ordem: null, modulo:{ id:null }, modeloQuadroHorario:{ id:null }, sistemaAvaliacao:{ id:null }, limiteAlunos: null, integral: true, curso: { id:null } };

        /* Vai para o módulo de disciplina */
        $scope.intraForms = function (etapa, novo){
            EtapaService.etapa = etapa;
            if(novo){
                DisciplinaService.recebeCurso = true;
                EtapaService.etapa = $scope.etapa;
            }
            DisciplinaService.abreForm();
        };

        /* Controle do spinner de progresso */
        $scope.mostraLoader = function (cortina) {
            $scope.loader = true;
            if (cortina){
                $scope.cortina = false;
                $('.blue-light').addClass('blue-light-active');
                $timeout(function(){$('.blue-light').removeClass('blue-light-active');}, 500);
            }else {$scope.cortina = true;}
        };

        $scope.fechaLoader = function () {$scope.loader = false; $scope.cortina = false;};

        /* Reinciando estrutura de etapa */
        $scope.reiniciar = function (){$scope.etapa = { nome: null,nomeExibicao: null, ordem: null, modulo:{ id:null }, modeloQuadroHorario:{ id:null }, sistemaAvaliacao:{ id:null }, limiteAlunos: null, integral: true, curso: { id:null } };};

        /* Verifica se o usuário deseja descartar os dados preenchidos no formulario */
        $scope.prepararVoltar = function(objeto) {
            if (objeto.nome && !objeto.id) {
                $('#modal-certeza').openModal();
            } else {
                $scope.fecharFormulario();
            }
        };

        /* Buscando regimes - Form */
        $scope.buscarRegimes = function() {
            var promise = Servidor.buscar('regimes',null);
            promise.then(function (response){ $scope.regimes = response.data; });
        };

        /* Buscando Sistema de Avaliaçao - Form */
        $scope.buscarSistemasAvaliacao = function() {
            var promise = Servidor.buscar('sistemas-avaliacao',null);
            promise.then(function (response){ $scope.sistemaAvaliacoes = response.data; });
        };

        /* Guarda a etapa para futura remoção e abre o modal de confirmação */
        $scope.prepararRemover = function (etapa, index){
            var promise = Servidor.buscar('turmas', {'etapa': etapa.id, 'encerrado': false});
            promise.then(function(response) {
                if (response.data) {
                    $('.remove-content').html('Há turmas ativas em <strong>' + etapa.nomeExibicao + '</strong>, você realmente deseja remover esta etapa?' );
                } else {
                    $('.remove-content').text('Você realmente deseja remover esta etapa?');
                }
                $scope.etapaRemover = etapa; $scope.index = index; $('#remove-modal-etapa').openModal();
            });
        };

        /* Validando Formulário */
        $scope.validar = function (id) { return Servidor.validar(id); };

        /* Buscando modulos - Form */
        $scope.buscarModulos = function() {
            var promise = Servidor.buscar('modulos',{ 'curso': $scope.curso });
            promise.then(function (response){
                if (response.data.length) {
                    $scope.modulos = response.data;
                } else {
                    $scope.modulos = [];
                    Materialize.toast('Este curso nao possui modulos.', 2500);
                }
                $timeout(function() {
                    $('#modulo').material_select();
                }, 250);
            });
        };

        /* Buscando modelos horários - Form */
        $scope.buscarModelosHorarios = function() {
            var promise = Servidor.buscar('modelo-quadro-horarios',{ 'curso':$scope.curso });
            promise.then(function (response){ $scope.modeloQuadroHorarios = response.data; });
        };

        /* Remove a etapa */
        $scope.remover = function(){
            $scope.mostraLoader(false);
            Servidor.remover($scope.etapaRemover, 'Etapa');
            $scope.etapas.splice($scope.index,1);
            $timeout(function (){
                $scope.buscarEtapas(false);
                $scope.fechaLoader();
            }, 1000);
        };

        /* Inicializando */
        $scope.inicializar = function (inicializaUmaVez) {
            $timeout(function () {
                Servidor.removeTooltipp();
                EtapaService.abrirFormulario = false;
                if (inicializaUmaVez) {
                    $('.counter').each(function(){  $(this).characterCounter(); });
                    /*$(window).scroll(function() {
                        if($(this).scrollTop() + $(this).height() === $(document).height()) {
                            if (!$scope.editando) {
                                $scope.pagina++;
                                $scope.buscarEtapas(true);
                            }
                        }
                    });*/
                    $('#etapaForm').keydown(function(event){
                        if ($scope.editando) {
                            var keyCode = (event.keyCode ? event.keyCode : event.which);
                            if (keyCode === 13) { //if enter is pressed
                                $timeout(function(){
                                    if ($scope.habilitaClique) { $('#salvarEtapa').trigger('click'); }
                                    else { $scope.habilitaClique = true; }
                                }, 300);
                            }
                        }
                    });
                    Servidor.entradaPagina();
                }
            },700);
        };

        $scope.carregarModulo = function() {
            var promise = Servidor.buscarUm('cursos', $scope.etapa.curso.id);
            promise.then(function(response) {
                $scope.modulo = { 'curso': response.data, 'nome': '' };
                $('#form-modal-modulo').openModal();
                $timeout(function() { Servidor.verificaLabels(); }, 150);

            });
        };

        $scope.salvarModulo = function() {
            if ($scope.modulo.nome) {
                var promise = Servidor.finalizar($scope.modulo, 'modulos', 'Modulo');
                promise.then(function(response) {
                    $('#form-modal-modulo').closeModal();
                    $scope.modulos.push(response.data);
                    $timeout(function() { $('#modulo').material_select(); }, 150);
                    $scope.etapa.modulo = response.data;
                });
            }
        };

        /* Buscando etapa - Lista */
        $scope.buscarEtapas = function(finalPagina){
            if ($scope.curso) {
                $scope.mostraLoader(finalPagina);
                $scope.etapa.curso.id = $scope.curso;
                var promise = null;
                if(!finalPagina){
                    $scope.pagina = 0;
                    $scope.etapas = [];
                }
                if ($scope.curso !== null){
                    promise = Servidor.buscar('etapas',{'curso':$scope.curso, 'page': $scope.pagina});
                } else {
                    promise = Servidor.buscar('etapas',{'page': $scope.pagina});
                }
                promise.then(function (response){
                    if (response.data.length > 0) {
                        var etapas = response.data;
                        if ($scope.pagina === 0) {
                            $scope.etapas = etapas;
                            $('.tooltipped').tooltip('remove');
                            $timeout(function(){ $('.tooltipped').tooltip({delay: 50}); });
                        } else {
                            for (var i=0; i < etapas.length; i++){ $scope.etapas.push(etapas[i]); }
                        }
                        $scope.primeiroScroll = true;
                    } else{
                        if ($scope.primeiroScroll) {
                            Materialize.toast('Nenhuma etapa foi carregada agora.', 1000);
                            $scope.primeiroScroll = false;
                        }
                        $scope.pagina--; $scope.fechaLoader(finalPagina);
                    }
                    $timeout(function (){ $scope.fechaLoader(finalPagina); }, 1000);
                });
            }
        };

        /* Preparando carregamento da etapa */
        $scope.carregar = function (etapa, nova, mobile, index){
            if (!mobile) {
                $scope.mostraLoader(false);
                Servidor.animacaoEntradaForm(false);
                $scope.reiniciar();
                if (!nova) {
                    $scope.acao = "Editar";
                    var promise = Servidor.buscarUm('etapas',etapa.id);
                    promise.then(function (response) { $scope.etapa = response.data; });
                }
                $timeout(function (){ Servidor.verificaLabels(); $timeout(function(){$('#nomeEtapa').focus();},150);},500);
                $timeout(function(){
                    $scope.etapa.curso.id = $scope.curso;
                    if (!nova) { $('.opcoesEtapa' + etapa.id).hide(); $scope.index = index; }
                    $scope.fechaLoader();
                    $scope.editando = true;
                    $('#modulo, #sistemaAvaliacao, #modeloQuadroHorario').material_select('destroy'); $('#modulo, #sistemaAvaliacao, #modeloQuadroHorario').material_select();
                    $('.select-wrapper').click(function(){
                        var element = $(this).find('ul');
                        if(element.hasClass('active')){ $scope.habilitaClique = false; } else { $scope.habilitaClique = true; }
                    });
                    Servidor.inputNumero();
                }, 500);
            } else{
                $timeout(function (){ Servidor.verificaLabels(); $timeout(function(){$('#nomeEtapa').focus();},200);},500);
                if(!nova){
                    $scope.editandoMobile = true;
                    $('.opcoesEtapa' + etapa.id).show();
                }else {
                    $scope.editandoMobile = true;
                    $scope.carregar(null,true,false,null);
                }
            }
        };

        /* Carregar informação da instituicao */
        $scope.carregarInfo = function (etapa) {
            $scope.mostraLoader(false);
            var promise = Servidor.buscarUm('etapas',etapa.id);
            promise.then(function (response) { $scope.etapa = response.data; $('#info-modal-etapa').openModal(); });
            $timeout(function(){
                $('.opcoesEtapa' + etapa.id).hide();
                $scope.fechaLoader();
            }, 300);
        };

        /* Fecha o formulario de cadastro/edição */
        $scope.fecharFormulario = function () {
            $('.tooltipped').tooltip('remove');
            $timeout(function(){
                $('.tooltipped').tooltip({delay: 50});
            }, 500);
            $scope.buscarEtapas(false);
            $scope.reiniciar();
            Servidor.animacaoEntradaLista(false);
            $timeout(function (){ $scope.editando = false; },300);
            $scope.acao = 'Adicionar';
            Servidor.resetarValidador('validateEtapa');
        };

        /* Salvando etapa */
        $scope.finalizar = function (novaEtapa) {
            if (novaEtapa) {
                if ($scope.validar('validateEtapa') === true) {
                    $scope.mostraLoader(false);
                    $scope.etapa.sistemaAvaliacao = {id: $scope.etapa.sistemaAvaliacao.id };
                    var result = Servidor.finalizar($scope.etapa, 'etapas', 'Etapa');
                    result.then(function (response) {
                        $scope.fecharFormulario();
                        $timeout(function () {
                            $scope.buscarEtapas(false);
                            $scope.fechaLoader();
                        }, 1000);
                    });
                } else {
                    Servidor.customToast('Esta etapa não possui módulo.');
                }
            } else {
                if ($scope.validar('validateEtapa') === true) {
                    $scope.mostraLoader(false);
                    var result = Servidor.finalizar($scope.etapa, 'etapas', '');
                    result.then(function (response) {
                        $scope.etapa = response.data;
                        $scope.fecharFormulario();
                    });
                    $timeout(function () {
                        $scope.fechaLoader();
                    }, 1000);
                };
            }
        };

        /* Reseta a estrutura de etapa */
        $scope.limparFormulario = function(cursoId){
            $scope.etapa = { nome: null, nomeExibicao: null, ordem: null, modulo:{ id:null }, modeloQuadroHorario:{ id:null }, sistemaAvaliacao:{ id:null }, limiteAlunos: null, integral: true, curso: { id:cursoId } };
            $timeout(function(){
                $('#modulo, #sistemaAvaliacao, #modeloQuadroHorario').material_select('destroy');
                $('#modulo, #sistemaAvaliacao, #modeloQuadroHorario').material_select();
            }, 500);
        };

        /* Verifica selects de Modulo */
        $scope.verificaSelectModulo = function (id) {
            if (id === $scope.etapa.modulo.id) { return 'selected'; }
        };

        $scope.verificaSelectCurso = function (id) {
            if (id === $scope.curso) { return 'selected'; }
        };

        /* Verifica selects de Sistema de Avaliação */
        $scope.verificaSelectSistemaAvaliacao = function (id) {
            if (id === $scope.etapa.sistemaAvaliacao.id) {  $('#sistemaAvaliacao').material_select('destroy'); $('#sistemaAvaliacao').material_select(); return 'selected'; }
        };

        /* Verifica selects de Sistema de Avaliação */
        $scope.verificaSelectModeloQuadroHorario = function (id) {
            if (id === $scope.etapa.modeloQuadroHorario.id) { $('#modeloQuadroHorario').material_select('destroy'); $('#modeloQuadroHorario').material_select(); return 'selected'; }
        };

        /* Buscando cursos - Select */
        $scope.buscarCursos = function() {
            var promise = Servidor.buscar('cursos', null);
            promise.then(function (response){
                if (response.data.length > 0) {
                    $scope.cursos = response.data;
                    $('#curso').material_select('destroy'); $('#curso').material_select();
                    $timeout(function (){ $('#curso').material_select('destroy'); $('#curso').material_select(); },200);
                } else {
                    Materialize.toast('Nenhuma etapa cadastrada neste curso.', 1000);
                }
            });
        };

        /* Chamando busca de Etapa. */
        $scope.selecionaCurso = function(){
            $scope.etapas = [];
            $scope.buscarEtapas(false);
            $scope.buscarModulos();
            $scope.buscarSistemasAvaliacao();
            $scope.buscarModelosHorarios();
        };

        $scope.buscarModelosHorarios();
        $scope.buscarSistemasAvaliacao();
        $scope.buscarCursos();
        $scope.inicializar(true);
    }]);
})();
