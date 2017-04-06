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
    var turmaQuadroHorarioModule = angular.module('turmaQuadroHorarioModule', ['servidorModule', 'turmaDirectives', 'erudioConfig', 'dateTimeModule']);
    //DEFINIÇÃO DO CONTROLADOR
    turmaQuadroHorarioModule.controller('TurmaQuadroHorarioController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$routeParams', 'dateTime', '$compile', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $routeParams, dateTime, $compile) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.isAdmin = Servidor.verificaAdmin(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('TURMA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateCustom('turmas','quadro-horario');
        //ATRIBUTOS
        $scope.enturmacoes = []; $scope.progresso = false; $scope.cortina = false; $scope.titulo = "Grade de horários"; $scope.mostraAlunos = true; $scope.removerDisc = false;
        $scope.aulasCriadas = false; $scope.segunda = []; $scope.terca = []; $scope.quarta = []; $scope.quinta = []; $scope.quadroBtn = true; $scope.selecionados = false; $scope.horariosATrocar = [];
        $scope.sexta = []; $scope.horariosDisciplina = []; $scope.horariosDisciplinaParaExcluir = []; $scope.mostraQuadroHorario = false; $scope.repeteVazio = ['','','',''];
        $scope.quadroHorario = { 'nome': null, 'inicio': null, 'modelo': {id: null}, 'unidadeEnsino': {id: null}, 'turno': {id: null}, 'diasSemana': [] };
        $scope.frequencia = { 'turma': {id: null}, 'disciplina': {id: null}, 'aula': {id: null} }; $scope.ocupado = false;
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //PREPARA VOLTAR
        $scope.prepararVoltar = function (objeto) { window.location = $scope.config.dominio+'/#/turmas/' + $routeParams.id; };
        //MOSTRA LABELS MENU FAB
        $scope.mostrarLabels = function () { $('.toolchip').fadeToggle(250); };
        //ABRE DISCIPLINAS
        $scope.abreDisciplinas = function (horario) { console.log($scope.removerDisc); if (!$scope.aulaGerada && !$scope.removerDisc) { $scope.removerDisc=false; $('.disciplinas-list').show(); $('.cortina-disciplinas').show(); } else if ($scope.aulaGerada) { $scope.disciplinaToggle(horario); } };
        //FECHA DISCIPLINAS
        $scope.fechaDisciplinas = function () { $('.disciplinas-list').hide(); $('.cortina-disciplinas').hide(); };
        //REMOVE HORARIOS A TROCAR
        $scope.removeHorarioATrocar = function (horarioId) { for (var i=0;i<$scope.horariosATrocar.length;i++) { if (horarioId === $scope.horariosATrocar[i].id) { $scope.horariosATrocar.splice(i,1); } } };
        //IS AULA GERADA
        $scope.isAulaGerada = function (){ if ($scope.turma.status === 'EM_ANDAMENTO') { $scope.aulaGerada = true; } else if ($scope.turma.status === 'CRIADO') { $scope.aulaGerada = false; } else { $scope.aulaGerada = null; } };
        //PREPARA SWAP
        $scope.preparaSwap = function () { $('#troca-data-modal').modal('open'); $('#dataNovaGrade').focus(); };
        //ORDENA ARRAYS DE AULAS
        $scope.ordenaAulas = function (a,b) { if (a.horario.inicio < b.horario.inicio) { return -1; } if (a.horario.inicio > b.horario.inicio) { return 1; } return 0; };
        //ID DO HORARIO
        $scope.idHorario = function ($index,horarioDisc) { return ($index !== 0)?horarioDisc.id:'horario'+horarioDisc.id; };
        //BUSCA HELPER ID
        $scope.buscaHelperId = function (index,horarioDisc) { return (index > 0)?'helper'+horarioDisc.id:''; };
        
        //QUADRO HORARIO TURMA
        $scope.quadroHorarioTurma = function (turma) {
            $scope.mostraProgresso(); $scope.isAulaGerada();
            if ($scope.aulaGerada) {
                var promise = Servidor.buscar('horarios-disciplinas',{'turma':turma.id}); 
                promise.then(function(response){ if (response.data.length > 0) { $scope.horarios = response.data; $scope.buscarUmQuadroHorarioGerado($scope.turma.quadroHorario.id); } });
            } else { $scope.horarios = []; $scope.buscarUmQuadroHorario($scope.turma.quadroHorario.id); $scope.buscarDisciplinasOfertadas('quadro'); }
            $timeout(function () { $('.date').mask('00/00/0009'); }, 500);
        };
        
        //BUSCAR QUADRO HORARIO DE TURMA GERADA
        $scope.buscarUmQuadroHorarioGerado = function (id) {
            $scope.horarios.forEach(function (horario) { horario.horario.inicio = Servidor.formatarHora(horario.horario.inicio); horario.horario.termino = Servidor.formatarHora(horario.horario.termino); });
            var promise = Servidor.buscarUm('quadro-horarios', id);
            promise.then(function (response) {
                $scope.quadroHorario = response.data; $scope.quadroHorario.inicio = Servidor.formatarHora($scope.quadroHorario.inicio);
                $scope.organizaHorariosPorDia(); $scope.organizaHorariosPorSemana(); $scope.fechaProgresso();
            });
        };

        //BUSCAR QUADRO HORARIO
        $scope.buscarUmQuadroHorario = function (id) {
            var promise = Servidor.buscarUm('quadro-horarios', id);
            promise.then(function (response) {
                $scope.quadroHorario = response.data; $scope.quadroHorario.inicio = Servidor.formatarHora($scope.quadroHorario.inicio);
                $scope.naoResetados = true; $scope.buscarHorarios();  $scope.fechaProgresso();
            });
        };
        
        //BUSCAR DISCIPLINAS OFERTADAS
        $scope.buscarDisciplinasOfertadas = function (op) {
            $scope.frequencia.turma.id = $scope.turma.id;
            var promise = Servidor.buscar('disciplinas-ofertadas', {'turma': $scope.turma.id});
            promise.then(function (response) {
                $scope.disciplinasOfertadas = response.data;
                if ($scope.disciplinasOfertadas.length === 0) { $scope.reiniciarSemanas(); $scope.buscarHorarios(); }
                if (op === 'quadro') {
                    $timeout(function () {
                        $('#dForm').material_select('destroy'); $('#dForm').material_select(); $scope.buscarHorariosDisciplinas();
                    }, 500);
                }
            });
        };
        
        //BUSCAR HORARIOS
        $scope.buscarHorarios = function () {
            var promise = Servidor.buscar('quadros-horarios/' + $scope.quadroHorario.id + '/horarios', null);
            promise.then(function (response) {
                $scope.horarios = response.data;
                $scope.horarios.forEach(function (horario) { horario.inicio = Servidor.formatarHora(horario.inicio); horario.termino = Servidor.formatarHora(horario.termino); });
                $scope.organizaHorariosPorDia(); $scope.organizaHorariosPorSemana();
            });
        };
        
        //ATIVAR DRAG AND DROP
        $scope.ativarDragAndDrop = function () {
            $timeout(function () {
                $(".horario").disableSelection();
                if ($scope.aulaGerada === false) {
                    $(".disciplina").draggable({ appendTo: "body", helper: "clone" });
                    $(".horario").droppable({
                        activeClass: "ui-state-default", hoverClass: "ui-state-hover", accept: ":not(.ui-sortable-helper)", stack:'div',
                        drop: function (event, ui) {
                            $scope.horariosDisciplina.forEach(function (horario) { if (horario.horario.id === parseInt(event.target.id)) { $scope.ocupado = true; } });
                            if ($scope.ocupado) { Materialize.toast("Horario já possui uma disciplina", 1000); $scope.ocupado = false;
                            } else {
                                $scope.cont++; var str = $(ui.draggable.context).html(); str = str.replace(/\s\s+/g,"");
                                var object = { 'disciplina': {'id': parseInt(ui.draggable.context.id)}, 'horario': {'id': parseInt(event.target.id)} };
                                var elem = $('<div>',{id: "disciplina"+object.disciplina.id, class: "valign hd"+event.target.id}); elem.css('width','100%').css('z-index','5','important');
                                $('#'+event.target.id).html(''); elem.html(str).appendTo('#'+event.target.id);
                                var elemRemove = $('<i>',{id:'remove'+parseInt(ui.draggable.context.id), class: 'material-icons absolute tiny dom-created disabled remove-disc remove-grid-disciplina'+event.target.id});
                                elemRemove.css('top','3px').css('right','3px'); elemRemove.html('clear'); $('#'+event.target.id).append(elemRemove);
                                $('.dom-created').unbind('click').click(function(){ $scope.removerDisciplina(event.target.id); });
                                $scope.mostraProgresso(); var promise = Servidor.finalizar(object, 'horarios-disciplinas', '');
                                promise.then(function (response) { $scope.horariosDisciplina.push(response.data); $scope.excluirDisciplina = true; $scope.fechaProgresso(); });
                            }
                        }
                    });
                    $('.sortable').css('position','relative'); $scope.isDragging = false;
                    var height = $(window).height(); height = height-150; $('.disciplina-wrapper').css('height',height+'px');
                    $('.grabbable').mousedown(function(){ $(this).mousemove(function(){ $('.disciplinas-list').mouseout(function(){ $scope.fechaDisciplinas(); $('.grabbable').unbind('mousemove'); $('.disciplinas-list').unbind('mouseout'); }); }); });
                }                    
            }, 500);
        };
        
        //ATIVA A TROCA DE DISCIPLINAS
        $scope.disciplinaToggle = function (horario) {
            var cont = $('.item-selecionado').length+1;
            if (cont===1) {
                if ($('.h'+horario.id).hasClass('item-selecionado')) { $scope.removeHorarioATrocar(horario.id); $('.h'+horario.id).removeClass('item-selecionado relative z-depth-5').css('background','').css('color',''); $scope.selecionados = false;
                } else { $('.h'+horario.id).addClass('item-selecionado relative z-depth-5').css('background','#11aef4').css('color','#fafafa'); $scope.selecionados = false; $scope.horariosATrocar.push(horario); }
            } else if (cont===2) {
                if ($('.h'+horario.id).hasClass('item-selecionado')) { $scope.removeHorarioATrocar(horario.id); $('.h'+horario.id).removeClass('item-selecionado relative z-depth-5').css('background','').css('color',''); $scope.selecionados = false;
                } else { $('.h'+horario.id).addClass('item-selecionado relative z-depth-5').css('background','#11aef4').css('color','#fafafa'); $scope.selecionados = true; $scope.horariosATrocar.push(horario); }
            } else {
                if ($('.h'+horario.id).hasClass('item-selecionado')) { $scope.selecionados = false; $scope.removeHorarioATrocar(horario.id); }
                $('.h'+horario.id).removeClass('item-selecionado relative z-depth-5').css('background','').css('color',''); 
            }
        };
        
        //TROCA ITENS DA GRADE HORARIO
        $scope.trocarDatas = function () {
            $scope.mostraProgresso(); var origem = $scope.horariosATrocar[0]; var destino = $scope.horariosATrocar[1];
            origem.horario.inicio += ":00"; origem.horario.termino += ":00"; destino.horario.inicio += ":00"; destino.horario.termino += ":00";
            var arrSelecionados = $('.item-selecionado'); console.log($(arrSelecionados[0]));
            var helper1 = $(arrSelecionados[0]).attr('data-drag-helper'); $("#helper"+helper1).html('').append($(arrSelecionados[1])); 
            var helper2 = $(arrSelecionados[1]).attr('data-drag-helper'); $("#helper"+helper2).html('').append($(arrSelecionados[0])); 
            if ($('#dataNovaGrade').val() !== null && $('#dataNovaGrade').val() !== undefined && $('#dataNovaGrade').val() !== '') {                
                var url = 'troca?dataInicio=' + dateTime.converterDataServidor($('#dataNovaGrade').val()); var promise = Servidor.customPutFinalizar(origem,destino,url,'Aulas');
                promise.then(function(response){ if (response.status === 204 || response.status === 200) { $scope.fechaProgresso(); $scope.horariosATrocar=[]; $('.table-label').removeClass('item-selecionado relative z-depth-5').css('background','').css('color',''); $scope.selecionados = false; $('#troca-data-modal').modal('close'); } });
            } else { $scope.fechaProgresso(); Servidor.customToast('Digite uma data válida.'); $('#dataNovaGrade').focus(); }
        };
        
        //BUSCAR HORARIOS DISCIPLINA
        $scope.buscarHorariosDisciplinas = function () {
            $scope.cont = 0; $scope.novaGrade = false; $scope.botaoFlag = true; $scope.quadroCompleto = false;        
            $scope.horarios.forEach(function (horario, i) { $scope.cont++;
                $scope.disciplinasOfertadas.forEach(function (disciplina, j) {
                    var promise = Servidor.buscar('horarios-disciplinas', {'horario': horario.id, 'disciplina': disciplina.id});
                    promise.then(function (response) {
                        if (response.data.length > 0) {
                            horario.disciplina = response.data[0]; $scope.horariosDisciplina.push(response.data[0]);
                            if (response.data[0].id && $scope.cont === $scope.horarios.length) {
                                $scope.aulasCriadas = true; $scope.botaoFlag = false; $scope.quadroCompleto = true; 
                            }
                            if ($scope.cont > 0) { $scope.novaGrade = true; } $scope.fechaProgresso();
                            $timeout(function(){ $scope.ativarDragAndDrop(); },150);
                        }
                        if ($scope.cont === $scope.horarios.length) {
                            $scope.botaoFlag = false; $scope.excluirDisciplina = false; $scope.quadroCompleto = true;
                            if ($scope.cont > 0) { $scope.novaGrade = true; } $scope.fechaProgresso(); $timeout(function(){ $scope.ativarDragAndDrop(); },150);
                        }
                        if (j === $scope.disciplinasOfertadas.length-1 && i === $scope.horarios.length-1) { $timeout(function(){ $scope.fechaProgresso(); }, 500); }
                    });
                });
            });
        };
       
        //ORGANIZAR POR DIA - HORARIOS
        $scope.organizaHorariosPorDia = function () {
            $scope.horarios.forEach(function (horarioDisc) {
                if ($scope.aulaGerada) {
                    switch (horarioDisc.horario.diaSemana.diaSemana) { case "2": $scope.segunda.push(horarioDisc); break; case "3": $scope.terca.push(horarioDisc); break; case "4": $scope.quarta.push(horarioDisc); break; case "5": $scope.quinta.push(horarioDisc); break; case "6": $scope.sexta.push(horarioDisc); break; }
                } else {
                    switch (horarioDisc.diaSemana.diaSemana) { case "2": $scope.segunda.push(horarioDisc); break; case "3": $scope.terca.push(horarioDisc); break; case "4": $scope.quarta.push(horarioDisc); break; case "5": $scope.quinta.push(horarioDisc); break; case "6": $scope.sexta.push(horarioDisc); break; }
                }
            }); $scope.fechaProgresso();
        };
        
        //ORGANIZA HORARIOS POR SEMANA
        $scope.organizaHorariosPorSemana = function () {
            var semanas = $scope.segunda.length; $scope.semanas = [];
            if ($scope.aulaGerada) { $scope.segunda.sort($scope.ordenaAulas); $scope.terca.sort($scope.ordenaAulas); $scope.quarta.sort($scope.ordenaAulas); $scope.quinta.sort($scope.ordenaAulas); $scope.sexta.sort($scope.ordenaAulas); }
            $timeout(function(){
                for (var i=0; i<semanas; i++) {
                    var semana = []; var horas = $scope.segunda[i];
                    semana.push(horas); semana.push($scope.segunda[i]); semana.push($scope.terca[i]); semana.push($scope.quarta[i]); semana.push($scope.quinta[i]); semana.push($scope.sexta[i]);
                    $scope.semanas.push(semana);
                }
            },100);
        };
        
        //REMOVER DISCIPLINA
        $scope.removerDisciplina = function (horarioDisciplina) {
            $scope.cont--; $scope.mostraProgresso(); $scope.removerDisc = true;
            if (horarioDisciplina.id !== undefined && horarioDisciplina.id) { $('.hd'+horarioDisciplina.id).remove(); } else { $('.hd'+horarioDisciplina).remove(); }
            if (horarioDisciplina.id) {
                $scope.horarioDisciplinaRemover = null; $scope.horariosDisciplina.forEach(function (horario, i) { if (parseInt(horario.horario.id) === horarioDisciplina.id) { $scope.horarioDisciplinaRemover = $scope.horariosDisciplina.splice(i, 1); } });
                $('.remove-grid-disciplina'+horarioDisciplina.id).remove(); Servidor.remover($scope.horarioDisciplinaRemover[0],''); $scope.fechaProgresso(); $timeout(function(){ $scope.removerDisc = false; },50);
            } else {
                $('.remove-grid-disciplina'+horarioDisciplina).remove(); $scope.horarioDisciplinaRemover = null;
                $scope.horariosDisciplina.forEach(function (horario, i) { if (parseInt(horario.horario.id) === parseInt(horarioDisciplina)) { $scope.horarioDisciplinaRemover = $scope.horariosDisciplina.splice(i, 1); } });
                Servidor.remover($scope.horarioDisciplinaRemover[0],''); $scope.fechaProgresso(); $timeout(function(){ $scope.removerDisc = false; },50);
            }
        };

        //VERIFICA DATA INICIAL
        $scope.verificarDataInicial = function () {
            var aulas = $('.validate-aulas').length; var horariosDisciplinas = $scope.horariosDisciplina.length;
            if (aulas === horariosDisciplinas) { $scope.gerarAulas(); } else { Servidor.customToast('Complete a grade de horários antes de gerar as aulas'); }
        };
                                
        //GERAR AULAS
        $scope.gerarAulas = function () {
            $scope.mostraProgresso(true); var promise = Servidor.finalizar(null, 'turmas/' + $scope.turma.id + '/aulas', 'AULAS');
            promise.then(function (response) { $scope.fechaProgresso(); window.location.reload(true); });
        };
        
        //DATA FINAL PRESENCAS
        $scope.dataFinalPresencas = function () {
            var frequenciasDisciplinas = [];
            $scope.disciplinasOfertadas.forEach(function(ofertada, i) {
                var promise = Servidor.buscar('frequencias', {disciplina: ofertada.id});
                promise.then(function(response) {
                    frequenciasDisciplinas.push(response.data);
                    if (i === $scope.disciplinasOfertadas.length-1) { var dataFinal = $scope.verificaUltimaDataQuadroHorario(frequenciasDisciplinas); }
                });
            });
        };
        
        //VERIFICAR PRESENCAS
        $scope.verificaPresencas = function() {
            if ($scope.dataInicioQuadroHorario.length === 10) {
                $scope.mostraProgresso(); var frequenciasDisciplinas = [];
                $scope.disciplinasOfertadas.forEach(function(ofertada, i) {
                    var promise = Servidor.buscar('frequencias', {disciplina: ofertada.id, turma: $scope.turma.id});
                    promise.then(function(response) {
                        frequenciasDisciplinas.push(response.data);
                        if (i === $scope.disciplinasOfertadas.length-1) { $scope.verificaMaiorDataPresenca(frequenciasDisciplinas); }
                    });
                });
            } else {
                Servidor.customToast('Esta data não está disponível para efetuar chamada.');
            }
        };
        
        //VERIFICA DATA QUADRO
        $scope.verificaUltimaDataQuadroHorario = function(disciplinas) {
            var maior = '0000-00-00';
            disciplinas.forEach(function(d) {
                d.forEach(function(f) {
                    if (dateTime.dateLessThan(maior, f.aula.dia.data)) { maior = f.aula.dia.data; }
                });
            });
            maior = maior.split('-'); var dia = parseInt(maior[2])+1; var mes = maior[1]; var ano = maior[0];
            if (parseInt(dia) < 10) { dia = '0'+dia; } return ano + '-' + mes + '-' + dia;
        };
        
        //VERIFICA MAIOR DATA
        $scope.verificaMaiorDataPresenca = function(disciplinas) {
            var dataInicio = dateTime.converterDataServidor($scope.dataInicioQuadroHorario); var maiorData = '0000-00-00';
            disciplinas.forEach(function(frequencias) {
                frequencias.forEach(function(frequencia) {
                    if (dateTime.dateLessOrEqual(dataInicio, frequencia.aula.dia.data)) {
                        if (dateTime.dateLessOrEqual(maiorData, frequencia.aula.dia.data)) { maiorData = frequencia.aula.dia.data; }
                    }
                });
            });
            var arrayData = maiorData.split('-'); var dia = parseInt(arrayData[2])+1; if (dia < 10) { dia = '0'+dia; } maiorData = arrayData[0] + '-' + arrayData[1] + '-' + dia;
            if (dateTime.dateLessOrEqual(dataInicio, maiorData)) {
                maiorData = dateTime.converterDataForm(maiorData); $scope.maiorData = maiorData;
                Servidor.customToast('Devido as frequencias geradas, a data de início da nova grade deverá ser ' + maiorData); $scope.fechaProgresso();
            } else { $scope.removerGradeHorario(); }
        };
        
        //REMOVER GRADE HORARIO
        $scope.removerGradeHorario = function() {
            $scope.aulas = []; var inicio = dateTime.converterDataServidor($scope.dataInicioQuadroHorario);
            var promise = Servidor.buscar('turmas/'+$scope.turma.id+'/aulas');
            promise.then(function(response) {
                var aulas = response.data;
                if (aulas.length > 0) {
                    aulas.forEach(function(aula, i) {
                        if (dateTime.dateLessOrEqual(inicio, aula.dia.data)) { $scope.aulas.push(aula.id); }
                        if (aulas.length-1 === i) {
                            $timeout(function () {
                                if ($scope.aulas.length > 0) { Servidor.excluirLote({'ids': $scope.aulas }, 'aulas'); } $scope.removerHorariosDisciplinas();
                            }, 1000);
                        }
                    });
                } else { $scope.removerHorariosDisciplinas(); }
            });
        };
        
        //VERIFICAR HORARIO DISCIPLINAS
        $scope.removerHorariosDisciplinas = function() {
            $scope.disciplinasGradeAntiga = [];
            for (var i=0; i<$scope.segunda.length; i++) { if ($scope.segunda[i].disciplina !== undefined) { $scope.disciplinasGradeAntiga.push($scope.segunda[i].disciplina.id); } }
            for (var i=0; i<$scope.terca.length; i++) { if ($scope.terca[i].disciplina !== undefined) { $scope.disciplinasGradeAntiga.push($scope.terca[i].disciplina.id); } }
            for (var i=0; i<$scope.quarta.length; i++) { if ($scope.quarta[i].disciplina !== undefined) { $scope.disciplinasGradeAntiga.push($scope.quarta[i].disciplina.id); } }
            for (var i=0; i<$scope.quinta.length; i++) { if ($scope.quinta[i].disciplina !== undefined) { $scope.disciplinasGradeAntiga.push($scope.quinta[i].disciplina.id); } }
            for (var i=0; i<$scope.sexta.length; i++) { if ($scope.sexta[i].disciplina !== undefined) { $scope.disciplinasGradeAntiga.push($scope.sexta[i].disciplina.id); } }
            $timeout(function () { $scope.removerLoteDisciplinas(); }, 1500);
        };
        
        //REMOVER DISCIPLINAS
        $scope.removerLoteDisciplinas = function() {
            if ($scope.disciplinasGradeAntiga.length === 0) {
                var inicioQuadro = dateTime.converterDataServidor($scope.dataInicioQuadroHorario); $('#remove-modal-quadro-horario').modal('close');
                $scope.prepararNovaGradeHorario(); $scope.gerarNovasAulas(inicioQuadro); $scope.fechaProgresso();
            } else {
                for (var i=0; i<$scope.disciplinasGradeAntiga.length; i++) {
                    if ($scope.disciplinasGradeAntiga[i] !== undefined) { Servidor.excluirLote({'ids': $scope.disciplinasGradeAntiga }, 'horarios-disciplinas'); };
                    var inicioQuadro = dateTime.converterDataServidor($scope.dataInicioQuadroHorario);
                    if ($scope.disciplinasGradeAntiga.length-1 === i) {
                        $('#remove-modal-quadro-horario').modal('close');
                        $scope.prepararNovaGradeHorario(); $scope.gerarNovasAulas(inicioQuadro); $scope.fechaProgresso();
                    }
                }
            }
        };
        
        //PREPARA NOVA GRADE
        $scope.prepararNovaGradeHorario = function() {
            $scope.reiniciarSemanas(); $scope.horariosDisciplina = [];
            $scope.horariosDisciplinaParaExcluir = []; $scope.buscarUmQuadroHorario($scope.turma.quadroHorario.id); $scope.ativarDragAndDrop();
        };
        
        //ABRIR MODAL EXCLUSAO
        $scope.abrirModalExclusaoGradeHorario = function() {
            $('#remove-modal-quadro-horario').modal('open'); $scope.dataInicioQuadroHorario = '';
            //$scope.horariosDisciplina = []; $scope.reiniciarSemanas(); $scope.buscarUmQuadroHorario($scope.turma.quadroHorario.id); $scope.ativarDragAndDrop();
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.title-module').html($scope.titulo); $('.material-tooltip').remove(); var promise = Servidor.buscarUm('turmas',$routeParams.id);
            promise.then(function(response){
                $scope.turma = response.data;
                $timeout(function () {  $('.tooltipped').tooltip({delay: 50}); Servidor.entradaPagina(); $('.cortina-disciplinas').click(function (){ $scope.fechaDisciplinas(); }); $scope.quadroHorarioTurma($scope.turma); }, 1000);
            });
        };

        $scope.inicializar();
    }]);
})();