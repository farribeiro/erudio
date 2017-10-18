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

    var quadroHorarioModule = angular.module('quadroHorarioModule', ['servidorModule', 'quadroHorarioDirectives']);

    quadroHorarioModule.controller('quadroHorarioController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', '$compile', function ($scope, Servidor, Restangular, $timeout, $templateCache, $compile) {
            $templateCache.removeAll();
            
            $scope.escrita = Servidor.verificaEscrita('QUADRO_HORARIO');
            $scope.isAdmin = Servidor.verificaAdmin();
            $scope.turnoNome = '';
            $scope.nomeUnidadeBusca = '';
            
            $scope.unidade = {id: null};
            $scope.strNome = ''; // VARIAVEL QUE GUARDA A STRING DE BUSCA.
            /* Atributos Específicos */
            $scope.quadroHorarios = null;
            $scope.quadroHorario = {
                'nome': null, 'inicio': null,
                'modelo': {'id': null}, 'unidadeEnsino': {id: null},
                'turno': {'id': null}, 'diasSemana': []
            };

            /*Reinicia estrutura de QuadroHorario */
            $scope.reiniciar = function () {
                $('#segunda')[0].checked = false;
                $('#terca')[0].checked = false;
                $('#quarta')[0].checked = false;
                $('#quinta')[0].checked = false;
                $('#sexta')[0].checked = false;
                $scope.quadroHorario = {
                    nome: null, inicio: null,
                    modelo: {id: null}, unidadeEnsino: {id: null},
                    turno: {id: null}, diasSemana: []
                };
                $timeout(function () {
                    $('#modelo, #unidadeEnsino, #turno, #turma').material_select('destroy');
                    $('#modelo, #unidadeEnsino, #turno, #turma').material_select();
                }, 300);
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

            /* Buscando quadroHorarios - Lista */
            $scope.buscarQuadroHorarios = function () {
                $scope.mostraProgresso();
                var unidade = null;
                if(!$scope.isAdmin) { unidade = sessionStorage.getItem('unidade'); } else { unidade = ''; }
                if (unidade !== null) {
                    var promise = Servidor.buscar('quadros-horarios', {unidadeEnsino: unidade});
                    promise.then(function (response) {
                        $scope.quadroHorarios = response.data; $('.tooltipped').tooltip('remove');
                        $timeout(function () { $('.tooltipped').tooltip({delay: 50}); }, 50);
                    });
                }
                $scope.fechaProgresso();
            };

            $scope.buscarUmQuadroHorario = function (id) {
                var promise = Servidor.buscarUm('quadros-horarios', id);
                promise.then(function (response) {
                    $scope.quadroHorario = response.data;
                    $scope.quadroHorario.inicio = Servidor.formatarHora($scope.quadroHorario.inicio);
                    $scope.naoResetados = true;
                    $scope.editando = true;
                    $scope.quadroHorario.diasSemana.forEach(function (dia) {
                        $scope.controlaDiasSemana(dia.diaSemana.toString(), true);
                    });
                    $timeout(function () {
                        Servidor.verificaLabels();
                        if ($scope.isAdmin) {
                            $('.dropdown').dropdown({
                                inDuration: 300,
                                outDuration: 225,
                                constrain_width: true, // Does not change width of dropdown to that of the activator
                                hover: false, // Activate on hover
                                gutter: 45, // Spacing from edge
                                belowOrigin: true, // Displays dropdown below the button
                                alignment: 'left' // Displays dropdown with edge aligned to the left of button
                            });
                        }
                        $('.time').mask('00:00');
                        $('.date').mask('00/00/0000');
                        $('#modelo, #unidade, #turno, #turma').material_select('destroy');
                        $('#modelo, #unidade, #turno, #turma').material_select();
                        $('#titulo').focus();
                        $scope.fechaLoader();
                    }, 300);
                });
            };

            $scope.buscarModeloQuadroHorarios = function () {
                $scope.mostraProgresso();
                var promise = Servidor.buscar('modelo-quadro-horarios', null);
                promise.then(function (response) {
                    $scope.modelos = response.data;
                    $timeout(function () {
                        $scope.fechaProgresso();
                    }, 50);
                });
            };

            $scope.buscarUnidades = function () {
                if ($scope.nomeUnidade !== undefined && $scope.nomeUnidade !== null) {
                    if ($scope.nomeUnidade.length > 4) {
                        $scope.buscarUnidadesEnsino($scope.nomeUnidade);
                    } else {
                        $scope.unidades = [];
                    }
                }
            };
            
            $scope.buscarUnidadesBusca = function () {
                if ($scope.nomeUnidadeBusca !== undefined && $scope.nomeUnidadeBusca !== null) {
                    if ($scope.nomeUnidadeBusca.length !== '0') {
                        $scope.selecionaUnidadeBusca($scope.nomeUnidadeBusca);
                    } else {
                        $scope.buscarQuadroHorarios();
                        $scope.quadroHorarios = [];
                    }
                }
            };

            $scope.selecionaUnidade = function(unidade) {
                $scope.nomeUnidade = unidade.tipo.sigla + ' ' + unidade.nome;
                if ($scope.turnoNome !== undefined && $scope.turnoNome !== null) {
                    console.log('nome e turno unidade');
                    $scope.quadroHorario.nome = 'Quadro de Horário - ' + $scope.nomeUnidade + ' - ' + $scope.turnoNome;
                } else {
                    console.log('so nome');
                    $scope.quadroHorario.nome = 'Quadro de Horário - ' + $scope.nomeUnidade;
                }
                $scope.quadroHorario.unidadeEnsino = unidade;
            };
            
            $scope.selecionaUnidadeBusca = function(id) {
                //$scope.nomeUnidadeBusca = unidade.tipo.sigla + ' ' + unidade.nome;
                var promise = Servidor.buscar('quadros-horarios',{'unidadeEnsino':id});
                promise.then(function(response){
                    $scope.quadroHorarios = response.data;
                });
            };
            
            $scope.atualizaNome = function() {
                $timeout(function() {
                    var promise = Servidor.buscarUm('turnos',$scope.quadroHorario.turno.id);
                    promise.then(function(response){
                        var turno = response.data;
                        $scope.turnoNome = turno.nome;
                        if ($scope.nomeUnidade !== undefined && $scope.nomeUnidade !== null) {
                            console.log('nome e turno turno');
                            $scope.quadroHorario.nome = 'Quadro de Horário - ' + $scope.nomeUnidade + ' - ' + $scope.turnoNome;
                        } else {
                            console.log('so turno');
                            $scope.quadroHorario.nome = 'Quadro de Horário - ' + $scope.turnoNome;
                        }
                    });
                }, 100);
            };
            
            $scope.selecionaTurno = function(id) {
                id = parseInt(id);
                $scope.turnos.forEach(function(t) {
                    if(t.id === id) {
                        $scope.quadroHorario.turno = t;
                    }
                });
            };
            
            $scope.selecionaModelo = function(id) {
                id = parseInt(id);
                $scope.modelos.forEach(function(m) {
                    if(m.id === id) {
                        $scope.quadroHorario.modelo = m;
                    }
                });
            };

            $scope.buscarUnidadesEnsino = function (nome) {
                if($scope.isAdmin){
                    var promise = Servidor.buscar('unidades-ensino', {nome:nome});
                    promise.then(function (response) {
                        $scope.unidades = response.data;
                        if ($scope.isAdmin) {
                            $timeout(function() {
                                $('#unidadeBusca').material_select('destroy');
                                $('#unidadeBusca').material_select();
                            }, 200);
                        }
                    });
                } else {
                    var promise = Servidor.buscarUm('alocacoes', sessionStorage.getItem('alocacao'));
                    promise.then(function (response) {
                        $scope.unidades = [response.data.instituicao];
                    });
                }
            };

            /*Busca de Turnos*/
            $scope.buscarTurnos = function () {
                var promise = Servidor.buscar('turnos', null);
                promise.then(function(response) {
                    $scope.turnos = response.data;
                    $timeout(function() { $('#turno').material_select(); }, 50);
                });
//                if ($scope.quadroHorario.inicio !== undefined && $scope.quadroHorario.inicio.length === 5) {
//                    var array = $scope.quadroHorario.inicio.split(':');
//                    var aux = array[0];
//                    var promise = Servidor.buscar('turnos', null);
//                    promise.then(function (response) {
//                        $scope.turnos = response.data;
//                        for (var i = 0; i < response.data.length; i++) {
//                            if ($scope.turnos[i].inicio) {
//                                var array2 = $scope.turnos[i].inicio.split(':');
//                                var aux2 = array2[0];
//                                var array3 = $scope.turnos[i].termino.split(':');
//                                var aux3 = array3[0];
//                                if (aux2 > aux || aux3 <= aux) {
//                                    $scope.turnos.splice(i, 1);
//                                    i--;
//                                }
//                                if (i === $scope.turnos.length - 1) {
//                                    if (!$scope.turnos.length) {
//                                        Materialize.toast('Não há turnos compatíveis com o horario de início.', 2500);
//                                    } else {
//                                        $scope.quadroHorario.turno.id = null;
//                                    }
//                                    $timeout(function () {
//                                        $('#turno').material_select('destroy');
//                                        $('#turno').material_select();
//                                    }, 200);
//                                }
//                            }
//                        }
//                    });
//                }
            };

            $scope.verificarTurnoCompativel = function() {
                if($scope.quadroHorario.inicio && $scope.quadroHorario.inicio.length === 5) {
                    var horario = parseInt($scope.quadroHorario.inicio.split(':').join(''));                    
                    $scope.turnosCompativeis = [];
                    $scope.turnos.forEach(function(turno) {
                        var inicio = parseInt(turno.inicio.split(':').join(''))/100;
                        var termino = parseInt(turno.termino.split(':').join(''))/100;
                        if (inicio <= horario && horario <= termino) {
                            $scope.turnosCompativeis.push(turno);
                        }
                    });
                    if ($scope.turnosCompativeis.length) {
                        if ($scope.turnosCompativeis.length === 1) {
                            $scope.quadroHorario.turno = $scope.turnosCompativeis[0];
                        }                        
                    } else {
                        $scope.quadroHorario.turno = {id: null};
                        Servidor.customToast("Não há nenhum turno compatível para este horário.");
                    }
                    $timeout(function(){$('#turno').material_select();},100);
                }
            };

            $scope.converterHora = function (date) {
                /*var separar = date.split(':'); date = separar[0]+':'+separar[1];*/
                var separar = date.split(' ');
                if (separar[1] === 'PM') {
                    var hora = parseInt(separar[0].split(':')[0]) + 12;
                    if (hora === 24) {
                        hora = 00;
                    }
                    var minuto = separar[0].split(':')[1];
                    date = hora + ':' + minuto;
                } else {
                    date = separar[0];
                }
                return date + ':00';
            };

            $scope.finalizar = function () {
                $scope.mostraProgresso();
                if ($scope.validarQuadro('validate-quadro')) {
                    if ($scope.quadroHorario.diasSemana.length) {
                        var horaQuebrada = $scope.converterHora($scope.quadroHorario.inicio).split(':').join('');
                        horaQuebrada = parseInt(horaQuebrada);
                        if ($scope.quadroHorario.unidadeEnsino.id !== null) {
                            $scope.quadroHorario.inicio = $scope.quadroHorario.inicio.toString() + ':00';
                            $scope.selecionaTurno($scope.quadroHorario.turno.id);
                            $scope.selecionaModelo($scope.quadroHorario.modelo.id);
                            var promise = Servidor.finalizar($scope.quadroHorario, 'quadros-horarios', 'Quadro de horario');
                            promise.then(function () {
                                $scope.fecharFormulario();
                                $('.tooltipped').tooltip('remove');
                                $timeout(function () {
                                    $('.tooltipped').tooltip({delay: 50});
                                    $scope.buscarQuadroHorarios();
                                    $scope.fechaProgresso();
                                }, 700);
                            }, function() {
                                Servidor.customToast('Turno inválido.');
                                $scope.fechaProgresso();
                            });
                        } else {
                            Servidor.customToast('Selecione uma unidade de ensino.');
                            $scope.fechaProgresso();
                        }
                    } else {
                        Servidor.customToast('Selecione ao menos 1 dia da semana');
                        $scope.fechaProgresso();
                    }
                } else {
                    $scope.fechaProgresso();
                }
            };

            /*Validar quadro de horarios*/
            $scope.validarQuadro = function (id) {
                var result = Servidor.validar(id);
                if (result === true) {
                    return true;
                } else {
                    return false;
                }
            };

            $scope.buscarTodosTurnos = function (quadroHorario) {
                var promise = Servidor.buscar('turnos', null);
                promise.then(function (response) {
                    $scope.turnos = response.data;
                    $scope.turnosCompativeis = $scope.turnos;
                    //$scope.verificarTurnoCompativel();
                    $scope.buscarUmQuadroHorario(quadroHorario.id);
                });
            };

            $scope.carregarFormulario = function (quadroHorario) {
                window.scrollTo(0, 0);
                $scope.acao = "Cadastrar";
                $('.time').mask('00:00');
                $scope.mostraLoader();
                $scope.turnosCompativeis = [];
                if (quadroHorario) {
                    //$scope.buscarUmQuadroHorario(quadroHorario.id);
                    //$scope.buscarTurnos();
                    $scope.buscarTodosTurnos(quadroHorario);
                    $scope.acao = "Editar";
                } else {
                    
                    $scope.quadroHorario = {
                        nome: 'Quadro de Horário - ', inicio: null,
                        modelo: {id: null}, unidadeEnsino: {id: null},
                        turno: {id: null}, diasSemana: []
                    };
                    $scope.editando = true;
                    if ($scope.unidades.length === 1) {
                        $scope.quadroHorario.unidadeEnsino = $scope.unidades[0];
                    };
                    $scope.quadroHorario.diasSemana = [
                        {diaSemana: "2"}, {diaSemana: "3"},
                        {diaSemana: "4"}, {diaSemana: "5"},
                        {diaSemana: "6"}
                    ];
                    $timeout(function () {
                        $('#segunda')[0].checked = true;
                        $('#terca')[0].checked = true;
                        $('#quarta')[0].checked = true;
                        $('#quinta')[0].checked = true;
                        $('#sexta')[0].checked = true;
                        if ($scope.isAdmin) {
                            $scope.unidades = [];
                            $('.dropdown').dropdown({
                                inDuration: 300,
                                outDuration: 225,
                                constrain_width: true, // Does not change width of dropdown to that of the activator
                                hover: false, // Activate on hover
                                gutter: 45, // Spacing from edge
                                belowOrigin: true, // Displays dropdown below the button
                                alignment: 'left' // Displays dropdown with edge aligned to the left of button
                            });
                        }
                        $('.time').mask('00:00');
                        $('.date').mask('00/00/0000');
                        $('#modelo, #unidade, #turno, #turma').material_select('destroy');
                        $('#modelo, #unidade, #turno, #turma').material_select();
                        $('#titulo').focus();
                        $scope.fechaLoader();
                    }, 500);
                }
            };

            $scope.prepararVoltar = function (objeto) {
                if (objeto.nome && !objeto.id) {
                    $('#modal-certeza-quadro').openModal();
                } else {
                    $scope.fecharFormulario();
                }
            };

            $scope.fecharFormulario = function () {
                $scope.mostraProgresso();
                $scope.editando = false;
                $scope.nomeUnidade = '';
                $timeout(function () {
                    $scope.buscarQuadroHorarios();
                    $scope.reiniciar();
                }, 300);
            };

            // Funcao que gerencia quais dias da semana o quadro de horario ira ter
            $scope.controlaDiasSemana = function (dia, existem) {
                switch (dia) {
                    case "2":
                        if (existem) {
                            document.getElementById('segunda').checked = true;
                        } else {
                            if (document.getElementById('segunda').checked === true) {
                                /*$scope.toggleDiaSemana('#segunda', dia, existem);*/
                                $scope.quadroHorario.diasSemana.push({'diaSemana': dia});
                            } else {
                                for (var i = 0; i < $scope.quadroHorario.diasSemana.length; i++) {
                                    if ($scope.quadroHorario.diasSemana[i].diaSemana === dia) {
                                        $scope.quadroHorario.diasSemana.splice(i, 1);
                                    }
                                }
                            }
                        }
                        break;
                    case "3":
                        if (existem) {
                            document.getElementById('terca').checked = true;
                        } else {
                            if (document.getElementById('terca').checked === true) {
                                $scope.quadroHorario.diasSemana.push({'diaSemana': dia});
                            } else {
                                for (var i = 0; i < $scope.quadroHorario.diasSemana.length; i++) {
                                    if ($scope.quadroHorario.diasSemana[i].diaSemana === dia) {
                                        $scope.quadroHorario.diasSemana.splice(i, 1);
                                    }
                                }
                            }
                        }
                        break;
                    case "4":
                        if (existem) {
                            document.getElementById('quarta').checked = true;
                        } else {
                            if (document.getElementById('quarta').checked === true) {
                                $scope.quadroHorario.diasSemana.push({'diaSemana': dia});
                            } else {
                                for (var i = 0; i < $scope.quadroHorario.diasSemana.length; i++) {
                                    if ($scope.quadroHorario.diasSemana[i].diaSemana === dia) {
                                        $scope.quadroHorario.diasSemana.splice(i, 1);
                                    }
                                }
                            }
                        }
                        break;
                    case "5":
                        if (existem) {
                            document.getElementById('quinta').checked = true;
                        } else {
                            if (document.getElementById('quinta').checked === true) {
                                $scope.quadroHorario.diasSemana.push({'diaSemana': dia});
                            } else {
                                for (var i = 0; i < $scope.quadroHorario.diasSemana.length; i++) {
                                    if ($scope.quadroHorario.diasSemana[i].diaSemana === dia) {
                                        $scope.quadroHorario.diasSemana.splice(i, 1);
                                    }
                                }
                            }
                        }
                        break;
                    case "6":
                        if (existem) {
                            document.getElementById('sexta').checked = true;
                        } else {
                            if (document.getElementById('sexta').checked === true) {
                                $scope.quadroHorario.diasSemana.push({'diaSemana': dia});
                            } else {
                                for (var i = 0; i < $scope.quadroHorario.diasSemana.length; i++) {
                                    if ($scope.quadroHorario.diasSemana[i].diaSemana === dia) {
                                        $scope.quadroHorario.diasSemana.splice(i, 1);
                                    }
                                }
                            }
                        }
                        break;
                }
            };

            /* Inicializando */
            $scope.inicializar = function (inicializaContador, primeiraVez) {
                $('.tooltipped').tooltip('remove');
                $scope.buscarTurnos();
                $timeout(function () {
                    if (inicializaContador) {
                        $('.counter').each(function () {
                            $(this).characterCounter();
                        });
                    }
                    $('.tooltipped').tooltip({delay: 50});
                    $('.modal-trigger').leanModal({dismissible: true, complete: function () {
                            $('.lean-overlay').hide();
                        }});
                    /*Inicializando controles via Jquery Mobile */
                    if ($(window).width() < 993) {
                        $('.hora').lolliclock({autoclose: false, hour24: false});
                        $(".swipeable").on("swiperight", function () {
                            $('.swipeable').removeClass('move-right');
                            $(this).addClass('move-right');
                        });
                        $(".swipeable").on("swipeleft", function () {
                            $('.swipeable').removeClass('move-right');
                        });
                    }
                    if (primeiraVez) { $timeout(function(){ Servidor.entradaPagina(); },500); }
                }, 100);
            };

            /* Guarda o modulo para futura remoção e abre o modal de confirmação */
            $scope.prepararRemover = function (quadroHorario) {
                var promise = Servidor.buscar('turmas', {quadroHorario: quadroHorario.id});
                promise.then(function (response) {
                    if(response.data.length) {
                        Servidor.customToast('Quadro de Horários não pode ser excluido.');
                    } else {
                        $scope.quadroHorarioRemover = quadroHorario;
                        $('#remove-modal-quadroHorario').openModal();
                    }
                });
            };

            /* Remove o modulo */
            $scope.remover = function () {
                $scope.mostraProgresso();
                Servidor.remover($scope.quadroHorarioRemover, 'quadros-horario');
                $timeout(function () {
                    $scope.buscarQuadroHorarios();
                    $scope.fechaProgresso();
                }, 100);
            };

            $scope.verificaSelectModelo = function (id) {
                if ($scope.quadroHorario.modelo) {
                    if (id === $scope.quadroHorario.modelo.id) {
                        return true;
                    }
                }
            };

            $scope.verificaSelectUnidadeEnsino = function (id) {
                if ($scope.quadroHorario.unidadeEnsino) {
                    if (id === $scope.quadroHorario.unidadeEnsino.id) {
                        return true;
                    }
                }
            };
            /* Verifica campo de Turno*/
            $scope.verificaSelectTurno = function (id) {
                if ($scope.quadroHorario.turno) {
                    if (id === $scope.quadroHorario.turno.id) {
                        return true;
                    }
                }
            };
            
            /* REINICIAR BUSCA */
            $scope.resetaBusca = function (){ $scope.strUsuario = ''; };

             /* LIMPAR BUSCA */
            $scope.limparBusca = function(){ $scope.strUsuario=''; };
            
            /* BUSCA - LISTENER  */
            $scope.$watch("strNome", function(query){
                $scope.buscaQuadro(query);
                if(!query) {$scope.icone = 'search'; }
                else { $scope.icone = 'clear'; }
            });

            /* BUSCA */
            $scope.buscaQuadro = function (query) {
                $timeout.cancel($scope.delayBusca);
                $scope.delayBusca = $timeout(function(){
                    if (query === undefined) { query = ''; }
                    var tamanho = query.length;
                    if (tamanho > 3) {
                        var res = null;
                        var unidade = null;
                        if(!$scope.isAdmin) { unidade = sessionStorage.getItem('unidade'); } else { unidade = ''; }
                        res = Servidor.buscar('quadros-horarios',{'nome':query,'unidadeEnsino':unidade});
                        res.then(function(response){
                            $scope.quadroHorarios = response.data;
                            $timeout(function (){ $scope.inicializar(false); $('.collection li').css('opacity',1); });
                        });
                    } else {
                        if (tamanho === 0) {
                            $scope.inicializar(false); $scope.buscarQuadroHorarios();
                            $('.collection li').css('opacity','');
                        }
                    }
                }, 1000);
            };

            //temporario
            $scope.fix = false;
            $scope.fixGeral = false;
            $scope.quadros = [];
            
            $scope.quadroH = {
                'nome': null, 'inicio': null, 'modelo': {'id': null}, 'unidadeEnsino': {id: null}, 'turno': {'id': null}, 'diasSemana': [{diaSemana: "2"}, {diaSemana: "3"}, {diaSemana: "4"}, {diaSemana: "5"}, {diaSemana: "6"}]
            };
            
            $scope.preparaFix = function() {
                $scope.fix = true;
                $timeout(function (){ $('#unidadeB').material_select('destroy'); $('#unidadeB').material_select(); }, 100);
            };
            
            $scope.buscarFixTurnos = function () {
                var promise = Servidor.buscar('turnos', null);
                promise.then(function (response) {
                    $scope.turnos = response.data;
                });
            };
            
            $scope.salvarParcial = function (k, geral, unidadeId) {
                var qMatutino = angular.copy($scope.quadroH);
                var qVespertino = angular.copy($scope.quadroH);
                if (geral) { qMatutino.unidadeEnsino.id = unidadeId; qVespertino.unidadeEnsino.id = unidadeId; }
                
                for (var t=0; t<$scope.turnos.length; t++) {
                    if ($scope.turnos[t].nome === "Matutino") {
                        qMatutino.turno.id = $scope.turnos[t].id;
                        qMatutino.inicio = $scope.turnos[t].inicio;
                        qMatutino.nome = $scope.modelosCompativeis[k].nome + ' - ' + $scope.turnos[t].nome;
                        qMatutino.modelo.id = $scope.modelosCompativeis[k].id;
                        $scope.qMat = qMatutino;
                        if (!geral) {
                            console.log('Criando Quadro Matutino...');
                            $scope.turnosNome.push($scope.turnos[t].nome);
                            $scope.modelosNome.push($scope.modelosCompativeis[k].nome);
                        } else {
                            console.log('Criando Quadro Matutino...');
                            Servidor.finalizar($scope.qMat, 'quadros-horarios', 'Quadros de horario');
                        }
                    } else if ( $scope.turnos[t].nome === "Vespertino") {
                        qVespertino.turno.id = $scope.turnos[t].id;
                        qVespertino.inicio = $scope.turnos[t].inicio;
                        qVespertino.nome = $scope.modelosCompativeis[k].nome + ' - ' + $scope.turnos[t].nome;
                        qVespertino.modelo.id = $scope.modelosCompativeis[k].id;
                        $scope.qVesp = qVespertino;
                        if (!geral) {
                            console.log('Criando Quadro Vespertino...');
                            $scope.turnosNome.push($scope.turnos[t].nome);
                            $scope.modelosNome.push($scope.modelosCompativeis[k].nome);
                        } else {
                            
                            Servidor.finalizar($scope.qVesp, 'quadros-horarios', 'Quadros de horario');
                        }
                    }
                }
                $timeout(function (){ 
                    if (!geral) { $scope.criados.push($scope.qMat); $scope.criados.push($scope.qVesp); }
                },100);
            };
            
            $scope.salvarIntegral = function (k, geral, unidadeId) {
                var qIntegral = angular.copy($scope.quadroH);
                if (geral) { qIntegral.unidadeEnsino.id = unidadeId; }
                
                for (var t=0; t<$scope.turnos.length; t++) {
                    if ($scope.turnos[t].nome === "Integral") {
                        qIntegral.turno.id = $scope.turnos[t].id;
                        qIntegral.inicio = $scope.turnos[t].inicio;
                        qIntegral.nome = $scope.modelosCompativeis[k].nome + ' - ' + $scope.turnos[t].nome;
                        qIntegral.modelo.id = $scope.modelosCompativeis[k].id;
                        $scope.qIntegral = qIntegral;
                        if (!geral) {
                            console.log('Criando Quadro Integral...');
                            $scope.turnosNome.push($scope.turnos[t].nome);
                            $scope.modelosNome.push($scope.modelosCompativeis[k].nome);
                        } else {
                            console.log('Criando Quadro Integral...');
                            Servidor.finalizar($scope.qIntegral, 'quadros-horarios', 'Quadros de horario');
                        }
                    }
                }
                $timeout(function (){ 
                    if (!geral) { $scope.criados.push($scope.qIntegral); }
                },100);
            };
            
            $scope.salvarNoturno = function (k, geral, unidadeId) {
                var qNoturno = angular.copy($scope.quadroH);
                if (geral) { qNoturno.unidadeEnsino.id = unidadeId;}
                
                for (var t=0; t<$scope.turnos.length; t++) {
                    if ($scope.turnos[t].nome === "Noturno") {
                        qNoturno.turno.id = $scope.turnos[t].id;
                        qNoturno.inicio = $scope.turnos[t].inicio;
                        qNoturno.nome = $scope.modelosCompativeis[k].nome + ' - ' + $scope.turnos[t].nome;
                        qNoturno.modelo.id = $scope.modelosCompativeis[k].id;
                        $scope.qNoturno = qNoturno;
                        if (!geral) {
                            $scope.turnosNome.push($scope.turnos[t].nome);
                            $scope.modelosNome.push($scope.modelosCompativeis[k].nome);
                        } else {
                            console.log('Criando Quadro Noturno...');
                            Servidor.finalizar($scope.qNoturno, 'quadros-horarios', 'Quadros de horario');
                        }
                    }
                }
                $timeout(function (){ 
                    if (!geral) { $scope.criados.push($scope.qNoturno); }
                },100);
            };
            
            $scope.preparaQuadro = function () {
                $scope.buscarFixTurnos();
                $scope.criados = [];
                $scope.turnosNome = [];
                $scope.modelosNome = [];
                var promise = Servidor.buscarUm('unidades-ensino',$scope.quadroH.unidadeEnsino.id);
                promise.then(function (response){
                    var cursosUnidade = response.data.cursos;
                        if (cursosUnidade.length > 0) {
                        $scope.nomeUnidade = response.data.nome;
                        $scope.cursosUnidade = [];
                        $scope.modelosCompativeis = [];
                        for (var i=0; i<cursosUnidade.length; i++) { $scope.cursosUnidade.push(cursosUnidade[i].id); }

                        for (var j=0; j<$scope.modelos.length; j++) {
                            if ($scope.cursosUnidade.indexOf($scope.modelos[j].curso.id) !== -1) {
                                //$scope.modelosCompativeis.push($scope.modelos[j]);
                                
                                if ($scope.modelos[j].id === 12) { $scope.salvarParcial(k, false, null); } // fundamental
                                if ($scope.modelos[j].id === 13) { $scope.salvarParcial(k, false, null); } // ensino inf parcial
                                if ($scope.modelos[j].id === 16) { $scope.salvarParcial(k, false, null); } // edu integral
                                if ($scope.modelos[j].id === 15) { $scope.salvarNoturno(k, false, null); } // eja
                                if ($scope.modelos[j].id === 14) { $scope.salvarIntegral(k, false, null); } // edu infantil integral
                            }
                        }

                        //$timeout(function (){
                            //for (var k=0; k<$scope.modelosCompativeis.length; k++) {
                                /*if ($scope.modelosCompativeis[k].id === 12) { $scope.salvarParcial(k, false, null); } // fundamental
                                if ($scope.modelosCompativeis[k].id === 13) { $scope.salvarParcial(k, false, null); } // ensino inf parcial
                                if ($scope.modelosCompativeis[k].id === 16) { $scope.salvarParcial(k, false, null); } // edu integral
                                if ($scope.modelosCompativeis[k].id === 15) { $scope.salvarNoturno(k, false, null); } // eja
                                if ($scope.modelosCompativeis[k].id === 14) { $scope.salvarIntegral(k, false, null); } // edu infantil integral*/
                                //var nome = $scope.modelosCompativeis[k].nome;
                                /*var resultFundamental = nome.match(/Fundamental/g);
                                var resultParcial = nome.match(/Parcial/g);
                                var resultIntegral = nome.match(/Integral/g);*/
                                //var resultEJA = nome.match(/EJA/g);

                                /*if (resultFundamental !== null) { $scope.salvarParcial(k, false, null); }
                                if (resultParcial !== null) { $scope.salvarParcial(k, false, null); }
                                if (resultIntegral !== null) { $scope.salvarIntegral(k, false, null); }*/
                                //if (resultEJA !== null) { $scope.salvarNoturno(k, false, null); }
                            //}
                        //}, 50);
                    }
                });
            };
            
            $scope.salvaQuadroGeral = function (id) {
                var promise = Servidor.buscarUm('unidades-ensino',id);
                promise.then(function (response){
                    var cursosUnidade = response.data.cursos;
                    if (cursosUnidade.length > 0) {
                        $scope.cursosUnidade = []; //$scope.modelosCompativeis = [];
                        for (var i=0; i<cursosUnidade.length; i++) { $scope.cursosUnidade.push(cursosUnidade[i].id); }
                        for (var j=0; j<$scope.modelos.length; j++) { 
                            if ($scope.cursosUnidade.indexOf($scope.modelos[j].curso.id) !== -1) {
                                //$scope.modelosCompativeis.push($scope.modelos[j]);
                                if ($scope.modelos[j].id === 12) { $scope.salvarParcial(k, true, id); } // fundamental
                                if ($scope.modelos[j].id === 13) { $scope.salvarParcial(k, true, id); } // ensino inf parcial
                                if ($scope.modelos[j].id === 16) { $scope.salvarParcial(k, true, id); } // edu integral
                                if ($scope.modelos[j].id === 15) { $scope.salvarNoturno(k, true, id); } // eja
                                if ($scope.modelos[j].id === 14) { $scope.salvarIntegral(k, true, id); } // edu infantil integral
                            }
                        }
                        /*$timeout(function (){
                            for (var k=0; k<$scope.modelosCompativeis.length; k++) {
                                if ($scope.modelosCompativeis[k].id === 12) { $scope.salvarParcial(k, true, id); }
                                if ($scope.modelosCompativeis[k].id === 13) { $scope.salvarParcial(k, true, id); }
                                if ($scope.modelosCompativeis[k].id === 16) { $scope.salvarParcial(k, true, id); }
                                if ($scope.modelosCompativeis[k].id === 15) { $scope.salvarNoturno(k, true, id); }
                                if ($scope.modelosCompativeis[k].id === 14) { $scope.salvarIntegral(k, true, id); }
                                //var resultParcial = nome.match(/Parcial/g);
                                //var resultEduIntegral = nome.match(/Educação|Integral/g);
                                //var resultIntegral = nome.match(/Infantil|Integral/g);
                                //var resultEJA = nome.match(/EJA/g);

                                //if (resultParcial !== null) { $scope.salvarParcial(k, true, id); }
                                //if (resultEduIntegral !== null) { $scope.salvarParcial(k, true, id); }
                                //if (resultIntegral !== null) { $scope.salvarIntegral(k, true, id); }
                                //if (resultEJA !== null) { $scope.salvarNoturno(k, true, id); }
                                
                                /*var resultFundamental = 0; if (nome === "Ensino Fundamental") { resultFundamental = 1; }
                                var resultPInt = 0; if (nome === "Educação Infantil Parcial") { resultPInt = 1; }
                                var resultIInt = 0; if (nome === "Educação Infantil Integral") { resultIInt = 1; }*/
                                //var resultEJA = 0; if (nome === "EJA") { resultEJA = 1; }
                                //var resultEInt = 0; if (nome === "Educação Integral") { resultEInt = 1; }

                                /*if (resultFundamental) { $scope.salvarParcial(k, true, id); }
                                if (resultPInt) { $scope.salvarParcial(k, true, id); }
                                if (resultIInt) { $scope.salvarIntegral(k, true, id); }*/
                                //if (resultEJA) { $scope.salvarNoturno(k, true, id); }
                                //if (resultEInt) { $scope.salvarParcial(k, true, id); }
                            //}
                        //}, 50);
                    }
                });
            };
            
            $scope.fixQuadroHorarioPorEscola = function () {
                $scope.mostraProgresso();
                for (var c=0; c<$scope.criados.length; c++) {
                    var promise = Servidor.finalizar($scope.criados[c], 'quadros-horarios', 'Quadros de horario');
                    if (c === $scope.criados.length-1) {
                        promise.then(function(){
                            $scope.fechaProgresso(); $scope.fix = false;
                        });
                    }
                }
            };
            
            $scope.fixQuadroHorariosGeral = function () {
                $scope.fixGeral = true;
                $scope.mostraProgresso();
                $scope.turnosNome = [];
                $scope.modelosNome = [];
                $scope.buscarFixTurnos();
                
                for (var u=0; u<$scope.unidades.length; u++) {
                    //console.log($scope.unidades[u].nome + ' - Gerando Quadro de Horário');
                    $scope.salvaQuadroGeral($scope.unidades[u].id);
                    if (u === $scope.unidades.length-1) { $scope.fechaProgresso(); }
                }
            };

            $scope.inicializar(false, true);
            $scope.buscarQuadroHorarios();
            $scope.buscarModeloQuadroHorarios();
            $scope.buscarUnidadesEnsino();
        }]);
})();
