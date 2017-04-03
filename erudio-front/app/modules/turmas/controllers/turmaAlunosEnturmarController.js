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
    var turmaAlunosEnturmarModule = angular.module('turmaAlunosEnturmarModule', ['servidorModule', 'turmaDirectives', 'erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    turmaAlunosEnturmarModule.controller('TurmaAlunosEnturmarController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$routeParams', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $routeParams) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.isAdmin = Servidor.verificaAdmin(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('TURMA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateCustom('turmas','enturmar');
        //ATRIBUTOS
        $scope.matriculas = []; $scope.enturmacoes = []; $scope.progresso = false; $scope.cortina = false; $scope.titulo = "Enturmar Aluno"; $scope.mostraAlunos = true;
        $scope.matriculaBusca = { 'aluno': '', 'status': '', 'codigo': '', 'curso': null, 'unidade': null }; $scope.etapa = { 'id': null };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //PREPARA VOLTAR
        $scope.prepararVoltar = function (objeto) { window.location = $scope.config.dominio+'/#/turmas/' + $routeParams.id + '/alunos'; };
        //MOSTRA LABELS MENU FAB
        $scope.mostrarLabels = function () { $('.toolchip').fadeToggle(250); };
        //TELA DE ENTURMACAO DE ALUNOS
        $scope.enturmarAlunos = function () { Servidor.verificaLabels(); $scope.verificarDiaLetivo($scope.turma.calendario, new Date().toJSON().split('T')[0]); };
        //REINICIA BUSCA MATRICULAS
        $scope.reiniciarBuscaMatriculas = function () { $scope.matriculaBusca = { 'aluno': '', 'codigo': '', 'curso': $scope.turma.etapa.curso.id, 'unidade': $scope.turma.unidadeEnsino.id }; };
        
        //REMOVE TODAS AS MATRICULAS
        $scope.removerTodasMatriculas = function () {
            var tamanho = $scope.matriculas.length;
            for (var i = 0; i < tamanho; i++) { $('#' + $scope.matriculas[i].codigo + $scope.opcaoForm).removeAttr('checked', 'checked'); }
        };
        
        //VERIFICA DIA LETIVO
        $scope.verificarDiaLetivo = function(calendario, data) {
            var promise = Servidor.buscar('calendarios/'+calendario.id+'/dias', {data: data});
            promise.then(function(response) {
                if (response.data.length) {
                    var dia = response.data[0];
                    if (dia.letivo) { $scope.botaoEnturmacaoAutomatica = false;
                    } else { var d = data.split('-'); data = new Date(d[0], d[1], parseInt(d[2]-1)).toJSON().split('T')[0]; $scope.verificarDiaLetivo(calendario, data); Servidor.verificaLabels(); }
                } else { $scope.botaoEnturmacaoAutomatica = true; Servidor.verificaLabels(); }
            });
        };
        
        //BUSCAR ENTURMACOES
        $scope.buscarEnturmacoes = function () {
            $scope.mostraProgresso();            
            var promise = Servidor.buscar('vagas', {turma: $scope.turma.id}); var solicitacoes = 0;
            promise.then(function(response){
                var vagas = response.data;
                for (var j=0; j<vagas.length; j++) { if (vagas[j].solicitacaoVaga !== undefined) { solicitacoes++; } }
                $scope.turma.quantidadeAlunos = $scope.turma.quantidadeAlunos + solicitacoes; $scope.solicitacoes = solicitacoes;
                var promise = Servidor.buscar('enturmacoes', {'turma': $scope.turma.id, 'encerrado': 0});
                promise.then(function (response) {
                    $scope.enturmacoes = response.data;
                    if ($scope.enturmacoes.length === 0) {
                        //TRATAR
                    } else {
                        $scope.enturmacoes.forEach(function(e) { Servidor.buscarUm('matriculas', e.matricula.id).then(function(response) { e.matricula = response.data; });});
                        $scope.nenhumaEnturmacao = false;
                    }
                    $('.tooltipped').tooltip('remove');
                    $timeout(function () { $('.tooltipped').tooltip({delay: 50}); $scope.fechaProgresso(); $('.collapsible').collapsible(); }, 500);
                });
            });
        };
        
        //AUTO ENTURMACAO
        $scope.carregarFormularioEnturmacaoAutomatica = function() {
            var promise = Servidor.buscar('etapas', {curso: $scope.turma.etapa.curso.id});
            promise.then(function(response) {
                $scope.etapas = response.data; var encontrou = false;
                $scope.etapas.forEach(function(etapa) {
                    if (etapa.ordem === $scope.turma.etapa.ordem-1) {
                        encontrou = true; $scope.etapa = etapa; $scope.etapa.turmas = []; $scope.enturmacaoAutomatica = true;
                        $scope.buscarEnturmacoesEtapa(etapa.id);
                        $timeout(function(){
                            $('.dropdown-button-enturmacao-automatica').dropdown({ inDuration: 300, outDuration: 225, constrain_width: false, hover: false, gutter: 0, belowOrigin: false, alignment: 'left' });
                            $('#etapaEnturmacaoAutomatica').material_select();
                        }, 50);
                    }
                });
                if (!encontrou) { $scope.enturmacaoAutomatica = false; Servidor.customToast('Não há etapa que antecede ' + $scope.turma.etapa.nomeExibicao + '.'); }
            });
        };
        
        //BUSCAR MATRICULAS
        $scope.buscarMatriculas = function (matricula) {
            $scope.mostraProgresso(); $scope.matriculas = []; $scope.matriculasVerificar = [];
            var promise = Servidor.buscar('disciplinas-ofertadas', {turma: $scope.turma.id});
            promise.then(function(response) { $scope.disciplinasOfertadas = response.data; });
            $scope.matriculaBusca.curso = $scope.turma.etapa.curso.id; $scope.matriculaBusca.unidade = $scope.turma.unidadeEnsino.id; $scope.adicionarAlunos = true;
            var promise = Servidor.buscar('matriculas', {'status': 'CURSANDO', 'codigo': matricula.codigo, 'aluno_nome': matricula.aluno, 'unidadeEnsino': $scope.matriculaBusca.unidade, 'curso': $scope.matriculaBusca.curso});
            promise.then(function (response) {
                if(response.data.length){
                    $scope.matriculasVerificar = response.data; $scope.alunosCompativeisSelecionados = 0; $scope.enturmacoesEncerradas(response.data);                        
                }else{ Servidor.customToast('Nenhuma matricula encontrada.'); }
                $scope.fechaProgresso();
            });
        };
        
        //ENTURMACOES ENCERRADAS
        $scope.enturmacoesEncerradas = function(matriculas) {
            $scope.mostraProgresso(); var compativeis = []; $scope.requisicoes = 0;
            matriculas.forEach(function(m) {
                $scope.requisicoes++; var promise = Servidor.buscar('enturmacoes', {matricula: m.id, encerrado: 0});
                promise.then(function(response) {
                    if (!response.data.length) { compativeis.push(m); }
                    if (--$scope.requisicoes === 0) {
                        if (compativeis.length) { $scope.alunosDisciplinasCompativeis(compativeis);
                        } else { $scope.fechaProgresso(); Servidor.customToast('Nenhuma matrícula compatível encontrada.'); }
                    } else { $scope.fechaProgresso(); }
                });
            });
        };
        
        //BUSCAR ENTURMACOES ETAPA
        $scope.buscarEnturmacoesEtapa = function(etapa) {
            $scope.enturmacoes = [];
            var promise = Servidor.buscar('turmas', {etapa: etapa});
            promise.then(function(response) {
                $scope.etapa.turmas = response.data;
                $scope.etapa.turmas.forEach(function(turma) {
                    var promise = Servidor.buscar('enturmacoes', {turma: turma.id});
                    promise.then(function(response) { response.data.forEach(function(enturmacao) { $scope.enturmacoes.push(enturmacao); }); });
                });
            });
        };
        
        //DISCIPLINAS COMPATIVEIS
        $scope.alunosDisciplinasCompativeis = function(matriculas) {
            $scope.mostraProgresso(); $scope.requisicoes = 0;
            matriculas.forEach(function(m) {
                $scope.requisicoes++; var promise = Servidor.buscar('disciplinas-cursadas', {matricula: m.id, status:'CURSANDO', etapa:$scope.turma.etapa.id});
                promise.then(function(response) {
                    if (response.data.length) { $scope.matriculas.push(m); }
                    if (--$scope.requisicoes === 0) {
                        if (!$scope.matriculas.length) { Servidor.customToast('Nenhuma matrícula compatível encontrada.'); } $scope.fechaProgresso();
                    }
                });
            });
        };
        
        //SALVAR ENTURMACAO
        $scope.finalizarEnturmacao = function () {
            var cont = 0; var tamanho = $scope.matriculas.length;
            for (var i = 0; i < tamanho; i++) {
                if ($("#" + $scope.matriculas[i].codigo + $scope.opcaoForm).is(':checked')) {
                    cont++; $scope.enturmacao.turma.id = $scope.turma.id; $scope.enturmacao.matricula.id = $scope.matriculas[i].id;
                    var promise = Servidor.finalizar($scope.enturmacao, 'enturmacoes', 'Enturmação');
                    promise.then(function (result) {
                        $scope.turma = result.data.turma; var idEnturmacao = result.data.id; var id = result.data.matricula.id;
                        var promise = Servidor.buscar('matriculas/' + id + '/disciplinas-cursadas', {'etapa': $scope.etapa.id});
                        promise.then(function (response) {
                            $scope.disciplinasCursadas = response.data; var tamanho = $scope.disciplinasCursadas.length;
                            for (var i = 0; i < tamanho; i++) { $scope.disciplinasCursadas[i].enturmacao = idEnturmacao; }
                        });
                        var promise = Servidor.buscar('enturmacoes', {'turma': $scope.turma.id, 'encerrado': false});
                        promise.then(function (response) {
                            $scope.enturmacoes = response.data; $scope.enturmacao = {'turma': {id: null}, 'matricula': {id: null}};
                            $timeout(function () { $scope.buscarMatriculas($scope.matriculaBusca, false); }, 100);
                        });
                    });
                }
                $scope.enturmacao = {'turma': {id: null}, 'matricula': {id: null}};
            }
            if (cont === 0) { Servidor.customToast('Nenhuma matricula selecionada'); }
        };
        
        //SELECIONA MATRICULAS COMPATIVEIS
        $scope.selecionarTodasMatriculasCompativeis = function() {
            var bool = $('#enturmacoesCheckAll').prop('checked'); $('.matricula-compativel').prop('checked', bool); $scope.alunosCompativeisSelecionados = (bool) ? $scope.matriculas.length : 0;
        };
        
        //FILTRAR ENTURMACOES POR TURMA
        $scope.filtrarEnturmacoesPorTurma = function(turma) {
            if ($('#filtro-turma'+turma).prop('checked')) { $('.turma'+turma).removeClass('hide'); } else { $('.turma'+turma).addClass('hide'); }
        };
        
        //ATUALIZA VAGAS PREENCHIDAS
        $scope.atualizarVagasPreenchidas = function() {
            var cont = 0; $('.enturmacao:not(.hide) input').each(function() { ($(this).prop('checked')) ? cont++ : cont; }); $scope.alunosCompativeisSelecionados = cont;
        };
        
        //SELECIONA MATRICULA COMPATIVEL
        $scope.selecionarUmaMatriculaCompativel = function(cod) {
            var bool = true; $scope.matriculas.forEach(function(m) { ($('#'+m.codigo+'turma').prop('checked')) ? bool : bool = false; });
            $scope.alunosCompativeisSelecionados += ($('#'+cod+'turma').prop('checked')) ? 1 : -1; $('#enturmacoesCheckAll').prop('checked', bool);
        };
        
        //ENTURMAR MATRICULAS
        $scope.enturmarMatriculas = function() {
            $scope.mostraProgresso(); $scope.requisicoes = 0;
            var promise = Servidor.buscar('vagas', {turma: $scope.turma.id});
            promise.then(function(response) {
                var vagas = response.data;
                $scope.matriculas.forEach(function(m) {
                    if ($('#'+m.codigo+'turma').prop('checked')) {
                        $scope.requisicoes++;
                        var promise = Servidor.finalizar({ matricula: {id: m.id}, turma: {id: $scope.turma.id} }, 'enturmacoes', '');
                        promise.then(function(response) {
                            $scope.requisicoes--; var enturmacao = response.data; enturmacao.achouVaga = false;
                            vagas.forEach(function(v) {
                                if (!enturmacao.achouVaga && (!v.enturmacao || v.enturmacao === undefined) && (!v.solicitacao || v.solicitacao === undefined)) {
                                    v.enturmacao = {id: response.data.id}; enturmacao.achouVaga = true; $scope.requisicoes++;
                                    var promise = Servidor.finalizar(v, 'vagas', '');
                                    promise.then(function() {
                                        if (--$scope.requisicoes === 0) { $scope.fechaProgresso(); $scope.buscarEnturmacoes($scope.turma, true); }
                                    });
                                }
                            });
                        });
                    }
                });
            });
        };
        
        //FILTRA APROVADOS E REPROVADOS
        $scope.separarAprovadosReprovados = function() {
            $scope.aprovados = []; $scope.reprovados = []; $scope.requisicoes = 0;
            $scope.enturmacoes.forEach(function(e) {
                if ($('#ent'+e.id+':not(.hide) input').prop('checked')) {
                    $scope.requisicoes++; var promise = Servidor.buscar('disciplinas-cursadas', {enturmacao: e.id});
                    promise.then(function(response) {
                        e.disciplinasCursadas = response.data;
                        if (alunoAprovado(e.disciplinasCursadas)) { $scope.aprovados.push(e); } else { $scope.reprovados.push(e); }
                        if (--$scope.requisicoes === 0) {
                            if ($scope.reprovados.length) {
                                $timeout(function() { $('#alunos-reprovados-modal').modal('open'); $('.collapsible').collapsible({ accordion : false }); }, 250);
                            } else { $scope.enturmarAutomaticamente(); }
                        }
                    });
                }
            });
        };
        
        //SELECIONA ENTURMACOES
        $scope.selecionarEnturmacoes = function(opcao) {
            switch(opcao) {
                case 'todos': $('.enturmacao:not(.hide) input').prop('checked', true); break;
                case 'nenhum': $('.enturmacao:not(.hide) input').prop('checked', false); break;
                case 'mesclar': $('.enturmacao:not(.hide) input:even').prop('checked', true); $('.enturmacao:not(.hide) input:odd').prop('checked', false); break;
            }
        };
        
        //ENTURMACAO AUTOMATICA
        $scope.enturmarAutomaticamente = function() {
            var promise = Servidor.buscar('vagas', {turma: $scope.turma.id});
            promise.then(function(response) {
                var vagas = response.data;
                $scope.reprovados.forEach(function(r, i) { if ($('#rep'+r.id).prop('checked') !== undefined && $('#rep'+r.id).prop('checked')) { $scope.aprovados.push(r); } });
                $timeout(function() {
                    $scope.requisicoes = 0;
                    $scope.aprovados.forEach(function(e) {
                        e.encerrado = true; $scope.requisicoes++;
                        Servidor.finalizar(e, 'enturmacoes', '').then(function() {
                            Servidor.finalizar({matricula:{id:e.matricula.id}, turma:{id:$scope.turma.id}}, 'enturmacoes', '').then(function() { if (--$scope.requisicoes === 0) { $scope.buscarEnturmacoes($scope.turma, true); } });
                        });
                        var achouVaga = false;
                        vagas.forEach(function(v) {
                            if (!achouVaga && (v.enturmacao === undefined || !v.enturmacao) && (v.solicitacao === undefined || !v.solicitacao)) {
                                v.enturmacao = e.id; $scope.requisicoes++;
                                Servidor.finalizar(v, 'vagas', '').then(function() { if (--$scope.requisicoes === 0) { $scope.buscarEnturmacoes($scope.turma, true); } });
                                achouVaga = true;
                            }
                        });
                    });
                }, 500);
            });
        };
        
        //ALUNOS APROVADOS
        var alunoAprovado = function(disciplinas) {
            if (!disciplinas.length) { return false; } var retorno = true;
            disciplinas.forEach(function(d) { if (d.status !== 'APROVADO') { retorno = false; } }); return retorno;
        };

        //INICIALIZAR
        $scope.inicializar = function () {
            $('.material-tooltip').remove(); var promise = Servidor.buscarUm('turmas',$routeParams.id); $('.title-module').html($scope.titulo);
            promise.then(function(response){
                $scope.turma = response.data;
                if ($scope.turma.quantidadeAlunos) { $scope.buscarEnturmacoes();
                } else { $scope.enturmarAlunos(); Servidor.customToast('Turma não possui nenhum aluno enturmado'); }
                $timeout(function () { $('.tooltipped').tooltip({delay: 50}); Servidor.entradaPagina();}, 1000);
            });
        };
        
        $scope.inicializar(); 
    }]);
})();