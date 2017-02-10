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
    var habilidadeModule = angular.module('habilidadeModule', ['servidorModule', 'habilidadeDirectives']);
    habilidadeModule.controller('habilidadeController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', '$compile', function ($scope, Servidor, Restangular, $timeout, $templateCache, $compile) {

            $scope.escrita = Servidor.verificaEscrita('HABILIDADE');

            $scope.cursos = [];
            $scope.unidades = [];
            $scope.etapas = [];
            $scope.disciplinas = [];
            $scope.habilidades = [];
            $scope.sistemas = [];
            $scope.qtdPaginas = [];

            /* Controle da barra de progresso e loader */
            $scope.mostraProgresso = function () {
                $scope.progresso = true;
            };
            $scope.fechaProgresso = function () {
                $scope.progresso = false;
            };
            $scope.mostraLoader = function () {
                $scope.loader = true;
            };
            $scope.fechaLoader = function () {
                $scope.loader = false;
            };

            $scope.curso = {
                'id': null
            };
            $scope.etapa = {
                'id': null
            };
            $scope.disciplina = {
                'id': null
            };
            $scope.unidade = {
                'id': null
            };
            $scope.habilidade = {
                'nome': '',
                'disciplina': {},
                'media': null,
                'sistema_avaliacao': ''
            };
            $scope.sistema = {
                'nome': '',
                'nomeIdentificacao': '',
                'tipo': null,
                'quantidadeMedias': null,
                'exame': null
            };
            $scope.habilidadeDisciplinas = {
                'curso': null,
                'etapa': null,
                'media': '',
                'disciplina': null
            };

            $scope.editando = false;
            $scope.acao = '';

            $scope.limpaHabilidade = function () {
                $scope.habilidade = {
                    'nome': '',
                    'disciplina': {},
                    'media': null,
                    'sistema_avaliacao': ''
                };
            };

            $scope.limpaSistema = function () {
                $scope.sistema = {
                    'nome': '',
                    'nomeIdentificacao': '',
                    'tipo': null,
                    'quantidadeMedias': null,
                    'exame': null
                };
            };

            /* Monta o select */
            $scope.select = function (id) {
                $timeout(function () {
                    $('#' + id).material_select('destroy');
                    $('#' + id).material_select();
                }, 50);
            };

            /* Faz a busca de todos os cursos */
            $scope.buscarCursos = function () {
                var promise = Servidor.buscar('cursos', null);
                promise.then(function (response) {
                    $scope.cursos = response.data;
                    $scope.select('cursoBusca, #curso');
                });
            };

            /* Busca as etapas de um determinado curso */
            $scope.buscarEtapas = function (id) {
                $scope.mostraLoader();
                var promise = Servidor.buscar('etapas', {'curso': id, 'unidade': sessionStorage.getItem('unidade')});
                promise.then(function (response) {
                    $scope.etapa.id = null;
                    $scope.habilidade.disciplina.id = null;
                    $scope.etapas = [];
                    if (!response.data.length) {
                        Servidor.customToast('Nenhuma etapa cadastrada');
                        if ($scope.editando) {
                            $scope.select('etapa, #disciplina');
                        } else {
                            $scope.select('etapaBusca, #disciplinaBusca');
                        }
                        $scope.fechaLoader();
                        
                    } else {
                        $scope.requisicoes = 0;
                        response.data.forEach(function(etapa) {
                            $scope.requisicoes++;
                            var promise = Servidor.buscarUm('etapas', etapa.id);
                            promise.then(function(response) {
                                if (response.data.sistemaAvaliacao.tipo === 'QUALITATIVO') {
                                    $scope.etapas.push(etapa);
                                }
                                if (--$scope.requisicoes === 0) {
                                    if ($scope.editando) {
                                        $scope.select('etapa, #disciplina');
                                    } else {
                                        $scope.select('etapaBusca, #disciplinaBusca');
                                    }
                                    $scope.fechaLoader();
                                }
                            });
                        });                        
                        $scope.disciplinas = [];
                    }                                        
                });
            };

            /* Busca as disciplinas de uma determinada etapa */
            $scope.buscarDisciplinas = function (id) {
                $scope.mostraLoader();
                $('#disciplina').attr('disabled', true);
                $scope.disciplinas = [];
                var promise = Servidor.buscar('disciplinas', {'etapa': id});
                promise.then(function (response) {
                    $scope.disciplinas = response.data;
                    if (!$scope.disciplinas.length) {
                        Servidor.customToast('Nenhuma disciplina cadastrada');
                    } else {
                        $('#disciplina').removeAttr('disabled', false);
                    }
                    $scope.select('disciplina');
                    $scope.select('disciplinaBusca');
                });
                $timeout(function () {
                    $scope.fechaLoader();
                }, 300);
            };

            /* Busca todas as unidades */
            $scope.buscarUnidades = function () {
                var promise = Servidor.buscar('unidades-ensino', null);
                promise.then(function (response) {
                    $scope.unidades = response.data;
                });
            };

            /* Busca os sistemas do tipo qualitativo */
            $scope.buscarSistemas = function () {
                var promise = Servidor.buscar('sistemas-avaliacao', {'tipo': 'QUALITATIVO'});
                promise.then(function (response) {
                    $scope.sistemas = response.data;
                });
            };

            /* Altera a paginação */
            $scope.alterarPagina = function (pagina) {
                for (var i = 0; i <= $scope.qtdPaginas; i++) {
                    $(".paginasLista" + parseInt(i)).removeClass('active');
                    if (pagina === i) {
                        $(".paginasLista" + parseInt(i)).addClass('active');
                    }
                }
            };

            /* Realiza a busca de habilidades */
            $scope.buscarHabilidades = function (pagina, origem) {
                if ($scope.curso.id === null || $scope.etapa.id === null || $scope.habilidade.disciplina.id === null) {
                    Servidor.customToast('Escolha um curso, uma etapa e uma disciplina.');
                } else {
                    if (pagina !== $scope.paginaAtual) {
                        if (origem === 'botao') {
                            $scope.mostraLoader();
                            $scope.habilidades = [];
                        }
                        if (!pagina) {
                            $scope.paginaAtual = 0;
                            $(".paginasLista0").addClass('active');
                        } else {
                            $scope.paginaAtual = pagina;
                        }
                        if (origem === 'botao' && $scope.qtdPaginas) {
                            for (var i = 1; i <= $scope.qtdPaginas; i++) {
                                $(".paginasLista" + parseInt(i)).remove();
                            }
                        }
                        var promise = Servidor.buscar('avaliacoes-qualitativas/habilidades', {
                            'page': pagina,
                            'curso': $scope.curso.id,
                            'etapa': $scope.etapa.id,
                            'media': $scope.habilidade.media,
                            'disciplina': $scope.habilidade.disciplina.id
                        });
                        promise.then(function (response) {
                            if (response.data.length === 0) {
                                Materialize.toast('Nenhuma habilidade encontrada.', 1000);
                                $scope.habilidade.nome = '1 - ';
                                $timeout(function () {
                                    Servidor.verificaLabels();
                                    $scope.fechaLoader();
                                }, 100);
                            } else {
                                if (origem === 'botao') {
                                    //$scope.qtdPaginas = 2;
                                    $scope.qtdPaginas = Math.ceil(response.data.length / 50);
                                    for (var i = 1; i < $scope.qtdPaginas; i++) {
                                        var a = '<li class="waves-effect paginasLista' + i + '" data-ng-click="alterarPagina(' + parseInt(i) + '); buscarHabilidades(' + parseInt(i) + ');"><a href="#!">' + parseInt(i + 1) + '</a></li>';
                                        $(".paginasLista" + parseInt(i - 1)).after($compile(a)($scope));
                                    }
                                }
                                $scope.habilidades = response.data;
                                $('.tooltipped').tooltip('remove');
                                $timeout(function () {
                                       window.scrollTo(0, 600);
                                    $('.tooltipped').tooltip({delay: 50});
                                }, 250);
                                if ($scope.editando) {
                                    $timeout(function () {
                                        $scope.numeracaoHabilidade();
                                    }, 50);
                                }
                                $scope.fechaLoader();
                            }
                            $('#btn-adicionar-habilidade').show();
                        });
                    }
                }
            };

            /* Busca um sistema de avaliação */
            $scope.buscarUmSistema = function (id) {
                var promise = Servidor.buscarUm('sistemas-avaliacao', id);
                promise.then(function (response) {
                    $scope.sistema = response.data;
                    $scope.habilidade.media = '';
                });
            };

            /* Busca uma habilidade */
            $scope.buscarUmaHabilidade = function (id) {
                var promise = Servidor.buscarUm('avaliacoes-qualitativas/habilidades', id);
                promise.then(function (response) {
                    $scope.habilidade = response.data;
                    $timeout(function () {
                        Servidor.verificaLabels();
                    }, 150);
                });
            };

            $scope.selecionaDisciplina = function () {
                var disciplina = null;
                for (var i = 0; i < $scope.disciplinas.length; i++) {
                    if ($scope.disciplinas[i].id === parseInt($scope.habilidade.disciplina.id)) {
                        disciplina = $scope.disciplinas[i];
                    }
                }
                if (disciplina) {
                    $scope.habilidade.disciplina = disciplina;
                }
            };

            $scope.selecionaMedia = function (media) {
                $scope.habilidade.media = parseInt(media);
                $scope.buscarHabilidades('', 'botao');
            };

            $scope.limparBusca = function () {
                $scope.limpaHabilidade();
                $scope.curso.id = null;
                $scope.etapa.id = null;
                $timeout(function () {
                    $('#cursoBusca, #etapaBusca, #disciplinaBusca').material_select('destroy');
                    $('#cursoBusca, #etapaBusca, #disciplinaBusca').material_select();
                }, 100);
            };

            /* Abre o formulário para cadastro/edição de habilidades */
            $scope.carregar = function (habilidade) {
                $scope.mostraLoader();
                $scope.habilidades = [];

                if (habilidade) {
                    $scope.buscarUmaHabilidade(habilidade.id);
                    $scope.acao = 'Editar';
                    $('#cursoEdicao, #mediaEdicao, #etapaEdicao, #disciplinaEdicao').prop('disabled', true);
                } else {
                    $scope.acao = 'Cadastrar';
                    $scope.limpaHabilidade();
                    $scope.etapas = [];
                    $scope.disciplinas = [];
                    $scope.unidade.id = null;
                    $scope.curso.id = null;
                    $scope.etapa.id = null;
                }
                $timeout(function () {
                    $('#curso, #etapa, #disciplina, #sistemaAvaliacao').material_select('destroy');
                    $('#curso, #etapa, #disciplina, #sistemaAvaliacao').material_select();
                    $scope.editando = true;
                    $scope.fechaLoader();
                }, 150);
            };

            $scope.fecharFormulario = function () {
                $scope.mostraLoader();
                $scope.editando = false;
//                $scope.habilidades = [];
//                $scope.etapas = [];
//                $scope.disciplinas = [];
//                $scope.curso.id = null;
                $scope.select('cursoBusca, #etapaBusca, #disciplinaBusca');
//                $scope.limpaSistema();
//                $scope.limpaHabilidade();
                $timeout(function () {
                    $scope.fechaLoader();
                }, 300);
            };

            /* Adiciona uma habilidade */
            $scope.adicionarHabilidade = function () {
                if ($scope.habilidade.disciplina.id && $scope.habilidade.media && $scope.sistema.id && $scope.habilidade.nome) {
                    var habilidade = {
                        'nome': $scope.habilidade.nome,
                        'media': $scope.habilidade.media,
                        'sistema_avaliacao': $scope.sistema,
                        'disciplina': {'id': $scope.habilidade.disciplina.id}
                    };
                    if ($scope.habilidades.length) {
                        for (var i = 0; i < $scope.habilidades.length; i++) {
                            if (habilidade.nome === $scope.habilidades[i].nome)
                            {
                                Servidor.customToast("Já existe esta habilidade!");
                                break;
                            } else if (i === $scope.habilidades.length - 1) {
                                $scope.salvarHabilidade(habilidade);
                            }
                        }
                    } else {
                        $scope.salvarHabilidade(habilidade);
                    }
                } else {
                    Materialize.toast('Preencha os campos obrigatórios para adicionar uma habilidade.', 5000);
                }
            };

            $scope.salvarHabilidade = function (habilidade) {
                var promise = Servidor.finalizar(habilidade, 'avaliacoes-qualitativas/habilidades', 'Habilidade');
                promise.then(function (response) {
                    $scope.habilidades.push(response.data);
                    $scope.numeracaoHabilidade();
                });
            };

            $scope.numeracaoHabilidade = function () {
                if ($scope.habilidades.length) {
                    for (var i = 1; i < $scope.habilidades.length + 2; i++) {
                        $scope.habilidade.nome = '';
                        $scope.habilidades.forEach(function (habilidade) {
                            var nome = parseInt(habilidade.nome.replace(/\D/g, ''));
                            if (nome === i) {
                                $scope.habilidade.nome = 'existe';
                            }
                        });
                        if (!$scope.habilidade.nome) {
                            $scope.habilidade.nome = i + ' - ';
                            $timeout(function () {
                                Servidor.verificaLabels();
                            }, 100);
                            return true;
                        }
                    }
                    $scope.habilidade.nome = '';
                } else {
                    $scope.habilidade.nome = '1 - ';
                    $timeout(function () {
                        Servidor.verificaLabels();
                    }, 100);
                }
            };

            /* Verifica se o usuário deseja descartar os dados preenchidos */
            $scope.prepararRemover = function (habilidade) {
                $scope.habilidadeRemover = habilidade;
                $('#remove-modal-habilidade').openModal();
            };

//            $scope.prepararVoltar = function(objeto) {
//                if (objeto.nome || objeto.media && !objeto.id ) {                
//                    $('#modal-certeza').openModal();
//                } else {
//                    $scope.fecharFormulario();
//                }
//            };

            /* Altera uma habilidade */
            $scope.finalizar = function (habilidade) {
                var promise = Servidor.finalizar(habilidade, 'avaliacoes-qualitativas/habilidades', 'Habilidade');
                promise.then(function () {
                    $('#disciplina, #etapa').material_select();
                    $scope.fecharFormulario();
                });
            };

            /* Exclui uma habilidade */
            $scope.removerHabilidade = function (habilidade) {
                Servidor.remover(habilidade, 'Habilidade');
                $scope.habilidades.forEach(function (hab, i) {
                    if (hab.id === habilidade.id) {
                        $scope.habilidades.splice(i, 1);
                        $scope.numeracaoHabilidade();
                    }
                });
            };

            $scope.inicializar = function () {
                $timeout(function () {
                    $('.counter').each(function () {
                        $(this).characterCounter();
                        $('.modal-trigger').leanModal();
                    });
                    $('.tooltipped').tooltip('remove');
                    $('.tooltipped').tooltip({delay: 50});
                    /*Inicializando controles via Jquery Mobile */
                    if ($(window).width() < 993) {
                        $(".swipeable").on("swiperight", function () {
                            $('.swipeable').removeClass('move-right');
                            $(this).addClass('move-right');
                        });
                        $(".swipeable").on("swipeleft", function () {
                            $('.swipeable').removeClass('move-right');
                        });
                    }
                    $('.modal-trigger').leanModal({
                        dismissible: true, // Modal can be dismissed by clicking outside of the modal
                        opacity: .5, // Opacity of modal background
                        in_duration: 300, // Transition in duration
                        out_duration: 200, // Transition out duration
                        ready: function () {
                            alert('Ready');
                        }, // Callback for Modal open
                        complete: function () {
                            alert('Closed');
                        } // Callback for Modal close
                    }
                    );

                    $('#unidadeBusca, #cursoBusca, #etapaBusca, #disciplinaBusca').material_select();
                    $('.tooltipped').tooltip('remove');
                    $('.tooltipped').tooltip({delay: 50});
                    Servidor.entradaPagina();
                }, 500);
            };


            $scope.buscarSistemas();
            $scope.buscarUnidades();
            $scope.buscarCursos();
            $scope.inicializar();

        }]);
})();
