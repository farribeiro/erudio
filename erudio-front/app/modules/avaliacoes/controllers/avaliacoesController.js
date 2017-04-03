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
    var avaliacaoModule = angular.module('avaliacaoModule', ['servidorModule', 'avaliacoesDirectives']);

    avaliacaoModule.service('AvaliacaoService', [function() {
        this.abrirFormulario = false;
        this.curso = {};
        this.abreForm = function() {
            this.abrirFormulario = true;
        };
        this.fechaForm = function() {
            this.abrirFormulario = false;
        };
    }]);

    avaliacaoModule.controller('AvaliacaoController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', '$filter', 'AvaliacaoService', 'dateTime', function ($scope, Servidor, Restangular, $timeout, $templateCache, $filter, AvaliacaoService, dateTime) {
        $templateCache.removeAll();
        
        $scope.escrita = Servidor.verificaEscrita('AVALIACAO');
        $scope.isAdmin = Servidor.verificaAdmin();
        $scope.professor = sessionStorage.getItem('disciplina');
        $scope.cursos = [];
        $scope.etapas = [];
        $scope.disciplinas = [];
        $scope.sistemas = [];
        $scope.tipos = [];
        $scope.avaliacoes = [];
        $scope.semanas = [];
        $scope.aulas = [];
        $scope.ofertadas = [];
        $scope.horarios = [];
        $scope.editando = false;
        $scope.tipoAvaliacaoSet = false;
        $scope.tipoAvaliacao = '';
        $scope.checkAll = null;
        $scope.acao = '';
        $scope.endereco = '';
        $scope.requisicoes = 0;
        $scope.alunosAvaliados = 0;

        $scope.progresso = false;
        function mostrarProgresso () { $scope.progresso = true; };
        function esconderProgresso () { $scope.progresso = false; };
        $scope.$watch("requisicoes", function (requisicoes) {
            if(requisicoes) { mostrarProgresso(); } else { esconderProgresso(); }
        });

        $scope.mes = {
            'nome': '',
            'num': null
        };
        $scope.avaliacao = {
            'nome': '',
            'disciplina': {'id': null},
            'aulaEntrega': {'id': null},
            'tipo': {'id': null},
            'media': null,
            'habilidades': []
        };
        $scope.semana = {
            'domingo': { 'dia': {'data': null}, 'horario': {'id': null} },
            'segunda': { 'dia': {'data': null}, 'horario': {'id': null} },
            'terca': { 'dia': {'data': null}, 'horario': {'id': null} },
            'quarta': { 'dia': {'data': null}, 'horario': {'id': null} },
            'quinta': { 'dia': {'data': null}, 'horario': {'id': null} },
            'sexta': { 'dia': {'data': null}, 'horario': {'id': null} },
            'sabado': { 'dia': {'data': null}, 'horario': {'id': null} }
        };
        $scope.disciplina = { 'id': null };
        $scope.curso = { 'id': null };
        $scope.etapa = { 'id': null };
        $scope.turma = { 'id': null };
        $scope.sistema = { 'id': null };
        $scope.busca = { 'nome': '', 'media': '' };

        /* Preparar remover */
        $scope.prepararRemover = function(avaliacao){
            $scope.avaliacaoRemover = avaliacao;
            $scope.endereco = avaliacao.route.split('-')[1];
            var promise = Servidor.buscar('notas-' + $scope.endereco, {'avaliacao': avaliacao.id});
            promise.then(function(response) {
                var notas = response.data;
                var mediaFechada = false;
                notas.forEach(function(n) {
                    if(n.media.valor !== undefined && n.media.valor) {
                        mediaFechada = true;
                    }
                });
                if(mediaFechada) {
                    return Servidor.customToast('Não é possível remover. A média desta avaliação está fechada.');                    
                } else {
                    return $('#modal-remover-avaliacao').modal();
                }
            });
//            if(!avaliacao.fechamentoMedia){
//                var promise = Servidor.buscar('notas-' + avaliacao.route.split('-')[1], {'avaliacao': avaliacao.id});
//                promise.then(function (response) {
//                    if (response.data.length) {
//                        Materialize.toast(avaliacao.nome + ' possui notas, portanto não pode excluí-la.', 5000);
//                    } else {
//                        $scope.avaliacaoRemover = avaliacao;
//                        $('#modal-remover-avaliacao').modal();
//                    }
//                });
//            } else{
//                Servidor.customToast('Esta avaliação não pode ser excluida pois a media ja foi fechada');
//            }
        };

        /* Remover avaliação */
        $scope.excluirAvaliacao = function () {
            Servidor.remover($scope.avaliacaoRemover, 'Avaliação');
            $scope.avaliacoes.forEach(function(a, i) {
                if(a.id === $scope.avaliacaoRemover.id) {
                    $scope.avaliacoes.splice(i, 1);
                }
            });
        };

        /* Reseta o objeto de avaliação */
        $scope.limparAvaliacao = function() {
            if($scope.avaliacao.disciplina.id) {
                var disciplina = $scope.avaliacao.disciplina.id;
            } else {
                disciplina = '';
            }
            $scope.avaliacao = {
                'nome': '',
                'disciplina': {'id': disciplina},
                'aulaEntrega': {'id': null},
                'tipo': {'id': null},
                'media': null,
                'habilidades': []
            };
        };

        /* Reseta o objeto de semana */
        $scope.limparSemana = function() {
            $scope.semana = {
                'domingo': { 'dia': {'data': null}, 'horario': {'id': null} },
                'segunda': { 'dia': {'data': null}, 'horario': {'id': null} },
                'terca': { 'dia': {'data': null}, 'horario': {'id': null} },
                'quarta': { 'dia': {'data': null}, 'horario': {'id': null} },
                'quinta': { 'dia': {'data': null}, 'horario': {'id': null} },
                'sexta': { 'dia': {'data': null}, 'horario': {'id': null} },
                'sabado': { 'dia': {'data': null}, 'horario': {'id': null} }
            };
        };

        /* Verifica se o usuário desejar descartar os dados preenchidos */
        $scope.prepararVoltar = function (avaliacao) {
            if ($scope.avaliacao.nome && !$scope.avaliacao.id) {
                $('#modal-certeza-avaliacoes').modal();
            } else {
                $scope.fecharFormulario();
            }
        };

        /* Verifica se veio de outro módulo e abre o formulário */
        $scope.$watch("AvaliacaoService", function() {
            if (AvaliacaoService.abrirFormulario) {
                $scope.limparAvaliacao();
                $scope.acao = 'Cadastrar';
                $scope.calendarioHorarios();
                $scope.prepararEdicaoAvaliacao(sessionStorage.getItem('disciplina'));
            }
        });

        /* Verifica os selects */
        $scope.verificaSelectCurso = function(id) {
            if ($scope.curso.id) {
                var curso = $scope.curso.id;
                if (parseInt(curso) === parseInt(id)){ return true; } else { return false; }
            }
        };

        $scope.verificaSelectEtapa = function(id) {
            var etapa = $scope.etapa.id;
            if (parseInt(etapa) === parseInt(id)){ return true; } else { return false; }
        };

        $scope.verificaSelectTurma = function(id) {
            var turma = $scope.turma.id;
            if (parseInt(turma) === parseInt(id)){ return true; } else { return false; }
        };

        $scope.verificaSelectDisciplina = function(id) {
            if($scope.avaliacao.disciplina.id) {
                var disciplina = $scope.avaliacao.disciplina.id;
                if (parseInt(disciplina) === parseInt(id)){ return true; } else { return false; }
            }
        };

        /* Reseta as variáveis de busca */
        $scope.limparBusca = function() {
            $scope.busca = { nome: '', media: '' };
            if (!sessionStorage.getItem('disciplina')) {
                $scope.avaliacao.disciplina.id = '';
                $scope.endereco = '';
                $scope.curso.id = '';
                $scope.etapa.id = '';
                $scope.turma.id = '';
                $scope.sistema.id = '';
            }
            $timeout(function() {
                $('select').material_select('destroy');
                $('select').material_select();
            }, 50);
        };

        /* Monta um select */
        $scope.select = function (id) {
            $timeout(function () {
                $('#' + id).material_select('destroy');
                $('#' + id).material_select();
            }, 500);
        };

        /*  Reseta  camps que estam preenchidos*/
        $scope.resetaSelects = function(curso, etapa, turma, disciplina, form) {
            if (!sessionStorage.getItem('disciplina')) {
                var seletor = '';
                if (form) {
                    if (curso && $scope.curso.id) { seletor += '#curso ,'; $scope.curso.id = null; }
                    if (curso || etapa && $scope.etapa.id) { seletor += '#etapa, '; $scope.etapa.id = null; $scope.etapas = []; }
                    if (curso || etapa || turma && $scope.turma.id) { seletor += '#turma, '; $scope.turma.id = null; $scope.turmas = [];}
                    if (curso || etapa || turma || disciplina) {
                        seletor+= '#disciplinas'; $scope.avaliacao.disciplina.id = null;
                        $scope.avaliacao.aulaEntrega.id = null;
                        $scope.ofertadas = [];
                        $scope.aulas = [];
                        $scope.horarios = [];
                        $scope.calendarioHorarios();
                    }
                } else {
                    if (curso && $scope.curso.id) { seletor += '#cursoAvaliacao ,'; $scope.curso.id = null; }
                    if (curso || etapa && $scope.etapa.id) { seletor += '#etapaAvaliacao, '; $scope.etapas = []; }
                    if (curso || etapa || turma && $scope.turma.id) { seletor += '#turmaAvaliacao, '; $scope.turmas = [];}
                    if (curso || etapa || turma || disciplina && $scope.disciplinas.length) {
                        seletor+= '#disciplinaAvaliacao';
                        $scope.avaliacao.disciplina.id = null;
                        $scope.ofertadas = [];
                    }
                }
                if (seletor) {
                    $timeout(function() {
                        $(seletor).material_select('destroy');
                        $(seletor).material_select();
                    }, 150);
                }
            }
        };

        /* Prepara o formulário */
        $scope.carregar = function(avaliacao, modal) {
            $scope.checkAll = false;
            $('#nomeAvaliacao').focus();
            if (avaliacao) {
                $scope.avaliacao = avaliacao;
                $scope.acao = 'Editar';
                $scope.buscarUmaAvaliacao(avaliacao.id, modal);
            } else {
                $scope.acao = 'Cadastrar';
                if (AvaliacaoService.abrirFormulario && $scope.turma.id && $scope.avaliacao.disciplina.id) {
                    $scope.selecionaDisciplina();
                    $scope.buscarHorarios($scope.avaliacao.disciplina.id);
                    $scope.buscarHabilidades();
                } else { $scope.limparAvaliacao(); }
                $scope.calendarioHorarios();
                $timeout(function() {
                    $('#sistemaAvaliacao, #tipoAvaliacao, #curso, #etapa, #turma, #disciplinas, #tipo').material_select('destroy');
                    $('#sistemaAvaliacao, #tipoAvaliacao, #curso, #etapa, #turma, #disciplinas, #tipo').material_select();
                    $('#nomeAvaliacao').focus();
                    Servidor.inputNumero();
                    $scope.editando = true;
                }, 150);
            }
        };

        /* Fecha o formulário */
        $scope.fecharFormulario = function() {
            $scope.limparSemana();
            $scope.aulas = [];
            $scope.semanas = [];
            $scope.disciplinas = [];
            $scope.habilidades = [];
            $scope.enturmacoes = [];
            $scope.notas = [];
            $scope.editando = false;
            $scope.avaliando = false;
            $scope.alunosAvaliados = 0;
            if ($scope.avaliacoes.length) { $scope.buscarAvaliacoes(); }
            AvaliacaoService.fechaForm();
        };

        $scope.verificarAvaliacoesMesmoDia = function(turma, dia) {
            var promise = Servidor.buscar("avaliacoes-"+$scope.endereco, {turma: turma.id, dia: dia.id});
            promise.then(function(response) {
                var avaliacoes = response.data;
                if(avaliacoes.lenth >= 2) {
                    if($scope.avaliacao.id !== undefined && $scope.avaliacao.id) {
                        avaliacoes.forEach(function(a) {
                            if(a.id === $scope.avaliacao.id && avaliacoes.length === 2) {
                                return $scope.finalizar();
                            }
                        });
                    }
                    return Servidor.customToast('Não pode haver mais que duas avaliações num mesmo dia.');
                }
                return $scope.finalizar();
            });
        };

        /* Busca um sistema de avaliação */
        $scope.buscarUmSistema = function(sistemaId){
            $scope.requisicoes++;
            var promise = Servidor.buscarUm('sistemas-avaliacao', sistemaId);
            promise.then(function(response){
                $scope.sistema = response.data;
                if(parseInt($scope.avaliacao.media) > $scope.sistema.quantidadeMedias){
                    $scope.avaliacao.media = $scope.sistema.quantidadeMedias;
                }
                if($scope.sistema.tipo === 'QUANTITATIVO'){
                    $scope.endereco = 'quantitativas';
                }else{
                    $scope.endereco = 'qualitativas';
                }
                $scope.requisicoes--;
            });
        };

        $scope.salvarNotasQuantitativas = function(enturmacoes) {
            enturmacoes.forEach(function(e) {
                if(!e.nota.id) {
                    e.nota.valor = e.nota.valor.toString().replace(',', '.');
                    e.nota.valor = parseFloat(e.nota.valor);
                    if(e.nota.valor || e.nota.valor === 0) {                        
                        $scope.requisicoes++;
                        delete e.nota.id;
                        e.nota.media = {id: e.matricula.media.id};
                        e.nota.avaliacao = {id: $scope.avaliacao.id};
                        var promise = Servidor.finalizar(e.nota, 'notas-quantitativas', '');
                        promise.then(function(response) {
                            $scope.alunosAvaliados++;
                            e.nota.id = response.data.id;
                            if(--$scope.requisicoes === 0) {
                                Servidor.customToast('Notas salvas com sucesso.');
                            }
                        });
                    } else {
                        Servidor.customToast('Nota de ' + e.matricula.aluno.nome + ' inválida.');
                    }
                }
            });
        };

        $scope.salvarNotasQualitativas = function(enturmacao) {
            var avaliadas = 0;
            enturmacao.nota.habilidadesAvaliadas.forEach(function(h) {
                if(h.conceito.id) { avaliadas++; }
            });
            if(avaliadas === enturmacao.nota.habilidadesAvaliadas.length) {
                var nota = {
                    media: {id: enturmacao.matricula.media.id},
                    avaliacao: {id: $scope.avaliacao.id},
                    habilidadesAvaliadas: enturmacao.nota.habilidadesAvaliadas
                };
                $scope.requisicoes++;
                var promise = Servidor.finalizar(nota, 'notas-qualitativas', 'Nota de ' + enturmacao.matricula.aluno.nome);
                promise.then(function(response) {
                    enturmacao.nota = response.data;
                    $timeout(function(){ $('.enturmacao'+enturmacao.id).material_select(); },250);
                    $scope.requisicoes--;
                });
            }
        };

        $scope.carregarFormAvaliar = function(avaliacao) {
            $scope.buscarEnturmacoes(avaliacao.disciplina.turma);
            $scope.buscarNotas(avaliacao);
            $scope.buscarDisciplinasCursadas(avaliacao.disciplina.turma, avaliacao.disciplina.disciplina);
            $scope.requisicoes++;
            var promise = Servidor.buscarUm('avaliacoes-'+$scope.endereco, avaliacao.id);
            promise.then(function(response) {
                $scope.avaliacao = response.data;
                alocarDadosEnturmacoes();
            });
        };

        $scope.buscarDisciplinasCursadas = function(turma, disciplina) {
            $scope.requisicoes++;
            var promise = Servidor.buscar('disciplinas-cursadas', {turma: turma.id, disciplina: disciplina.id});
            promise.then(function(response) {
                $scope.cursadas = response.data;
                $scope.requisicoes--;
                $scope.cursadas.forEach(function(c) {
                    $scope.requisicoes++;
                    var promise = Servidor.buscar('medias', {disciplinaCursada: c.id});
                    promise.then(function(response) {
                        c.medias = response.data;
                        alocarDadosEnturmacoes();
                    });
                });
            });
        };

        /* Busca as enturmacoes */
        $scope.buscarEnturmacoes = function(turma) {
            $scope.requisicoes++;
            var promise = Servidor.buscar('enturmacoes', {turma: turma.id, encerrado: 0});
            promise.then(function(response) {
                $scope.enturmacoes = response.data;
                alocarDadosEnturmacoes();
            });
        };

        /* Busca as notas */
        $scope.buscarNotas = function(avaliacao) {
            $scope.requisicoes++;
            var promise = Servidor.buscar('notas-'+$scope.endereco, {avaliacao: avaliacao.id});
            promise.then(function(response) {
                $scope.notas = response.data;
                alocarDadosEnturmacoes();
            });
        };

        $scope.buscarConceitos = function() {
            $scope.requisicoes++;
            var promise = Servidor.buscar('avaliacoes-qualitativas/conceitos', null);
            promise.then(function(response) {
                $scope.conceitos = response.data;
                $timeout(function() { $('select').material_select(); $scope.requisicoes--; }, 250);
            });
        };

        var alocarDadosEnturmacoes = function() {
            if(--$scope.requisicoes === 0) {
                if($scope.enturmacoes.length) {
                    if($scope.endereco === 'quantitativas') {
                        $scope.alocarNotasQuantitativasEnturmacoes();
                    } else {
                        $scope.alocarNotasQualitativasEnturmacoes();
                        $scope.buscarConceitos();
                        $('.collapsible').collapsible();
                    }
                    $scope.alocarMediasEnturmacoes();
                    $scope.avaliando = true;
                } else {
                    Servidor.customToast('Não há alunos na turma ' + $scope.avaliacao.disciplina.turma.nomeCompleto + '.');
                }
            }
        };

        $scope.alocarMediasEnturmacoes = function() {
            $scope.enturmacoes.forEach(function(e) {
                $scope.cursadas.forEach(function(c, j) {
                    if(c.matricula.id === e.matricula.id) {
                        c.medias.forEach(function(m) {
                            if(m.numero === $scope.avaliacao.media) {
                                e.matricula.media = m;
                            }
                        });
                        $scope.cursadas.splice(j, 1);
                    }
                });
            });
        };

        $scope.alocarNotasQualitativasEnturmacoes = function() {
            $scope.enturmacoes.forEach(function(e) {
                e.nota = { habilidadesAvaliadas: [] };
                if($scope.notas.length) {
                    $scope.notas.forEach(function(n, j) {
                        if(n.media.disciplinaCursada.matricula.id === e.matricula.id) {
                            e.nota = n;
                            $scope.notas.splice(j, 1);
                        }
                    });
                }
                if(!e.nota.habilidadesAvaliadas.length) {
                    $scope.avaliacao.habilidades.forEach(function(h) {
                        e.nota.habilidadesAvaliadas.push({
                            habilidade: h,
                            conceito: {id: null}
                        });
                    });
                }
            });
        };

        /* Alocar notas nas enturmacoes */
        $scope.alocarNotasQuantitativasEnturmacoes = function() {
            $scope.enturmacoes.forEach(function(e, i) {
                $scope.notas.forEach(function(n, j) {
                    if(e.matricula.id === n.media.disciplinaCursada.matricula.id) {
                        $scope.enturmacoes[i].nota = n;
                        $scope.alunosAvaliados++;
                        $scope.notas.splice(j, 1);
                    }
                });
                if(e.nota === undefined) {
                    e.nota = {
                        id: null,
                        valor: null,
                        avaliacao: {id: $scope.avaliacao}
                    };
                }
            });
            $('.nota').keypress(function(event) {                
                var tecla = (window.event) ? event.keyCode : event.which;
                if(tecla !== 8 && $(this).val().length > 3) {
                    return false;
                }
                if (tecla > 47 && tecla < 58) {
                    return true;
                } else {
                    if (tecla === 8 || tecla === 0 || tecla === 44) {
                        return true;
                    } else {
                        return false;
                    }
                }
            });
//            $('.nota').mask('00.00', {reverse: true});
        };

        /* Busca os cursos */
        $scope.buscarCursos = function() {
            var promise = Servidor.buscar('cursos', null);
            promise.then(function(response) {
                $scope.cursos = response.data;
                if (sessionStorage.getItem('disciplina')) {
                    $scope.cursos = Servidor.isolarElemento(response.data, $scope.curso.id);
                }
                $scope.select('cursoAvaliacao');
            });
        };

        /* Busca as etapas de um curso */
        $scope.buscarEtapas = function(id) {
            var promise = Servidor.buscar('etapas', {'curso': id, 'unidade': sessionStorage.getItem('unidade')});
            promise.then(function(response) {
                $scope.etapas = response.data;
                if (sessionStorage.getItem('disciplina')) {
                    $scope.etapas = Servidor.isolarElemento(response.data, $scope.etapa.id);
                }
                if ($scope.editando) {
                    $scope.select('etapa');
                } else {
                    $scope.select('etapaAvaliacao');
                }
            });
        };

        /* Busca as turmas de uma etapa */
        $scope.buscarTurmas = function(id) {
            var promise = Servidor.buscar('turmas', {'etapa': id, 'unidadeEnsino': sessionStorage.getItem('unidade')});
            promise.then(function(response) {
                $scope.turmas = response.data;
                if (sessionStorage.getItem('disciplina')) {
                    $scope.turmas = Servidor.isolarElemento(response.data, $scope.turma.id);
                }
                if ($scope.editando) {
                    $scope.select('turma');
                } else {
                    $scope.select('turmaAvaliacao');
                }
            });
        };

        /* Busca as disciplinas de uma turma */
        $scope.buscarDisciplinas = function(turmaId) {
            var promise = Servidor.buscar('disciplinas-ofertadas', {'turma': turmaId});
            promise.then(function(response) {
                $scope.ofertadas = response.data;
                if (sessionStorage.getItem('disciplina')) {
                    $scope.ofertadas = Servidor.isolarElemento(response.data, sessionStorage.getItem('disciplina'));
                }
                if ($scope.editando) { $scope.select('disciplinas'); } else { $scope.select('disciplinaAvaliacao'); }
            });
        };

        $scope.buscarUmaDisciplinaOfertada = function(id) {
            var promise = Servidor.buscarUm('disciplinas-ofertadas', id);
            promise.then(function(response) {
                $scope.avaliacao.disciplina.disciplina = response.data.disciplina;
                $scope.buscarHabilidades();
            });
        };

        $scope.removerHabilidades = function(){
            var promise = Servidor.buscar('notas-qualitativas', {'avaliacao': $scope.avaliacao.id});
            promise.then(function(response){
                 var notas = response.data;
                 $scope.habilidades.forEach(function(h){
                     if(!$('#hab'+h.id)[0].checked){
                         notas.forEach(function(n){
                            n.habilidadesAvaliadas.forEach(function(ah,i){
                               if(ah.habilidade.id === h.id){
                                   n.habilidadesAvaliadas.splice(i,1);
                                   Servidor.finalizar(n, 'notas-qualitativas', '');
                               }
                            });
                         });
                     }
                 });
               });
        };

        /* Busca as habilidades de uma disciplina em determinada média */
        $scope.buscarHabilidades = function() {
            if ($scope.avaliacao.disciplina.disciplina !== undefined && $scope.avaliacao.disciplina.disciplina.id) {
                if ($scope.sistema.tipo === 'QUALITATIVO') {
                    var params = {
                        'disciplina': $scope.avaliacao.disciplina.disciplina.id,
                        'media': $scope.avaliacao.media
                    };
                    if ($scope.avaliacao.media > 0 && $scope.avaliacao.media <= $scope.sistema.quantidadeMedias) {
                        var promise = Servidor.buscar('avaliacoes-qualitativas/habilidades', params);
                        promise.then(function(response) {
                            $scope.habilidades = response.data;
                            $timeout(function() { $('.FMTooltip').tooltip({delay: 50}); }, 100);
                            if ($scope.habilidades.length) {
                                if ($scope.avaliacao.id) {
                                    var cont = $scope.habilidades.length;
                                    $scope.avaliacao.habilidades.forEach(function(aH) {
                                        $scope.habilidades.forEach(function(h) {
                                            if (aH.id === h.id) {
                                                cont--;
                                                $timeout(function() {
                                                    $('#hab'+h.id)[0].checked = true;
                                                }, 100);
                                            }
                                        });
                                    });
                                    if (cont === 0) { $scope.checkAll = true; }
                                } else {
                                    $scope.avaliacao.habilidades = [];
                                }
                            } else {
                                Materialize.toast('Nenhuma habilidade foi encontrada.', 1500);
                            }
                        });
                    } else {
                        Materialize.toast('Insira uma média válida.', 2500);
                        $scope.habilidades = [];
                    }
                }
            } else {
                $scope.buscarUmaDisciplinaOfertada($scope.avaliacao.disciplina.id);
            }
        };

        /* Busca os horários das aulas de uma disciplina*/
        $scope.buscarHorarios = function(id) {
            var promise = Servidor.buscar('horarios-disciplinas', {'disciplina': id});
            promise.then(function(response) {
                $scope.horarios = response.data;
                if (!$scope.horarios.length) { Materialize.toast('Nenhum horário foi encontrado.', 2000); }
            });
        };

        /* Busca os sistemas de avaliação */
        $scope.buscarSistemas = function() {
            var promise = Servidor.buscar('sistemas-avaliacao', null);
            promise.then(function(response) {
                $scope.sistemas = response.data;
            });
        };

        /* Busca os tipos de avaliações */
        $scope.buscarTipos = function() {
            var promise = Servidor.buscar('avaliacoes/tipos', null);
            promise.then(function(response) {
                $scope.tipos = response.data;
            });
        };

        /* Carrega os dados para os inputs/selects/calendario */
        $scope.prepararEdicaoAvaliacao = function(avaliacaoId) {
            Servidor.verificaLabels();
            var promise = Servidor.buscarUm('disciplinas-ofertadas', avaliacaoId);
            promise.then(function(response) {
                $scope.avaliacao.disciplina = response.data;
                $scope.buscarDisciplinas(response.data.turma.id);
                promise = Servidor.buscarUm('turmas', response.data.turma.id);
                promise.then(function(response) {
                    $scope.turma = response.data;
                    $scope.curso.id = response.data.etapa.curso.id;
                    $scope.etapa = response.data.etapa;
                    $scope.buscarEtapas(response.data.etapa.curso.id);
                    $scope.buscarTurmas(response.data.etapa.id);
                    promise = Servidor.buscarUm('etapas', response.data.etapa.id);
                    promise.then(function(response) {
                        $scope.etapa.sistemaAvaliacao = {id: response.data.sistemaAvaliacao.id};
                        if (response.data.sistemaAvaliacao.tipo === 'QUALITATIVO') {
                            $scope.buscarUmaDisciplinaOfertada($scope.avaliacao.disciplina.id);
                        }
                        $scope.buscarUmSistema(response.data.sistemaAvaliacao.id);
                        $scope.buscarCursos();
                        $scope.buscarHorarios($scope.avaliacao.disciplina.id);
                        if($scope.avaliacao.aulaEntrega.dia !== undefined) {
                            var mes = $scope.avaliacao.aulaEntrega.dia.data.split('-')[1];
                        } else {
                            mes = new Date().getMonth();
                        }
                        $scope.calendarioHorarios(mes);
                        $timeout(function() {
                            $('#sistemaAvaliacao, #tipo, #curso, #etapa, #turma, #disciplinas').material_select('destroy');
                            $('#sistemaAvaliacao, #tipo, #curso, #etapa, #turma, #disciplinas').material_select();
                            $('#nomeAvaliacao').focus();
                            Servidor.inputNumero();
                            $scope.editando = true;
                        }, 150);
                    });
                });
            });
        };

        $scope.verificaMediaFechada = function() {
            $scope.requisicoes = 0;
            $scope.avaliacao.temMedia = false;
            var promise = Servidor.buscarUm('disciplinas-ofertadas', $scope.avaliacao.disciplina.id);
            promise.then(function(response) {
                var promise = Servidor.buscar('disciplinas-cursadas', {disciplina: response.data.disciplina.id, status: 'CURSANDO'});
                promise.then(function(response) {
                    var disciplinasCursadas = response.data;
                    if (response.data.length) {
                        disciplinasCursadas.forEach(function(dc) {
                            $scope.requisicoes++;
                            var promise = Servidor.buscar('medias', {disciplinaCursada: dc.id});
                            promise.then(function(response) {
                                $scope.requisicoes--;
                                var medias = response.data;
                                medias.forEach(function(m, i) {
                                    if (m.valor !== undefined && m.valor && parseInt(m.numero) === $scope.avaliacao.media) {
                                        m.notas.forEach(function(n) {
                                            if(n.avaliacao.id === $scope.avaliacao.id) {
                                                $scope.avaliacao.temMedia = true;
                                            }
                                        });
                                    }
                                    if ($scope.requisicoes === 0 && i === medias.length-1) {
                                        if ($scope.avaliacao.temMedia) {
                                            Servidor.customToast('Já possui média fechada para esta avaliação.');
                                        } else {
                                            $scope.prepararEdicaoAvaliacao($scope.avaliacao.disciplina.id);
                                        }
                                    }
                                });
                            });
                        });
                    } else {
                        $scope.prepararEdicaoAvaliacao($scope.avaliacao.disciplina.id);
                    }
                });
            });
        };

        /* Busca uma avaliação */
        $scope.buscarUmaAvaliacao = function(id, modal) {
            var promise = Servidor.buscarUm('avaliacoes-' + $scope.endereco, id);
            promise.then(function(response) {
                $scope.avaliacao = response.data;
                if (modal) {
                    $scope.avaliacao.aulaEntrega.horario.inicio = $scope.avaliacao.aulaEntrega.horario.inicio.split(':')[0] + ':' + $scope.avaliacao.aulaEntrega.horario.inicio.split(':')[1];
                    $scope.avaliacao.aulaEntrega.horario.termino = $scope.avaliacao.aulaEntrega.horario.termino.split(':')[0] + ':' + $scope.avaliacao.aulaEntrega.horario.termino.split(':')[1];
                    var promise = Servidor.buscarUm('turmas', $scope.avaliacao.disciplina.turma.id);
                    promise.then(function(response) {
                        $scope.avaliacao.disciplina.turma = response.data;
                        $('#info-modal-avaliacao').modal();
                    });
                } else if ($scope.escrita) {
                    $scope.verificaMediaFechada();                    
                } else {
                    Servidor.customToast('Você não possui permissão para este tipo de acesso.');
                }
            });
        };

        /* Busca avaliações de acordo com nome, disciplina e média */
        $scope.buscarAvaliacoes = function() {
            if($scope.ofertadas.length && $scope.avaliacao.disciplina.id || $scope.busca.nome && $scope.endereco) {
                var params = {
                    'nome': $scope.busca.nome,
                    'disciplina': $scope.avaliacao.disciplina.id,
                    'media': $scope.busca.media
                };
                if (params.media && params.media < 0 && params.media > $scope.sistema.quantidadeMedias) {
                    Materialize.toast('Média Inválida.', 1500);
                } else {
                    var promise = Servidor.buscar('avaliacoes-' + $scope.endereco, params);
                    promise.then(function(response) {
                        if (response.data.length) {
                            $scope.avaliacoes = response.data;
                            $('.tooltipped').tooltip('remove');
                            $timeout(function() {
                                $('.tooltipped').tooltip({delay: 50});
                                window.scrollTo(0, 600);
                            }, 150);
                        } else {
                            $scope.avaliacoes = [];
                            Materialize.toast('Nenhuma avaliação encontrada.', 2500);
                        }
                    });
                }
            } else { Materialize.toast('Selecione ao menos uma disciplina para realizar a busca.', 4000); }
        };

        /* Salva uma avaliação */
        $scope.verificaFinalizar = function () {
            if ($scope.avaliacao.media) {
                if ($scope.avaliacao.aulaEntrega.id) {
                    if ($scope.avaliacao.nome && $scope.avaliacao.tipo.id && $scope.avaliacao.disciplina.id) {
                        if ($scope.endereco === 'qualitativas') {
                            if ($scope.avaliacao.habilidades.length) {
                                if ($scope.avaliacao.habilidades.length === $scope.habilidades.length) {
                                    $scope.avaliacao.fechamentoMedia = true;
                                    $scope.verificarAvaliacoesMesmoDia($scope.turma, $scope.avaliacao.aulaEntrega.dia);
                                } else {
                                    $scope.avaliacao.fechamentoMedia = false;
                                    if ($scope.avaliacao.id) {
                                        $scope.removerHabilidades();
                                    } else {
                                        $('#modal-fechamento-media').modal();
                                        return false;
                                    }
                                }
                            } else {
                                return Materialize.toast('Selecione ao menos uma habilidade.', 2500);
                            }
                        } else {
                            $scope.verificarAvaliacoesMesmoDia($scope.turma, $scope.avaliacao.aulaEntrega.dia);
                        }
                    } else {
                        Materialize.toast('Preencha os campos obrigatórios.', 2000);
                    }
                } else {
                    Materialize.toast('Escolha a data da aula de entrega.', 2000);
                }
            } else {
                Materialize.toast('Média inválida.', 2000);
            }
        };

        $scope.finalizar = function(){
            delete $scope.avaliacao.disciplina.professores;
            var promise = Servidor.finalizar($scope.avaliacao, 'avaliacoes-' + $scope.endereco, 'Avaliação');
            promise.then(function (response) {
                $scope.limparAvaliacao();
                $scope.avaliacao.disciplina.id = response.data.disciplina.id;
                $scope.avaliacao.media = response.data.media;
                $scope.fecharFormulario();
            });
        };

        /* Seleciona a etapa para pegar o tipo de sistema de avaliacao */
        $scope.carregarEtapa = function(etapaId) {
            if (!etapaId) { etapaId = $scope.etapa.id; }
            var promise = Servidor.buscarUm('etapas', etapaId);
            promise.then(function(response) {
                $scope.buscarUmSistema(response.data.sistemaAvaliacao.id);
            });
        };

        /* Corrigir bug de select */
        $scope.selecionaDisciplina = function () {
            var disciplina = null;
            for (var i = 0; i < $scope.ofertadas.length; i++) {
                if ($scope.ofertadas[i].id === parseInt($scope.avaliacao.disciplina.id)) {
                    disciplina = $scope.ofertadas[i];
                }
            }
            if (disciplina) {
                $scope.disciplina = disciplina.disciplina;
                $scope.avaliacao.disciplina.id = disciplina.id;
            }
        };

        /* Corrigir bug de select */
        $scope.selecionaCurso = function (id) {
            for (var i = 0; i < $scope.cursos.length; i++) {
                if ($scope.cursos[i].id === parseInt(id)) {
                    $scope.curso = $scope.cursos[i];
                    $scope.buscarEtapas($scope.curso.id);
                    return 0;
                }
            }
        };

        /* Corrigir bug de select */
        $scope.selecionaEtapa = function (id) {
            for (var i = 0; i < $scope.etapas.length; i++) {
                if ($scope.etapas[i].id === parseInt(id)) {
                    $scope.etapa = $scope.etapas[i];
                    $scope.buscarDisciplinas('', $scope.etapa.id);
                }
            }
        };

        /* Inicializa conteúdo */
        $scope.inicializar = function () {
            $scope.buscarSistemas();
            $scope.buscarTipos();
            if (sessionStorage.getItem('disciplina')) {
                var promise = Servidor.buscarUm('disciplinas-ofertadas', sessionStorage.getItem('disciplina'));
                promise.then(function(response) {
                    $scope.avaliacao.disciplina.id = response.data.id;
                    $scope.buscarDisciplinas(response.data.turma.id);
                    $scope.turma.id = response.data.turma.id;
                    promise = Servidor.buscarUm('turmas', response.data.turma.id);
                    promise.then(function(response) {
                        $scope.etapa.id = response.data.etapa.id;
                        $scope.curso.id = response.data.etapa.curso.id;
                        $scope.carregarEtapa($scope.etapa.id);
                        $scope.buscarCursos();
                        $scope.buscarEtapas($scope.curso.id);
                        $scope.buscarTurmas($scope.etapa.id);
                        Servidor.entradaPagina();
                    }, function(error) {
                        $scope.limparBusca();
                        $scope.buscarCursos();
                        Servidor.customToast(error.data.message);
                    });
                });
            } else {
                $scope.buscarCursos();
                $timeout(function(){ $('select').material_select(); Servidor.entradaPagina(); }, 500);
            }
            $('.tooltipped').tooltip('remove');
            $timeout(function (){
                Servidor.inputNumero();
                $('select').material_select();
                $('.counter').each(function(){  $(this).characterCounter();});
                $('.tooltipped').tooltip({delay: 50});
            },500);
        };

        /* Busca as aulas de uma disciplina em determinado mes */
        $scope.buscarAulas = function(turma, disciplina, mes) {
            if (!mes) { var mes = new Date().toJSON().split('T')[0].split('-')[1]; }
            if(parseInt(mes)<10) { mes = '0'+parseInt(mes); }
            var promise = Servidor.buscar('turmas/' + turma + '/aulas', {'disciplina': disciplina, 'mes': mes});
            promise.then(function(response) {
                $scope.aulas = response.data;
                if (!$scope.aulas.length) { Materialize.toast('Não há aulas neste mês.', 1500); } else
                if ($scope.semanas.length) { $scope.incluirAulas(); }
            });
        };

        /* Inicializa conteúdo referente ao calendário */
        $scope.calendarioHorarios = function(mes, ano) {
            var data = new Date();
            if (!ano) { ano = data.getFullYear(); }
            if (!mes && mes !== 0) { mes = data.getMonth()+1; }
            if (parseInt(mes) > 0 && parseInt(mes) < 13) {
                if ($scope.turma.id && $scope.avaliacao.disciplina.id) { $scope.buscarAulas($scope.turma.id, $scope.avaliacao.disciplina.id, mes); }
                $scope.semanas = [];
                $scope.limparSemana();
                var dia = 1;
                data =  new Date(ano, mes-1, dia).toJSON().split('T')[0];
                var comparaMes = mes;
                while (parseInt(mes) === parseInt(comparaMes)) {
                    var diaSemana = new Date(ano, mes-1, dia).toDateString().split(' ')[0];
                    switch(diaSemana) {
                        case 'Sun': $scope.semana.domingo.dia.data = data; break
                        case 'Mon': $scope.semana.segunda.dia.data = data; break
                        case 'Tue': $scope.semana.terca.dia.data = data; break
                        case 'Wed': $scope.semana.quarta.dia.data = data; break
                        case 'Thu': $scope.semana.quinta.dia.data = data; break
                        case 'Fri': $scope.semana.sexta.dia.data = data; break
                        case 'Sat':
                            $scope.semana.sabado.dia.data = data;
                            $scope.semanas.push($scope.semana);
                            $scope.limparSemana();
                        break
                    }
                    var data = new Date(ano, mes-1, ++dia).toJSON().split('T')[0];
                    comparaMes = data.split('-')[1];
                }
                if ($scope.semana.domingo.dia.data) { $scope.semanas.push($scope.semana); }
                if ($scope.aulas.length ) { $timeout(function() { $scope.incluirAulas(); }, 50); }
                switch (parseInt(mes)) {
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
            } else {
                Materialize.toast('Excedeu limite do calendario.', 2000);
            }
        };

        /* Carrega as aulas para os dias das semanas */
        $scope.incluirAulas = function() {
            for (var i = 0; i < $scope.aulas.length; i++) {
                for (var j = 0; j < $scope.semanas.length; j++) {
                    switch($scope.aulas[i].dia.data) {
                        case $scope.semanas[j].segunda.dia.data:
                            $scope.semanas[j].segunda = $scope.aulas[i];
                        break
                        case $scope.semanas[j].terca.dia.data:
                            $scope.semanas[j].terca = $scope.aulas[i];
                        break
                        case $scope.semanas[j].quarta.dia.data:
                            $scope.semanas[j].quarta = $scope.aulas[i];
                        break
                        case $scope.semanas[j].quinta.dia.data:
                            $scope.semanas[j].quinta = $scope.aulas[i];
                        break
                        case $scope.semanas[j].sexta.dia.data:
                            $scope.semanas[j].sexta = $scope.aulas[i];
                        break
                        case $scope.semanas[j].sabado.dia.data:
                            $scope.semanas[j].sabado = $scope.aulas[i];
                        break
                    }
                }
            }
        };

        /* Seleciona o dia da aula de entrega */
        $scope.carregarAula = function(aula) {
            if(aula.horario.id) {
                $scope.avaliacao.aulaEntrega = aula;
            } else {
                Materialize.toast('Não há aulas neste dia.', 1500);
            }
        };

        /* Verifica se o usuário deseja descartar os dados preenchidos */
        $scope.prepararVoltar = function(objeto) {
            if ((objeto.nome || objeto.disciplina.id || objeto.media) && !objeto.id) {
                $('#modal-certeza').modal();
            } else {
                $scope.fecharFormulario();
            }
        };

        /* Seleciona uma habilidade */
        $scope.adicionarHabilidade = function (habilidade, excluir) {
            var permissao = true;
            for (var i = 0; i < $scope.avaliacao.habilidades.length; i++) {
                if (habilidade.id === $scope.avaliacao.habilidades[i].id) {
                    if(excluir) { $scope.avaliacao.habilidades.splice(i, 1); }
                    permissao = false;
                }
            }
            if (permissao) { $scope.avaliacao.habilidades.push(habilidade); }
            if ($scope.habilidades.length === $scope.avaliacao.habilidades.length) {
                $scope.checkAll = true;
                $('.FMButton').prop('checked', true);
            } else {
                $scope.checkAll = false;
                $('.FMButton').prop('checked', false);
            }
        };

        /* Seleciona todas as habilidades */
        $scope.selecionarTudo = function(bool) {
            for (var i = 0; i < $scope.habilidades.length; i++) {
                if (bool) {
                    if ($('#hab' + $scope.habilidades[i].id).is(':checked')) {
                        $scope.adicionarHabilidade($scope.habilidades[i], !bool);
                    } else {
                        $scope.adicionarHabilidade($scope.habilidades[i], bool);
                    }
                } else {
                    if ($('#hab' + $scope.habilidades[i].id).is(':checked')) {
                        $scope.adicionarHabilidade($scope.habilidades[i], !bool);
                    }
                }
                $('#hab' + $scope.habilidades[i].id).prop('checked', bool);
            }
        };
        $scope.inicializar();
    }]);
})();
