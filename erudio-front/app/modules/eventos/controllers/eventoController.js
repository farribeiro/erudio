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

    var eventoModule = angular.module('eventoModule', ['servidorModule', 'eventoDirectives']);

    eventoModule.controller('eventoController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', function ($scope, Servidor, Restangular, $timeout, $templateCache) {
        $templateCache.removeAll();
            
        $scope.escrita = Servidor.verificaEscrita('EVENTO');            
        /* Atributos Específicos */
        $scope.eventos = [];
        $scope.evento = {
            nome: null,
            tipo: null,
            descricao: null
        };

        /* Atributos de controle da página */
        $scope.editando = false;
        $scope.loader = false;
        $scope.progresso = false;

        /* Controle da barra de progresso */
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

        /* Buscando eventos - Lista */
        $scope.buscarEventos = function () {
            $('.tooltipped').tooltip('remove');
            $timeout(function(){
                $('.tooltipped').tooltip({delay: 50});
            },150);
            $scope.mostraProgresso();
            var promise = Servidor.buscar('eventos', {'fixo': true});
            promise.then(function (response) {
                $scope.eventos = response.data;
                $timeout(function () {
                    $scope.fechaProgresso();
                }, 500);
            });
        };

        /* Edita o evento */
        $scope.finalizarEvento = function(){
            if ($scope.evento.nome && $scope.evento.tipo) {
                $scope.evento.fixo = true;
                var promise = Servidor.finalizar($scope.evento, 'eventos', 'Evento');
                promise.then(function(response){
                    if ($scope.evento.id) {
                        $scope.eventos.forEach(function (evento) {
                            if (evento.id === response.data.id) {
                                evento = response.data;
                                $scope.fecharFormulario();
                                $scope.fechaProgresso();
                                return;
                            }
                        });
                    } else {
                        $scope.eventos.push(response.data);
                        $scope.fecharFormulario();
                        $scope.fechaProgresso();
                    }
                });
            } else {
                Materialize.toast('Campos obrigatórios não preenchidos.', 2500);
            }
        };

        /* Verifica se o usuário deseja descartar os dados preenchidos */
        $scope.prepararVoltar = function(objeto) {
            if (objeto.nome && !objeto.id) {
                $('#modal-certeza').modal();
            } else {
                $scope.fecharFormulario();
            }
        };

        /* Abre o formulário */
        $scope.carregarFormulario = function (evento){
            $scope.moobad = true;
            $scope.acao = "Cadastrar";
            if (evento) {
                $scope.evento = evento;
                $scope.acao = "Editar";
            }
            $timeout(function() {
               Servidor.verificaLabels();
               $('#tipo').material_select();
               $timeout(function(){$('#titulo').focus();},150);
               $scope.editando = true;
            }, 300);
        };

        /* Volta para a busca */
        $scope.fecharFormulario = function () {
            $scope.editando = false;
            $('#tipo').material_select('destroy');
            $scope.reiniciar();
            $scope.buscarEventos();
        };

        /*Reinicia estrutura de Evento */
        $scope.reiniciar = function () {
            $scope.evento = {
                nome: null,
                tipo: null,
                descricao: null
            };
        };

        /* Inicializando */
        $scope.inicializar = function (inicializaContador) {
            $('.tooltipped').tooltip('remove');
            $timeout(function () {
                if (inicializaContador) {
                    $('.counter').each(function () {
                        $(this).characterCounter();
                    });
                }
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
                Servidor.entradaPagina();
            }, 1000);
        };

        /* Guarda o modulo para futura remoção e abre o modal de confirmação */
        $scope.prepararRemover = function (evento) {
            $scope.eventoRemover = evento;
            $('#remove-modal-evento').modal();
        };

        /* Remove o modulo */
        $scope.remover = function () {
            $scope.mostraProgresso();
            $scope.eventos.forEach(function(evento, i) {
                if (parseInt(evento.id) === parseInt($scope.eventoRemover.id)) {
                    $scope.eventos.splice(i, 1);
                    Servidor.remover($scope.eventoRemover, 'Evento');
                    $scope.fechaProgresso();
                    return;
                }
            });
        };

        $scope.verificaSelectTipo = function (id) {
            if ($scope.evento.tipo) {
                if (id === $scope.evento.tipo) {
                    return true;
                }
            }
        };
        $scope.inicializar();
        $scope.buscarEventos();
    }]);
})();
