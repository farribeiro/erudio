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

/* global angular */
(function () {
    var calendarioModule = angular.module('calendarioModule', ['servidorModule', 'calendarioDirectives']);

    calendarioModule.controller('calendarioController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', 'dateTime', function ($scope, Servidor, Restangular, $timeout, $templateCache, dateTime) {
        $templateCache.removeAll();
        
        $scope.escrita = Servidor.verificaEscrita('CALENDARIO');
        $scope.isAdmin = Servidor.verificaAdmin();
        $scope.instituicaoId = parseInt(sessionStorage.getItem('instituicao'));
        $scope.unidadeId = parseInt(sessionStorage.getItem('unidade'));
        $scope.calendarios = [];
        $scope.instituicoes = [];
        $scope.semanas = [];
        $scope.acao = '';
        $scope.inicio = null;
        $scope.termino = null;
        $scope.calendario = {
            nome: null,
            dataInicio: null,
            dataTermino: null,
            instituicao: null,
            calendarioBase: null
        };
        $scope.calendarioBase = $scope.calendario;
        $scope.efetivos = 0;
        $scope.letivos = 0;
        $scope.naoLetivos = 0;
        $scope.dia = {};
        $scope.mes = {
            'num': null,
            'nome': null
        };

        $scope.evento = {
            'dia': {'id': null},
            'evento': {'id': null},
            'inicio': null,
            'termino': null
        };

        $scope.semana = {
            'domingo': {'dia': {'data': null}, 'eventos': []},
            'segunda': {'dia': {'data': null}, 'eventos': []},
            'terca': {'dia': {'data': null}, 'eventos': []},
            'quarta': {'dia': {'data': null}, 'eventos': []},
            'quinta': {'dia': {'data': null}, 'eventos': []},
            'sexta': {'dia': {'data': null}, 'eventos': []},
            'sabado': {'dia': {'data': null}, 'eventos': []}
        };

        $scope.editando = false;
        $scope.editandoEvento = false;
        $scope.progresso = false;
        $scope.abrirCortina = function() { $scope.progresso = true; };
        $scope.fecharCortina = function() { $scope.progresso = false; };
        $scope.limparCalendario = function() {
            $scope.calendario = {
                nome: null,
                dataInicio: null,
                dataTermino: null,
                instituicao: null,
                calendarioBase: null
            };
            $scope.nomeInstituicao = '';
            $scope.sistemaAvaliacao = {id: null};            
        };

        $scope.limparSemana = function () {
            $scope.semana = {
                'domingo': {'dia': {'data': null}, 'eventos': []},
                'segunda': {'dia': {'data': null}, 'eventos': []},
                'terca': {'dia': {'data': null}, 'eventos': []},
                'quarta': {'dia': {'data': null}, 'eventos': []},
                'quinta': {'dia': {'data': null}, 'eventos': []},
                'sexta': {'dia': {'data': null}, 'eventos': []},
                'sabado': {'dia': {'data': null}, 'eventos': []}
            };
        };

        $scope.limparEvento = function() {
            $scope.evento = {
                'dia': {'id': null},
                'evento': {'id': null},
                'inicio': null,
                'termino': null
            };
        };

        /* Funções de Busca */
        $scope.buscarCalendarios = function() {
            $scope.abrirCortina();
            $scope.calendariosBases = [];
            var unidade = ($scope.isAdmin) ? null : sessionStorage.getItem('unidade');
            var promise = Servidor.buscar('calendarios', {instituicao: unidade});
            promise.then(function(response) {
                $scope.calendarios = response.data;                    
                $('.tooltipped').tooltip('remove');
                $timeout(function() { $('.tooltipped').tooltip({delay: 50}); }, 250);
                $scope.buscarInstituicoesUnidades();
            });
            var promise = Servidor.buscar('calendarios', {instituicao: sessionStorage.getItem('instituicao')});
            promise.then(function(response) {
                $scope.calendariosBases = response.data;
            });
        };

        /* Busca as instituicoes e as unidades */
        $scope.buscarInstituicoesUnidades = function() {
            $scope.instituicoes = [];
            if (!$scope.isAdmin && $scope.unidadeId !== undefined && $scope.unidadeId) {
                var promise = Servidor.buscarUm('unidades-ensino', $scope.unidadeId);
                promise.then(function(response) {
                    $scope.instituicoes.push(response.data);
                    $scope.calendario.instituicao = response.data;
                    $scope.fecharCortina();
                    Servidor.entradaPagina();
                });
            } else {
                var promise = Servidor.buscar('instituicoes');
                promise.then(function(response) {
                    $scope.instituicoes = response.data;
                    if ($scope.calendarios.length) {
                        var promise = Servidor.buscar('unidades-ensino');
                        promise.then(function(response) {
                            response.data.forEach(function(unidade) {
                               $scope.instituicoes.push(unidade);
                            });
                            $scope.fecharCortina();
                            Servidor.entradaPagina();
                        });
                    } else {
                        Servidor.entradaPagina();
                        $scope.fecharCortina();
                    }
                });
            }
        };

        $scope.buscarSistemasAvaliacao = function() {
            var promise = Servidor.buscar('sistemas-avaliacao');
            promise.then(function(response) {
                $scope.sistemasAvaliacao = response.data;
                $timeout(function() {
                    $('#sistemasAvaliacao').material_select();
                }, 50);
            });
        };

        $scope.buscarPeriodoMedias = function(calendario){
            var promise = Servidor.buscar('periodos', {calendario: calendario.id});
            promise.then(function(response){
                $scope.periodosMedia = response.data;
                $scope.periodosMedia.forEach(function(p){
                    p.dataInicio = dateTime.converterDataForm(p.dataInicio);
                    p.dataTermino = dateTime.converterDataForm(p.dataTermino);
                });
            }, 50);
        };

        $scope.selecionarSistemaAvaliacao = function(sistema) {
            var promise = Servidor.buscarUm('sistemas-avaliacao', sistema);
            promise.then(function(response) {
                $scope.sistemaAvaliacao = response.data;
                $scope.periodosMedia = [];
                for (var i = 0; i < $scope.sistemaAvaliacao.quantidadeMedias; i++) {
                    $scope.periodosMedia.push({
                        sistemaAvaliacao: angular.copy(response.data),
                        media: i+1,
                        dataInicio: '',
                        dataTermino: ''
                    });
                }
                $timeout(function() { $('.date-input').mask('00/00/0000'); }, 150);
            });
        };

        /* Busca os eventos pelo nome*/
        $scope.buscarEventos = function(nome) {
            if (nome.length > 2) {
                var promise = Servidor.buscar('eventos', {'nome': nome});
                promise.then(function(response) {
                    $scope.eventos = response.data;
                });
            }
        };

        /* Busca um mês de um calendário */
        $scope.buscarMes = function (mes) {
            var promise = Servidor.buscar('calendarios/'+$scope.calendario.id+'/meses/'+mes);
            promise.then(function(response) {
                $scope.dias = response.data;
                $scope.preencherDias();
                $scope.colorirDias();
                $scope.inicializarDropdown('.drop-opcoes', false, true, 0, false);
                if ($scope.letivos) { $timeout(function() { $scope.fecharCortina(); }, 500); } else { $scope.contarDiasLetivosEfetivos(); }
            });
        };

        /* Carrega o formulário para criar/editar calendário */
        $scope.carregarFormulario = function(calendario) {
            $scope.abrirCortina();
            if (calendario) {
                $scope.acao = 'Editar';
                $scope.calendario = calendario;
                $scope.calendario.dataInicio = dateTime.converterDataForm(calendario.dataInicio);
                $scope.calendario.dataTermino = dateTime.converterDataForm(calendario.dataTermino);
                if(calendario.calendarioBase === undefined || calendario.calendarioBase === null){
                    $scope.buscarPeriodoMedias(calendario);
                };
            } else {
                $scope.limparCalendario();
                $scope.periodosMedia = [];
                if (!$scope.isAdmin) {
                    if ($scope.instituicoes.length === 1) {
                        $scope.calendario.instituicao = $scope.instituicoes[0];
                    }
                    if ($scope.calendarios.length) {
                        $scope.calendario.calendarioBase = $scope.calendarios[0];
                        $scope.selecionarBase();
                    }                        
                } else {
                    $('.dropdown-input').dropdown({
                        inDuration: 300,
                        outDuration: 225,
                        constrain_width: true,
                        hover: false,
                        gutter: 45,
                        belowOrigin: true,
                        alignment: 'left'
                    });
                }
                $scope.acao = 'Cadastrar';
            }
            $timeout(function() {
                $('#calendarioBase, #instituicao, #sistemasAvaliacao').material_select();
                $('.date-input').mask('00/00/0000');
                Servidor.verificaLabels();
                $scope.editando = true;
                $scope.fecharCortina();
            }, 500);
        };

        $scope.selecionarTipoEvento = function() {
            if ($scope.evento.evento.tipo) {
                $scope.evento.inicio = '00:00';
                $scope.evento.termino = '23:59';
            } else {
                $scope.evento.inicio = '';
                $scope.evento.termino = '';
            }
            $timeout(function() { 
                Servidor.verificaLabels();
            },50);
        };

        /* Carrega os dados de um evento */
        $scope.carregarEvento = function(evento) {
            $scope.abrirCortina();
            $scope.evento.evento = evento;
            $scope.nomeEvento = evento.nome;
            if (evento.tipo === 'FERIADO') {
                $scope.evento.inicio = '00:00';
                $scope.evento.termino = '23:59';
                $('.time').prop('disabled', true);
            } else {
                $('.time').removeProp('disabled');
            }
            $timeout(function() { Servidor.verificaLabels(); $scope.fecharCortina(); }, 250);
        };

        /* Transfere os dias para os dias da semana */
        $scope.preencherDias = function() {
            for (var i = 0; i < $scope.dias.length; i++) {
                for (var j = 0; j < $scope.semanas.length; j++) {
                    if ($scope.dias[i].data === $scope.semanas[j].domingo.data) {
                        $scope.semanas[j].domingo = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].segunda.data) {
                        $scope.semanas[j].segunda = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].terca.data) {
                        $scope.semanas[j].terca = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].quarta.data) {
                        $scope.semanas[j].quarta = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].quinta.data) {
                        $scope.semanas[j].quinta = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].sexta.data) {
                        $scope.semanas[j].sexta = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].sabado.data) {
                        $scope.semanas[j].sabado = $scope.dias[i];
                    }
                }
            }
        };

        /* Colore os dias do calendário */
        $scope.colorirDias = function () {
            var currentData = new Date().toJSON().split('T')[0];
            for (var i = 0; i < $scope.dias.length; i++) {
                var id = $scope.dias[i].data;
                if ($scope.dias[i].efetivo) {
                    $('.dia' + id).find('.dia-de-semana').css({'background-color': '#c8e6c9'});
                } else if ($scope.dias[i].letivo) {
                    $('.dia' + id).find('.dia-de-semana').css({'background-color': '#ffecb3'});
                } else {
                    $('.dia' + id).find('.dia-de-semana').css({'background-color': '#f5f5f5'});
                }
                if ($scope.dias[i].data === currentData) {
                    $('.dia' + id).find('.date-input').css({'color': '#fafafa', 'background-color': '#039be5'});
                }
            }
        };

        /* Altera o letivo/efetivo do dia */
        $scope.carregarDia = function(dia) {
            $scope.dia = dia;
            if (dia.letivo) {
                $('#letivo').prop('checked', true);
                if (dia.efetivo) {
                    $('#efetivo').prop('checked', true);
                } else {
                    $('#efetivo').prop('checked', false);
                }
            } else {
                $('#efetivo').prop('checked', false);
                $('#letivo').prop('checked', false);
            }
        };

        /* Salva os dias */
        $scope.salvarDias = function() {
            for (var i = 0; i < $scope.dias.length; i++) {
                if ($scope.dias[i].id === $scope.dia.id) { $scope.dias[i] = $scope.dia; }
            }
            var dias = {'dias': $scope.dias};
            var result = Servidor.salvarLote(dias, 'calendarios/'+$scope.calendario.id+'/dias', '');
            result.then(function() {
                $scope.fecharCortina();
            });
        };

        /* Modifica o dia letivo/efetivo */
        $scope.atualizarDia = function(efetivo) {
            $scope.abrirCortina();
            if (efetivo) {
                if ($scope.dia.efetivo) {
                    $('.dia'+$scope.dia.data).find('.dia-de-semana').css('background-color', '#ffecb3');
                    $scope.efetivos--;
                    $scope.dia.efetivo = false;
                } else {
                    $('.dia'+$scope.dia.data).find('.dia-de-semana').css('background-color', '#c8e6c9');
                    $('#letivo').prop('checked', true);
                    $scope.efetivos++;
                    $scope.dia.efetivo = true;
                    if (!$scope.dia.letivo) {
                        $scope.dia.letivo = true;
                        $scope.letivos++;
                        $scope.naoLetivos--;
                    }
                }
            } else {
                if ($scope.dia.letivo) {
                    $('.dia'+$scope.dia.data).find('.dia-de-semana').css('background-color','#f5f5f5');
                    $('#efetivo').prop('checked', false);
                    $scope.dia.letivo = false;
                    $scope.letivos--;
                    $scope.naoLetivos++;
                    if ($scope.dia.efetivo) {
                        $scope.efetivos--;
                        $scope.dia.efetivo = false;
                    }
                } else {
                    $('.dia'+$scope.dia.data).find('.dia-de-semana').css('background-color', '#ffecb3');
                    $scope.dia.letivo = true;
                    $scope.letivos++;
                    $scope.naoLetivos--;
                    if ($scope.dia.efetivo) {
                        $scope.dia.efetivo = false;
                        $scope.efetivos--;
                    }
                }
            }
            $scope.salvarDias();
        };

        /* Contador dos dias efetivos, letivos e não letivos */
        $scope.contarDiasLetivosEfetivos = function() {
            var letivos = 0;
            var efetivos = 0;
            var naoLetivos = 0;
            var inicio = $scope.calendario.dataInicio.split('-')[1];
            var termino = $scope.calendario.dataTermino.split('-')[1];
            for (var i = inicio; i <= termino; i++) {
                var promise = Servidor.buscar('calendarios/' + $scope.calendario.id + '/meses/' + i);
                promise.then(function(response) {
                    for (var j = 0; j < response.data.length; j++) {
                        if (response.data[j].letivo) {
                            letivos++;
                            if (response.data[j].efetivo) { efetivos++; }
                        } else {
                            naoLetivos++;
                        }
                    }
                    $scope.letivos = letivos;
                    $scope.efetivos = efetivos;
                    $scope.naoLetivos = naoLetivos;
                    $timeout(function() { 
                        $scope.fecharCortina();
                    }, 500);
                });
            }
        };

        /* Prepara o calendário */
        $scope.baseCalendario = function(mes) {
            $scope.abrirCortina();
            var mesInicio = $scope.calendario.dataInicio.split('-')[1];
            var mesTermino = $scope.calendario.dataTermino.split('-')[1];
            var ano = $scope.calendario.dataInicio.split('-')[0];
            if (!mes && mes !== 0) { mes = mesInicio; };
            if (parseInt(mes) >= parseInt(mesInicio) && parseInt(mes) <= parseInt(mesTermino)) {
                $scope.semanas = [];
                $scope.limparSemana();
                var dia = 1;
                var data = new Date(ano, mes - 1, dia).toJSON().split('T')[0];
                while (parseInt(mes) === parseInt(data.split('-')[1])) {
                    var diaSemana = new Date(ano, mes - 1, dia).toDateString().split(' ')[0];
                    switch(diaSemana) {
                        case 'Sun': $scope.semana.domingo.data = data; break
                        case 'Mon': $scope.semana.segunda.data = data; break
                        case 'Tue': $scope.semana.terca.data = data; break
                        case 'Wed': $scope.semana.quarta.data = data; break
                        case 'Thu': $scope.semana.quinta.data = data; break
                        case 'Fri': $scope.semana.sexta.data = data; break
                        case 'Sat':
                            $scope.semana.sabado.data = data;
                            $scope.semanas.push($scope.semana);
                            $scope.limparSemana();
                        break
                    }
                    dia++;
                    data = new Date(ano, mes - 1, dia).toJSON().split('T')[0];
                }
                if ($scope.semana.domingo.data) {
                    $scope.semanas.push($scope.semana);
                }
                switch(parseInt(mes)) {
                    case 1: $scope.mes.nome = 'JANEIRO'; $scope.mes.num = 1; break
                    case 2: $scope.mes.nome = 'FEVEREIRO'; $scope.mes.num = 2; break
                    case 3: $scope.mes.nome = 'MARÇO'; $scope.mes.num = 3; break
                    case 4: $scope.mes.nome = 'ABRIL'; $scope.mes.num = 4; break
                    case 5: $scope.mes.nome = 'MAIO'; $scope.mes.num = 5; break
                    case 6: $scope.mes.nome = 'JUNHO'; $scope.mes.num = 6; break
                    case 7: $scope.mes.nome = 'JULHO'; $scope.mes.num = 7; break
                    case 8: $scope.mes.nome = 'AGOSTO'; $scope.mes.num = 8; break
                    case 9: $scope.mes.nome = 'SETEMBRO'; $scope.mes.num = 9; break
                    case 10: $scope.mes.nome = 'OUTUBRO'; $scope.mes.num = 10; break
                    case 11: $scope.mes.nome = 'NOVEMBRO'; $scope.mes.num = 11; break
                    case 12: $scope.mes.nome = 'DEZEMBRO'; $scope.mes.num = 12; break
                }
                $scope.buscarMes(mes);
            } else {
                Materialize.toast('Excedeu limite do calendário.', 2500);
                $scope.fecharCortina();
            }
        };

        /* Prepara a página de visualização do calendário */
        $scope.carregarCalendario = function(calendario) {
            var promise = Servidor.buscarUm('calendarios', calendario.id);
            promise.then(function(response) {
                $scope.calendario = response.data;
                $scope.baseCalendario();
                $scope.fecharCortina();
            });
        };

        $scope.verificarCalendarioReplicado = function(calendario) {
            return calendario.id !== $scope.calendario.id && calendario.instituicao.id === $scope.calendario.instituicao.id;
        };

        $scope.selecionarCalendario = function(calendario) {
            if (calendario) {
                var naoAchou = true;
                $scope.calendariosSelecionados.forEach(function(c, i) {
                    if (c.id === calendario.id) {
                        $scope.calendariosSelecionados.splice(i, 1);
                        $('#checkbox-evento-calendario').prop('checked', false);
                        naoAchou = false;
                    }
                });
                if(naoAchou) {
                    $scope.calendariosSelecionados.push(calendario);
                    var bool = true;
                    $('.calendario-checkbox').each(function() {
                        if (!$(this).prop('checked')) { bool = false; }
                    });
                    if (bool) {
                        $('#checkbox-evento-calendario').prop('checked', true   );
                    }                        
                }
            } else {
                $scope.calendariosSelecionados = [$scope.calendario];
                if ($('#checkbox-evento-calendario').prop('checked')) {
                    $('.calendario-checkbox').prop('checked', true);
                    $scope.calendarios.forEach(function(c) {
                        if ($scope.verificarCalendarioReplicado(c)) {
                            $scope.calendariosSelecionados.push(c);
                        }
                    });
                } else {
                    $('.calendario-checkbox').prop('checked', false);
                }
            }
        };

        /* Abre o modal de inserir evento num dia */
        $scope.abrirModalEvento = function() {                                
            if ($scope.calendario.calendarioBase !== undefined && $scope.calendario.calendarioBase.id) {
                $scope.calendariosSelecionados = [$scope.calendario];
            } else {
                var promise = Servidor.buscar('calendarios', {calendarioBase: $scope.calendario.id});
                promise.then(function(response) {
                    $scope.calendariosSelecionados = response.data;
                });
            }
            if ($scope.dia.eventos.length) {
                $scope.editandoEvento = false;
            } else {
                $scope.abrirFormEvento();
            }
            $scope.eventoExtendido = false;
            $scope.eventoReplicado = false;
            $scope.abrirCortina();
            $scope.inicializarDropdown('#evento', true, false, 45, true);
            $('.time').mask('00:00');
            $('.data').mask('00/00/0000');
            $('#btn-fechar-evento').hide();
            $scope.diaDoEvento = dateTime.converterDataForm($scope.dia.data);
            $('.tooltipped').tooltip('remove');
            $timeout(function() {
                Servidor.verificaLabels();
                $('#dia-evento-modal').openModal();
                $scope.fecharCortina();
                $('.tooltipped').tooltip({delay: 50});
                $('#tipo').material_select('');
            }, 500);
        };

        $scope.verificaTipoEvento = function (tipo) {
            if (tipo === 'FERIADO') {
                $scope.evento.inicio = '00:00';
                $scope.evento.termino = '24:00';
            };

        };
        $scope.abrirFormEvento = function (evento) {
            if (evento) {
                $scope.opcaoDeEnvio = 'Editar Evento';
                var promise = Servidor.buscarUm('calendarios/' + $scope.calendario.id + '/dias/' + $scope.dia.id + '/eventos', evento.id);
                promise.then(function (response) {
                    response.data.inicio = dateTime.formatarHorario(response.data.inicio);
                    response.data.termino = dateTime.formatarHorario(response.data.termino);
                    $scope.evento = response.data;
                    var promise = Servidor.buscarUm('eventos', $scope.evento.evento.id);
                    promise.then(function(response) {
                        $scope.evento.evento = response.data;
                        $scope.nomeEvento = response.data.nome;
                        $scope.editandoEvento = true;
                        $timeout(function () {
                            $('#tipo').material_select();
                            Servidor.verificaLabels();
                        }, 50);
                    });
                });
            } else {
                $scope.opcaoDeEnvio = 'Adicionar Evento';
                $scope.limparEvento();
                $scope.evento.dia = $scope.dia;
                $scope.nomeEvento = '';
                $scope.editandoEvento = true;
                $timeout(function () {
                    $('#tipo').material_select('destroy');
                    $('#tipo').material_select('');
                }, 50);
            }
        };

        /* Salva o evento e o inclui no dia escolhido */

        $scope.salvarEvento = function () {
            if ($scope.evento.evento.nome && $scope.evento.evento.tipo) {
                var evento = $scope.evento.evento;
                (evento.id) ? evento.id : delete evento.id;                    
                var promise = Servidor.finalizar(evento, 'eventos', '');
                promise.then(function(response) {
                    $scope.evento.evento = response.data;
                    if (dateTime.validarHorario($scope.evento.inicio) && dateTime.validarHorario($scope.evento.termino)) {
                        $scope.abrirCortina();
                        $scope.evento.inicio = $scope.evento.inicio + ':00';
                        $scope.evento.termino = $scope.evento.termino + ':00';
                        if($scope.calendario.calendarioBase === undefined) { $scope.evento.fixo = true; }
                        /* VERIFICA SE O EVENTO SE EXTENDE */
                        $scope.calendariosSelecionados.forEach(function(calendario) {
                            var promise = Servidor.buscar('calendarios/'+calendario.id+'/meses/'+$scope.mes.num);
                            promise.then(function(response) {
                                var dias = response.data;
                                if ($scope.eventoExtendido) {
                                    $scope.salvarDiaEventoPeriodo(dias, calendario);
                                } else {
                                    dias.forEach(function(d) {
                                        if (d.data === $scope.evento.dia.data) {                                                
                                            var evento = $scope.evento; 
                                            evento.dia = d;
                                            $scope.salvarDiaEvento(evento, calendario);
                                        }
                                    });                                        
                                }
                            });
                        });                                
                    } else {
                        Materialize.toast('Insira um horário válido.', 2500);
                    }
                });
            };
        };

        $scope.salvarDiaEventoPeriodo = function(dias, calendario) {
            var diaInicio = $scope.evento.dia.data.split('-')[2];
            var diaTermino = $scope.evento.dataTermino.split('/')[0];                                
            dias.forEach(function(dia) {
                var d = dia.data.split('-')[2];
                if (dia.letivo && d >= diaInicio && d <= diaTermino) {
                    $scope.salvarDiaEvento({
                        evento: $scope.evento.evento,
                        dia: dia,
                        fixo: $scope.evento.fixo,
                        inicio: $scope.evento.inicio,
                        termino: $scope.evento.termino
                    }, calendario);
                }
            });
        };

        $scope.salvarDiaEvento = function (evento, calendario) {
            var result = Servidor.finalizar(evento, 'calendarios/' + calendario.id + '/dias/' + evento.dia.id + '/eventos', '');
            result.then(function (response) {                        
                if(calendario.id === $scope.calendario.id) {
                    $scope.eventos.push(response.data);                    
                    for (var i = 0; i < $scope.semanas.length; i++) {
                        switch (response.data.dia.id) {
                            case $scope.semanas[i].domingo.id:
                                $scope.semanas[i].domingo.eventos.push(response.data);
                                break
                            case $scope.semanas[i].segunda.id:
                                $scope.semanas[i].segunda.eventos.push(response.data);
                                break
                            case $scope.semanas[i].terca.id:
                                $scope.semanas[i].terca.eventos.push(response.data);
                                break
                            case $scope.semanas[i].quarta.id:
                                $scope.semanas[i].quarta.eventos.push(response.data);
                                break
                            case $scope.semanas[i].quinta.id:
                                $scope.semanas[i].quinta.eventos.push(response.data);
                                break
                            case $scope.semanas[i].sexta.id:
                                $scope.semanas[i].sexta.eventos.push(response.data);
                                break
                            case $scope.semanas[i].sabado.id:
                                $scope.semanas[i].sabado.eventos.push(response.data);
                                break
                        }
                    }
                    $scope.editandoEvento = false;
                    if (evento.dia.letivo && evento.evento.tipo === 'FERIADO' || evento.evento.tipo === 'RECESSO') {
                        $scope.dia = evento.dia;
                        $scope.atualizarDia(false);
                    } else {
                        $scope.fecharCortina();
                    };
                }                        
            });
        };

        $scope.buscarInstituicoesNome = function(nome) {
            if (nome.length > 3) {
                var promise = Servidor.buscar('instituicoes', {nome: nome});
                promise.then(function(response) {
                    var instituicoes = response.data;
                    var promise = Servidor.buscar('unidades-ensino', {nome: nome});
                    promise.then(function(response) {
                        var unidades = response.data;
                        $scope.instituicoes = unidades;
                        instituicoes.forEach(function(instituicao) {
                            $scope.instituicoes.push(instituicao);
                        });
                    });
                });
            }
        };

        $scope.selecionarInstituicao = function(instituicao) {
            $scope.nomeInstituicao = instituicao.nome;
            $scope.calendario.instituicao = instituicao;
            Servidor.verificaLabels();
        };

        $scope.selecionarBase = function(calendarioBase) {
            if (calendarioBase) { $scope.calendario.calendarioBase.id = calendarioBase; }
            $scope.sistemaAvaliacao = {id: null};
            $scope.periodosMedia = [];
            $scope.calendariosBases.forEach(function(c) {
                if (parseInt($scope.calendario.calendarioBase.id) === parseInt(c.id)) {
                    $scope.calendarioBase = c;
                }
            });
            if($scope.calendario.calendarioBase !== undefined) {
                $scope.calendario.dataInicio = dateTime.converterDataForm($scope.calendarioBase.dataInicio);
                $scope.calendario.dataTermino = dateTime.converterDataForm($scope.calendarioBase.dataTermino);
                $scope.calendario.nome = $scope.calendarioBase.nome + ' - ' + $scope.calendario.instituicao.tipo.sigla + ' ' + $scope.calendario.instituicao.nome;
                $timeout(function(){ $('#sistemasAvaliacao').material_select(); 
                    Servidor.verificaLabels(); 
                }, 50);  
            }                    
        };

        /* Verifica as condições para efetuar o cadastro de um calendário */
        $scope.prepararFinalizar = function(calendario) {
            if($scope.calendario.nome && $scope.calendario.instituicao.id) {
                var inicio = $scope.calendario.dataInicio;
                var termino = $scope.calendario.dataTermino;
                if(parseInt(inicio.split('/')[2]) !== parseInt(termino.split('/')[2])) {
                    Materialize.toast('O ano das datas devem ser o mesmo.', 3000);
                } else {
                    if (inicio !== termino) {
                        if (dateTime.validarDataAgendamento(termino, inicio)) {
                            if($scope.calendario.calendarioBase !== undefined && $scope.calendario.calendarioBase !== null && $scope.calendario.calendarioBase.id) {
                                var passeInicio = dateTime.validarDataAgendamento($scope.calendario.dataInicio, $scope.calendarioBase.dataInicio);
                                var passeTermino = dateTime.validarDataAgendamento($scope.calendarioBase.dataTermino, $scope.calendario.dataTermino);
                                if(passeInicio && passeTermino) {
                                    $scope.finalizar(calendario);                           
                                } else {
                                    Materialize.toast('As datas devem coincidir com as datas do calendário base.', 3000);
                                }
                            } else {
                                if ($scope.validarPeriodosMedia()) {
                                    $scope.finalizar(calendario);
                                } 
                            }
                        } else { Materialize.toast('Data de término deve ser maior que a data de início.', 3000); }
                    } else { Materialize.toast('As datas do calendário devem ser diferentes.', 3000); }
                }
            } else {
                Materialize.toast('Preencha os campos obrigatorios.', 3000);
            }
        };

        $scope.validarPeriodosMedia = function() {
            var retorno = true;
            var d = $scope.calendario.dataInicio.split('/');
            var dataInicio = new Date(parseInt(d[2]), d[1]-1, d[0]-1).toJSON().split('T')[0];
            $scope.periodosMedia.forEach(function(p, i) {
                if(dateTime.dateLessOrEqual(dateTime.converterDataServidor(p.dataInicio), dataInicio)) {
                    Servidor.customToast('Data da ' + (i+1) + 'ª Média inválida.');
                    retorno = false;
                }
                if (dateTime.dateLessThan(dateTime.converterDataServidor($scope.calendario.dataTermino), dateTime.converterDataServidor(p.dataTermino))) {
                    Servidor.customToast('Data da ' + (i+1) + 'ª Média inválida.');
                    retorno = false;
                }
                if (dateTime.dateLessOrEqual(dateTime.converterDataServidor(p.dataTermino), dateTime.converterDataServidor(p.dataInicio))) {                        
                    Servidor.customToast('Data da ' + (i+1) + 'ª Média inválida.');
                    retorno = false;
                }
                dataInicio = dateTime.converterDataServidor(p.dataTermino);
            });
            return retorno;                
        };

        /* Salva o calendário */
        $scope.finalizar = function(calendario) {
            $scope.abrirCortina();
            calendario.dataInicio = dateTime.converterDataServidor($scope.calendario.dataInicio);
            calendario.dataTermino = dateTime.converterDataServidor($scope.calendario.dataTermino);9
            calendario.instituicao = {id: calendario.instituicao.id};
            if (calendario.calendarioBase !== undefined && calendario.calendarioBase !== null && calendario.calendarioBase.id)  {
                calendario.calendarioBase = {id: calendario.calendarioBase.id};
            }
            var result = Servidor.finalizar(calendario, 'calendarios', 'Calendário');
            result.then(function (response) {
                if (calendario.calendarioBase) {
                    $scope.fecharFormulario();
                    $scope.carregarCalendario(response.data);
                } else {
                    $scope.salvarPeriodosMedia(response.data);
                }                    
            });
        };

        $scope.salvarPeriodosMedia = function(calendario) {
            $scope.requisicoes = 0;
            $scope.periodosMedia.forEach(function(periodo) {
                periodo.calendario = {id:calendario.id};
                periodo.sistemaAvaliacao = {id: periodo.sistemaAvaliacao.id};
                $scope.requisicoes++;
                periodo.dataInicio = dateTime.converterDataServidor(periodo.dataInicio);
                periodo.dataTermino = dateTime.converterDataServidor(periodo.dataTermino);
                var promise = Servidor.finalizar(periodo, 'periodos', '');
                promise.then(function(response) {
                    if (--$scope.requisicoes === 0) {                            
                        $scope.fecharFormulario();
                        $scope.carregarCalendario(calendario);                           
                    }
                });
            });
        };

        /* Verifica se o usuário deseja descartar os dados preenchidos */
        $scope.prepararRemover = function (calendario) {
            $scope.calendario = calendario;
            $('#remove-modal-calendario').openModal();
        };

        /* Exclui um calendário */
        $scope.remover = function() {
            $scope.abrirCortina();
            Servidor.remover($scope.calendario, 'Calendario');
            $timeout(function() {
                $scope.fecharFormulario();
                $scope.fecharCortina();
            }, 1000);
        };

        $scope.prepararRemoverEvento = function(evento, dia) {
            $scope.evento = evento;
            $scope.evento.dia = dia;            
            $('#remove-modal-evento-dia').openModal();
        };

        /* Desvincula um evento à um dia */
        $scope.removerEvento = function(evento, dia) {
            $scope.abrirCortina();            
            var promise = Servidor.buscarUm('calendarios/'+$scope.calendario.id+'/dias/'+dia.id+'/eventos/'+evento.id);
            promise.then(function(response) {
                var evento = response.data;
                Servidor.remover(evento, 'Evento');
                $scope.limparEvento();
                for (var i = 0; i < $scope.semanas.length; i++) {
                    switch (dia.data) {
                        case $scope.semanas[i].domingo.data:
                            for (var j = 0; j < $scope.semanas[i].domingo.eventos.length; j++) {
                                if ($scope.semanas[i].domingo.eventos[j].id === evento.id) {
                                    $scope.semanas[i].domingo.eventos.splice(j, 1);
                                    $timeout(function() { $scope.fecharCortina(); }, 250);
                                }
                            }
                        break
                        case $scope.semanas[i].segunda.data:
                            for (var j = 0; j < $scope.semanas[i].segunda.eventos.length; j++) {
                                if ($scope.semanas[i].segunda.eventos[j].id === evento.id) {
                                    $scope.semanas[i].segunda.eventos.splice(j, 1);
                                    $timeout(function() { $scope.fecharCortina(); }, 250);
                                }
                            }
                        break
                        case $scope.semanas[i].terca.data:
                            for (var j = 0; j < $scope.semanas[i].terca.eventos.length; j++) {
                                if ($scope.semanas[i].terca.eventos[j].id === evento.id) {
                                    $scope.semanas[i].terca.eventos.splice(j, 1);
                                    $timeout(function() { $scope.fecharCortina(); }, 250);
                                }
                            }
                        break
                        case $scope.semanas[i].quarta.data:
                            for (var j = 0; j < $scope.semanas[i].quarta.eventos.length; j++) {
                                if ($scope.semanas[i].quarta.eventos[j].id === evento.id) {
                                    $scope.semanas[i].quarta.eventos.splice(j, 1);
                                    $timeout(function() { $scope.fecharCortina(); }, 250);
                                }
                            }
                        break
                        case $scope.semanas[i].quinta.data:
                            for (var j = 0; j < $scope.semanas[i].domingo.quinta.length; j++) {
                                if ($scope.semanas[i].quinta.eventos[j].id === evento.id) {
                                    $scope.semanas[i].quinta.eventos.splice(j, 1);
                                    $timeout(function() { $scope.fecharCortina(); }, 250);
                                }
                            }
                        break
                        case $scope.semanas[i].sexta.data:
                            for (var j = 0; j < $scope.semanas[i].sexta.eventos.length; j++) {
                                if ($scope.semanas[i].sexta.eventos[j].id === evento.id) {
                                    $scope.semanas[i].sexta.eventos.splice(j, 1);
                                    $timeout(function() { $scope.fecharCortina(); }, 250);
                                }
                            }
                        break
                        case $scope.semanas[i].sabado.data:
                            for (var j = 0; j < $scope.semanas[i].sabado.eventos.length; j++) {
                                if ($scope.semanas[i].sabado.eventos[j].id === evento.id) {
                                    $scope.semanas[i].sabado.eventos.splice(j, 1);
                                    $timeout(function() { $scope.fecharCortina(); }, 250);
                                }
                            }
                        break
                    }
                }
            });                
        };

        /* Abre o modal de exclusão */
        $scope.prepararVoltar = function(calendario) {
            if (calendario.nome && !calendario.id) {
                $('#modal-certeza').openModal();
            } else {
                $scope.fecharFormulario();
            }
        };

        /* Reseta as variáveis e volta para a lista*/
        $scope.fecharFormulario = function() {
            $scope.letivos = 0;
            $scope.efetivos = 0;
            $scope.naoLetivos = 0;
            $scope.semanas = [];
            $scope.limparCalendario();
            $scope.buscarCalendarios();
            $scope.editando = false;
        };

        /* Inicializa o dropdown */
        $scope.inicializarDropdown = function (query, constrainWidth, hover, gutter, belowOrigin) {
            $(query).dropdown({
                inDuration: 300,
                outDuration: 225,
                constrain_width: constrainWidth,
                hover: hover,
                gutter: gutter,
                belowOrigin: belowOrigin,
                alignment: 'right'
            });
        };

        $scope.buscarCalendarios();
        $scope.buscarSistemasAvaliacao();
    }]);
})();