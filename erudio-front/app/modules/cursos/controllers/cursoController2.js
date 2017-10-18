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
    var cursoModule = angular.module('cursoModule', ['cursoDirectives', 'etapaDirectives', 'servidorModule']);

    cursoModule.service('CursoService', [function () {
        this.abrirFormulario = false;
        this.curso = {};
        this.abreForm = function () {
            this.abrirFormulario = true;
        };
    }]);

    cursoModule.controller('CursoController', ['$scope', '$timeout', 'Servidor', 'Restangular', 'CursoService', 'EtapaService', '$templateCache', function ($scope, $timeout, Servidor, Restangular, CursoService, EtapaService, $templateCache) {

            $scope.escrita = Servidor.verificaEscrita('CURSO') || Servidor.verificaAdmin();                        
            
            /* Atributos Específicos */
            $scope.cursos = [];
            $scope.modalidades = [];
            $scope.modalidadeId = null;
            $scope.nomeCurso = '';
            $scope.icone = 'search';

            /* Atributos de controle da página */
            $scope.editando = false;
            $scope.progresso = false;
            $scope.loader = false;
            $scope.acao = 'Adicionar';
            $scope.editandoMobile = false;
            $scope.cortina = false;
            $scope.pagina = 0;
            $scope.primeiroScroll = true;
            $scope.index = null;
            $scope.habilitaClique = true;
            $scope.CursoService = CursoService;
            $scope.EtapaService = EtapaService;
            $scope.statusBotao = true;

            /* Controle da barra de progresso e loader */
            $scope.mostraProgresso = function () {
                $scope.progresso = true;
                $scope.cortina = true;
            };
            $scope.fechaProgresso = function () {
                $scope.progresso = false;
                $scope.cortina = false;
            };
            $scope.mostraLoader = function (cortina) {
                $scope.loader = true;
                if (cortina) {
                    $scope.cortina = true;
                }
            };
            $scope.fechaLoader = function () {
                $scope.loader = false;
                $scope.cortina = false;
            };

            /* Estrutura de Curso */
            $scope.curso = {nome: null, modalidade: {id: 0}};

            /* Reinciando estrutura de curso*/
            $scope.reiniciar = function () {
                $scope.curso = {nome: null, modalidade: {id: 0}};
            };

            /* Guarda o curso para futura remoção e abre o modal de confirmação */
            $scope.prepararRemover = function (curso, index) {
                $('#remove-modal-curso').openModal();
                $scope.cursoRemover = curso;
                $scope.index = index;
            };

            /* Resetar Busca */
            $scope.resetaBusca = function () {
                $scope.nomeCurso = '';
            };

            /* Validando Formulário */
            $scope.validar = function (id) {
                return Servidor.validar(id);
            };

            /* Limpar busca */
            $scope.limparBusca = function () {
                $scope.nomeCurso = '';
            };

            /* Verifica o select de modalidade */
            $scope.verificaSelectModalidade = function (id) {
                if (id === $scope.curso.modalidade.id) {
                    return 'selected';
                }
            };

            /* Verifica se o campo de busca foi preenchido  */
            $scope.$watch("nomeCurso", function (query) {
                $scope.buscaCurso(query);
                if (!query) {
                    $scope.icone = 'search';
                } else {
                    $scope.icone = 'clear';
                }
            });

            /* Verfica se e novo ou editavel */
            /*$scope.verificaAcao = function (curso, statusSalvar) {
                $scope.statusSalvar = statusSalvar;
                $scope.abrirFormulario = true;
                if (!curso) {
                    curso = {nome: null, modalidade: {id: null}};
                    $scope.statusBotao = false;
                }
                else {
                    $scope.editando = true;
                }
                $scope.carregar(curso, !statusSalvar);
            };
*/
            /* Salvar Curso */
            $scope.salvarCurso = function () {
                var promise = Servidor.buscar('cursos', {nome: $scope.curso.nome});
                promise.then(function (response) {
                    if (response.data.length) {
                        Servidor.customToast('Já existe um curso com este nome!.');
                        $scope.fechaProgresso();
                    }
                    else {
                        var result = Servidor.finalizar($scope.curso, 'cursos', 'Cursos');
                        result.then(function () {
                            $scope.fecharFormulario();
                            $scope.buscarCursos();
                        });
                    }
                });
            };
            
            /* Vai para o módulo de etapas */
            $scope.intraForms = function (curso) {
                EtapaService.abreForm();
                EtapaService.curso = curso;
            };

            /* Volta para a pagina de busca */
            $scope.fecharFormulario = function () {
                Servidor.animacaoEntradaLista(false);
                $('.title-card').css('margin-top','-64px');
                $timeout(function (){ $scope.editando = false; },300);
                $scope.reiniciar();
                $scope.buscarCursos();
            };

            /* Inicializando */
            $scope.inicializar = function (inicializaUmaVez) {
                $timeout(function(){
                    $('.modalidadeCurso').material_select('destroy');
                    $('.modalidadeCurso').material_select();
                    if (inicializaUmaVez) {
                        $('#cursoForm').keydown(function (event) {
                            if ($scope.editando) {
                                var keyCode = (event.keyCode ? event.keyCode : event.which);
                                if (keyCode === 13) { //if enter is pressed
                                    $timeout(function () {
                                        if ($scope.habilitaClique) {
                                            $('#salvarEhabilitaCliquetapa').trigger('click');
                                        }
                                        else {
                                            $scope.habilitaClique = true;
                                        }
                                    }, 300);
                                }
                            }
                        });
                    }
                    Servidor.entradaPagina();
                    $('.tooltipped').tooltip('remove');
                    $timeout(function(){
                        $('.tooltipped').tooltip({delay: 50});    
                    },100);
                }, 700);
            };

            /* Buscando cursos - Lista */
            $scope.buscarCursos = function (finalPagina) {
                var promise = Servidor.buscar('cursos', {page: $scope.pagina});
                promise.then(function (response) {
                    if (response.data.length > 0) {
                        var cursos = response.data;
                        if ($scope.pagina === 0) {
                            $scope.cursos = cursos;
                            $('.tooltipped').tooltip('remove');
                            $timeout(function() { $('.tooltipped').tooltip({delay: 50}); }, 250);
                        } else {
                            for (var i = 0; i < cursos.length; i++) {
                                $scope.cursos.push(cursos[i]);
                            }
                        }
                        $scope.primeiroScroll = true;
                        if ($('#search').is(':disabled')) {
                            $('#search').attr('disabled', '');
                            $('#search').css('background', '');
                            $('#search').attr('placeholder', 'Digite aqui para buscar');
                        }
                        if (!finalPagina) {
                            $timeout(function () {
                                //$('.modal-trigger').leanModal({dismissible: true, in_duration: 100, out_duration: 100});
                                $scope.fechaProgresso();
                            }, 1000);
                        } else {
                            $timeout(function () {
                                //$('.modal-trigger').leanModal({dismissible: true, in_duration: 100, out_duration: 100});
                                $scope.fechaLoader();
                            }, 1000);
                        }
                    } else {
                        if ($scope.cursos.length === 0) {
                            $('#search').attr('disabled', 'disabled');
                            $('#search').css('background', '#ccc');
                            $('#search').attr('placeholder', '');
                        }
                        if ($scope.primeiroScroll) {
                            Materialize.toast('Nenhum curso foi carregado agora.', 1000);
                            $scope.primeiroScroll = false;
                        }
                        $scope.pagina--;
                        $scope.fechaLoader();
                        if (!finalPagina) {
                            $scope.fechaProgresso();
                        } else {
                            $scope.fechaLoader();
                        }
                    }
                });
            };

            /* Buscam as modalidades de ensino */
            $scope.buscarModalidades = function () {
                $scope.mostraProgresso();
                var promise = Servidor.buscar('modalidades-ensino', null);
                promise.then(function (response) {
                    $scope.modalidades = response.data;
                    $timeout(function () {
                        $scope.fechaProgresso();
                    }, 1000);
                });
            };

            /* Preparando carregamento do curso */
            $scope.carregar = function (curso, nova, mobile, index) {
                if (!mobile) {
                    $scope.mostraLoader(true);
                    Servidor.animacaoEntradaForm(false);
                    $('.title-card').css('margin-top','');
                    $scope.reiniciar();
                    if (!nova) {
                        $scope.acao = "Editar";
                        $scope.curso = curso;
                        $scope.statusBotao = false;
                        $('.opcoesCurso' + curso.id).hide();
                        $scope.index = index;
                    } else {
                        $scope.acao = 'Adicionar';
                        $scope.curso = {nome: null, modalidade: {id: null}};
                        $scope.statusBotao = true;
                    }
                    $timeout(function () {
                        $scope.fechaLoader();
                        Servidor.verificaLabels();
                        $scope.editando = true;
                        $timeout(function() { $('#nomeCursoFocus').focus(); }, 150);
                        $('.modalidadeCurso').material_select('destroy');
                        $('.modalidadeCurso').material_select();
                    }, 100);
                } else {
                    if (!nova) {
                        $scope.editandoMobile = true;
                        $scope.statusBotao = false;
                        $('.opcoesCurso' + curso.id).show();
                    } else {
                        $scope.editandoMobile = true;
                        $scope.carregar(null, true, false, null);
                        $scope.statusBotao = true;
                    }
                }
            };

            /* Verifica se o usuário deseja descartar os dados preenchidos */
            $scope.prepararVoltar = function (objeto) {
                if (objeto.nome && !objeto.id) {
                    $('#modal-certeza').openModal();
                } else {
                    $scope.fecharFormulario();
                }
            };

            /* Remove o curso */
            $scope.remover = function () {
                $scope.mostraProgresso();
                Servidor.remover($scope.cursoRemover, 'Curso');
                $('.lean-overlay').hide();
                $scope.cursos.splice($scope.index, 1);
                $timeout(function () {
                    $scope.fechaProgresso();
                }, 1000);
                $scope.buscaCurso();
            };

            /* Função de Busca */
            $scope.buscaCurso = function (query) {
                $timeout.cancel($scope.delayBusca);
                $scope.delayBusca = $timeout(function () {
                    if (query === undefined) {
                        query = '';
                    }
                    var tamanho = query.length;
                    if (tamanho > 3) {
                        var promise = Servidor.buscar('cursos', {'nome': query});
                        promise.then(function (response) {
                            $scope.cursos = response.data;
                            $timeout(function () {
                                $scope.inicializar(false);
                            }, 1000);
                        });
                    } else {
                        if (tamanho === 0) {
                            $scope.inicializar(false);
                            $scope.buscarCursos();
                        }
                    }
                }, 1000);
            };

            /* Salvando Curso */
            $scope.finalizar = function (nome) {
                if ($scope.curso.nome && $scope.curso.modalidade.id) {
                    $scope.mostraProgresso();
                    for (var i = 0; i < $scope.cursos.length; i++) {
                        if ($scope.cursos[i].nome.toUpperCase() === nome.toUpperCase() && $scope.curso.id !== $scope.cursos[i].id) {
                            Servidor.customToast("Ja existe um curso com este nome!");
                            $scope.fechaLoader();
                            return true;
                        }
                        if (i === $scope.cursos.length - 1) {
                            if (!$scope.curso.id) {
                                $scope.pagina = 0;
                            }
                            var promise = Servidor.finalizar($scope.curso, 'cursos', 'Curso');
                            promise.then(function (response) {
                                $scope.cursos.forEach(function (curso) {
                                    if (curso.id === response.data.id) {
                                        curso = response.data;
                                        $scope.fecharFormulario();
                                        $scope.fechaProgresso();
                                        return true;
                                    }
                                });
                            });

                        }
                    }
                }
            };

            $scope.verificaSelectModalidade = function (modalidade) {
                if (modalidade === $scope.curso.modalidade.id) {
                    return true;
                }
            };

            $scope.selecionaModalidade = function () {
                $scope.curso.modalidade.id = $scope.modalidadeId;
            };

            /* Inicializando Cursos */
            $scope.inicializar(true);
            $scope.buscarCursos(false);
            $scope.buscarModalidades();
        }]);
})();
