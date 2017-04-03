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
    var matriculaEtapaModule = angular.module('matriculaEtapaModule', ['matriculaDirectives', 'servidorModule', 'erudioConfig', 'elementosModule']);
    matriculaEtapaModule.controller('MatriculaEtapaController', ['$scope', '$filter', 'Servidor', 'Restangular', '$timeout', '$templateCache', 'PessoaService', 'MatriculaService', '$compile', 'dateTime', 'makePdf', 'ErudioConfig', 'Elementos' , '$sce', '$routeParams', function ($scope, $filter, Servidor, Restangular, $timeout, $templateCache, PessoaService, MatriculaService, $compile, dateTime, makePdf, ErudioConfig, Elementos, $sce, $routeParams) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig;
        $scope.escrita = Servidor.verificaEscrita('MATRICULA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateCustom('matriculas','etapas'); $scope.lista = false;
        //ATRIBUTOS
        $scope.titulo = "Etapas da Matrícula"; $scope.buscaAvancada = false; $scope.etapasMatricula = []; $scope.etapa = {id: null}; $scope.disciplinasCursadas = [];
        $scope.matriculaBusca = { 'aluno': '', 'status': '', 'codigo': '', 'curso': null, 'unidade': null }; $scope.turmaMatricula = {'id': null}; $scope.disciplinaCursada = { 'disciplina': {id: null}, 'matricula': {id: null} };
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-turma').modal('open'); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //LIMPAR DISCIPLINA CURSADA
        $scope.limparDisciplinaCursada = function() { $scope.disciplinaCursada = { 'disciplina': {id: null}, 'matricula': {id: null} }; };
        //PREPARA VOLTAR
        $scope.prepararVoltar = function (objeto) { window.location = $scope.config.dominio+'/#/matriculas/'+$scope.matricula.id; };
        
        //BUSCAR ETAPAS
        $scope.iniciarEtapas = function () {            
            var promise = Servidor.buscar('etapas', {'curso': $scope.matricula.curso.id});
            promise.then(function (response) {
                var etapas = response.data; $scope.etapasMatricula = response.data;
                $scope.possiveisEtapas = response.data;
                $timeout(function() { $('select').material_select(); }, 50);
                $scope.etapas = []; $scope.requisicoes = 0;
                etapas.forEach(function(e) {
                    $scope.requisicoes++;
                    var promise = Servidor.buscar('disciplinas-cursadas', {matricula: $scope.matricula.id, etapa: e.id});
                    promise.then(function(response) {
                        if (response.data.length) {
                            e.disciplinasCursadas = response.data; var promise = Servidor.buscarUm('etapas', e.id);
                            promise.then(function(response) {
                                response.data.disciplinasCursadas = e.disciplinasCursadas; $scope.etapas.push(response.data);
                                if (--$scope.requisicoes === 0) {
                                    $scope.camposNovaEtapa = $scope.verificaCadastroDisciplinas($scope.etapas);
                                    $timeout(function(){ $('.collapsible').collapsible({ accordion : false });}, 50);
                                }
                            });
                        } else { $scope.requisicoes--; }
                    });
                });
            });
        };
        
        //BUSCAR INFORMACOES DISCIPLINA
        $scope.buscarInformacoesDisciplina = function(indice) {
            var botao = $('#btn-etapa'+$scope.etapas[indice].id);
            if (botao.text() === "keyboard_arrow_down") { botao.text("keyboard_arrow_up"); } else { botao.text("keyboard_arrow_down"); }
            $scope.etapas[indice].disciplinasCursadas.forEach(function(cursada) {
                if (cursada.porcentagem === undefined) {
                    var promise = Servidor.buscarUm('disciplinas-cursadas', cursada.id);
                    promise.then(function(response) {
                        Servidor.buscar('medias', {disciplinaCursada: cursada.id}).then(function(response) { cursada.medias = response.data; });
                        if(response.data.enturmacao !== undefined) {
                            cursada.enturmacao = response.data.enturmacao;
                            Servidor.buscar('frequencias', {disciplina: cursada.id}).then(function(response) {
                                cursada.faltas = 0; cursada.presencas = 0;
                                var frequencias = response.data;
                                frequencias.forEach(function(frequencia) { if(frequencia.status === 'PRESENCA') { cursada.presencas++; } });
                                promise = Servidor.buscar('turmas/'+cursada.enturmacao.turma.id+'/aulas', {disciplina: cursada.disciplinaOfertada.id});
                                promise.then(function(response) {
                                    var aulas = response.data.length;
                                    if (aulas) {
                                        cursada.porcentagem = ((cursada.presencas * 100) / aulas)+'%';
                                        if(cursada.porcentagem.length > 5) { cursada.porcentagem = cursada.porcentagem.slice(0, 5)+'%'; } else { cursada.porcentagem = "ND"; }
                                        $scope.fechaProgresso(); 
                                    }
                                });
                            });
                        } else { cursada.porcentagem = "ND"; }
                    });
                }
            });
        };
        
        //VERIFICA SE PODE CADASTRAR ETAPAS
        $scope.verificaCadastroDisciplinas = function(etapas) {
            var retorno = true;
            etapas.forEach(function(e) { e.disciplinasCursadas.forEach(function(dc) { if(dc.status === "CURSANDO") { retorno = false; } }); });
            $scope.fechaProgresso(); return retorno;
        };
        
        //BUSCAS TURMAS
        $scope.turmasCompativeis = function (id, cursadas) {
            if (!id) { id = $scope.etapa.id; } $scope.turmas = [];
            var requisicoesTurmasCompativeis = 0; $scope.requisicoes++;
            var promise = Servidor.buscar('turmas', {'etapa': id, unidadeEnsino: $scope.matricula.unidadeEnsino.id});
            promise.then(function (response) {
                $scope.turmas = response.data; $scope.fechaProgresso();
                $timeout(function(){
                    $('#turmaDisciplinas, #turmaEnturmacaoDisciplinasMatricula, #enturmacaoTurma').material_select('destroy');
                    $('#turmaDisciplinas, #turmaEnturmacaoDisciplinasMatricula, #enturmacaoTurma').material_select('');                                
                },50);
            });
        };
        
        //BUSCAR ETAPAS
        $scope.buscarEtapas = function (id, verifica) {
            $scope.etapasMatricula = []; var promise = Servidor.buscar('etapas', {'curso': id});
            promise.then(function (response) {
                $scope.etapasMatricula = response.data;
                $timeout(function () {
                    if (verifica === 'frequencia') { $scope.matriculaDisciplina = false; $('#etapaCurso').material_select('destroy'); $('#etapaCurso').material_select();
                    } else { $('select').material_select('destroy'); $('select').material_select(); }
                    $scope.mostraTurmas = false; $scope.nenhumaTurma = false; $scope.fechaProgresso();
                }, 150);
            });
        };
        
        //BUSCA DISCIPLINA DA TURMA
        $scope.buscarTurmasDisciplinas = function (id) {
            $scope.turmaMatricula.id = null; $scope.disciplinasOfertadas = []; $scope.mostraProgresso(); $scope.buscarEtapaCurso(id);
            var promiseD = Servidor.buscar('disciplinas', {'curso': $scope.matricula.curso.id, 'etapa': id});
            promiseD.then(function (responseD) {
                if (responseD.data.length) { $scope.disciplinasCurso = responseD.data; $scope.selecionarTodasDisciplinas();
                } else { $scope.fechaProgresso(); Servidor.customToast('Nao ha disciplinas nesta etapa.'); }
            });
        };
        
        //BUSCA ETAPA CURSO
        $scope.buscarEtapaCurso = function (id) {
            $scope.mostraProgresso(); var promise = Servidor.buscarUm('etapas', id);
            promise.then(function (response) { $scope.fechaProgresso(); $scope.etapaCurso = response.data; });
        };
        
        //SELECIONAR TODAS DISCIPLINAS
        $scope.selecionarTodasDisciplinas = function () {
            $scope.mostraProgresso(); $scope.disciplinasCursadas = []; var requisicoes = 0;
            var disciplinaCursada = { 'matricula': $scope.matricula.id, 'disciplina': null, 'id': null }; 
            if(!$scope.disciplinasCurso.length) { $scope.fechaProgresso(); }
            $scope.disciplinasCurso.forEach(function (d, index) {                                
                requisicoes++; var promise = Servidor.buscarUm('disciplinas', d.id);
                promise.then(function (response) {
                    disciplinaCursada.disciplina = angular.copy(response.data);
                    if (response.data.opcional) { $scope.disciplinasCursadas.push(angular.copy(disciplinaCursada));
                    } else { d = disciplinaCursada; }
                    if(--requisicoes === 0) { $scope.fechaProgresso(); }
                });
            });
        };
        
        //SELECIONA DISCIPLINAS
        $scope.selecionaDisciplina = function (disciplina) {
            var qtd = $scope.disciplinasCurso.length;
            $scope.disciplinasCurso.forEach(function (d, index){ if(d.id === disciplina.disciplina.id) { $scope.disciplinasCurso.splice(index, 1); } });
            if(qtd === $scope.disciplinasCurso.length) { $scope.disciplinasCurso.push(disciplina.disciplina); }
        };
        
        //SALVAR ETAPA
        $scope.salvarDisciplinasCursadas = function () {
            if ($scope.matricula.id) { $scope.mostraProgresso();
                if ($scope.etapa.id) {
                    if ($scope.disciplinasCurso !== undefined && $scope.disciplinasCurso.length) {
                        var promise = Servidor.buscarUm('matriculas', $scope.matricula.id);
                        promise.then(function(response) {
                            $scope.matricula = response.data; $scope.matricula.status = 'CURSANDO';
                            var promise = Servidor.finalizar($scope.matricula, 'matriculas', null);
                            promise.then(function(response) {
                                var cursadas = []; $scope.disciplinasCursadas = [];
                                $scope.disciplinasCurso.forEach(function (d) {
                                    $scope.disciplinaCursada.matricula.id = $scope.matricula.id;
                                    if (d.id) { $scope.disciplinaCursada.disciplina = d; $scope.disciplinaCursada.nome = d.nome; $scope.disciplinaCursada.nomeExibicao = d.nomeExibicao;
                                    } else { $scope.disciplinaCursada.disciplina = d.disciplina; $scope.disciplinaCursada.nome = d.disciplina.nome; $scope.disciplinaCursada.nomeExibicao = d.disciplina.nomeExibicao; }
                                    cursadas.push($scope.disciplinaCursada); Servidor.finalizar($scope.disciplinaCursada, 'disciplinas-cursadas', null); $scope.limparDisciplinaCursada();
                                    if(cursadas.length === $scope.disciplinasCurso.length) {
                                        window.location.href = ErudioConfig.dominio + '/#/matriculas/'+$scope.matricula.id+'/enturmacoes';
                                        Servidor.customToast('Etapa salva com sucesso.');
                                    }
                                });
                                $timeout(function() { $('select').material_select('destroy'); $('select').material_select(); $scope.fechaProgresso(); }, 250);
                            });
                        });
                    } else { Servidor.customToast('Esta etapa não possui disciplinas.'); $scope.fechaProgresso(); }
                } else { Servidor.customToast('Existem campos obrigatórios não preenchidos'); $scope.fechaProgresso(); }                  
            } else { $scope.fechaProgresso(); Materialize.toast('Precisa efetuar a matrícula antes de alocar as disciplinas.', 4000); }
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $scope.mostraProgresso();  var promise = Servidor.buscarUm('matriculas', $routeParams.id);
            promise.then(function (response) { $scope.matricula = response.data; $scope.iniciarEtapas(); $scope.fechaProgresso(); });
            $('.title-module').html($scope.titulo); $('#modal-ajuda-matricula').modal(); $('.material-tooltip').remove();
            $timeout(function () {
                $('select').material_select('destroy'); $('select').material_select();
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                $('.tooltipped').tooltip({delay: 50});
                $('.dropdown-button').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
            }, 300);
        };
        $scope.inicializar();
    }]);
})();