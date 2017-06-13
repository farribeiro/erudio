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

(function() {
    var turmasMistasModule = angular.module('turmaMistaModule', ['servidorModule', 'turmaMistaDirectives']);

    turmasMistasModule.controller('turmasMistasController', ['Servidor','$templateCache'], function($scope, Servidor, $templateCache) {
        $templateCache.removeAll();
        
        $scope.unidade = {id: parseInt(sessionStorage.getItem('unidade')) };
        $scope.escrita = Servidor.verificaEscrita('TURMAS_MISTAS');
        $scope.isAdmin = function() { return Servidor.verificaAdmin(); };
        $scope.cortina = false; $scope.editando = false; $scope.requisicoes = 0;

        var getEndereco = function() { return 'turmas/agrupamentos'; };
        var esconderCortina = function() { $scope.cortina = false; };
        var mostrarCortina = function() { $scope.cortina = true; };
        var newAgrupamento = function() { return { nome: null, turmas: [] }; };
        var buildSelect = function(selector) { setTimeout(function() { $(selector).material_select(); }, 250); };

        // Mostra cortina quando há requisições em andamento
        $scope.$watch('requisicoes', function(requisicoes) { if(requisicoes) { mostrarCortina(); } else { esconderCortina(); } });

        // Buscas

        $scope.buscar = function(params) {
            params = params || { nome: null };
            $scope.requisicoes++;
            params.unidadeEnsino = $scope.unidade.id;
            var promise = Servidor.buscar(getEndereco(), params);
            promise.then(function(response) {
                $scope.agrupamentos = response.data;
                $scope.requisicoes--;
            });
        };

        var buscarTurmasAgrupamento = function(agrupamento) {
            $scope.requisicoes++;
            var promise = Servidor.buscar('turmas', {agrupamento: agrupamento.id});
            promise.then(function(response) {
                agrupamento.turmas = response.data;
                $scope.requisicoes--;
                return agrupamento;
            });
        };

        var buscarCursos = function() {
            $scope.etapas = [];
            $scope.etapa = {id: null};
            buildSelect('#etapaAgrupamento');
            $scope.turmas = [];
            $scope.requisicoes++;
            if($scope.isAdmin()) {
                var promise = Servidor.buscar('cursos', null);
                promise.then(function(response) {
                    $scope.cursos = response.data;
                    buildSelect('#cursoAgrupamento');
                    $scope.requisicoes--;
                });
            } else {
                var promise = Servidor.buscarUm('unidades-ensino', $scope.unidade.id);
                promise.then(function(response) {
                    $scope.cursos = response.data;
                    buildSelect('#cursoAgrupamento');
                    $scope.requisicoes--;
                });
            }
        };

        $scope.buscarEtapas = function(curso) {
            $scope.turmas = [];
            $scope.requisicoes++;
            var promise = Servidor.buscar('etapas', {curso: curso.id});
            promise.then(function(response) {
                $scope.etapas = response.data;
                buildSelect('#etapaAgrupamento');
                $scope.requisicoes--;
            });
        };

        $scope.buscarTurmas = function(etapa) {
            $scope.requisicoes++;
            var promise = Servidor.buscar('turmas', {etapa: etapa.id, unidadeEnsino: $scope.unidade.id});
            promise.then(function(response) {
                $scope.turmas = response.data;
                retirarTurmasJaSelecionadas($scope.agrupamento, $scope.turmas);
                $scope.requisicoes--;
            });
        };

        // Formulario

        var retirarTurmasJaSelecionadas = function(agrupamento, turmas) {
            agrupamento.turmas.forEach(function(at) {
                turmas.forEach(function(t, j) {
                    if(t.id === at.id) {
                        turmas.splice(j, 1);
                    }
                });
            });
            return turmas;
        };

        $scope.adicionarTurma = function(turma) {
            $scope.agrupamento.turmas.push(turma);
            $scope.turmas.forEach(function(t, i) {
                if(t.id === turma.id) {
                    $scope.turmas.splice(i, 1);
                }
            });
            $('#turmaAdicionar'+turma.id).remove();
        };
        
        $scope.removerTurma = function(agrupamento, turma) {            
            if(agrupamento.id !== undefined && turma.agrupamento.id) {
                $scope.requisicoes++;
                turma.agrupamento = "";
                var promise = Servidor.finalizar(turma, 'turmas', 'Turma');
                promise.then(function(response) {
                    $scope.requisicoes--;
                    agrupamento.turmas.forEach(function(at, i) {
                        if(at.id === turma.id) {
                            agrupamento.turmas.splice(i, 1);
                            if(turma.etapa.id === parseInt($scope.etapa.id)) {
                                $scope.turmas.push(angular.copy(turma));
                            }
                        }
                    });
                    return agrupamento;
                });
            } else {
                agrupamento.turmas.forEach(function(at, i) {
                    if(at.id === turma.id) {
                        agrupamento.turmas.splice(i, 1);
                        if(turma.etapa.id === parseInt($scope.etapa.id)) {
                            $scope.turmas.push(angular.copy(turma));
                        }
                    }
                });
                return agrupamento;
            }
        };

        $scope.selecionar = function(agrupamento) {
            $scope.curso = {id: null};
            $scope.etapa = {id: null}; $scope.etapas = [];
            $scope.turmas = [];
            buildSelect('#cursoAgrupamento, #etapaAgrupamento');                        
            if(agrupamento.id !== undefined) {
                $scope.agrupamento = agrupamento;
                $scope.tipoAcao = 'Editar';
                buscarTurmasAgrupamento(agrupamento);
                setTimeout(function() { Servidor.verificaLabels(); }, 50);
            } else {
                $scope.tipoAcao = 'Cadastrar';
                $scope.agrupamento = newAgrupamento();
            }            
            $scope.editando = true;
        };

        $scope.salvar = function(agrupamento) {
            $scope.requisicoes++;
            agrupamento.unidadeEnsino = {id: $scope.unidade.id};
            var promise = Servidor.finalizar(agrupamento, getEndereco(), 'Turma Mista');
            promise.then(function(response) {
                agrupamento.id = response.data.id;
                $scope.requisicoes--;
                if(agrupamento.turmas.length) {
                    agrupamento.turmas.forEach(function(turma) {
                        $scope.requisicoes++;
                        turma.agrupamento = {id: agrupamento.id };
                        var promise = Servidor.finalizar(turma, 'turmas', '');
                        promise.then(function() {
                            if(--$scope.requisicoes === 0) {
                                $scope.fecharFormulario();
                            }
                        });
                    });
                } else {
                    $scope.fecharFormulario();
                }                
            });
        };

        $scope.prepararVoltar = function(agrupamento) {
            if(agrupamento.id === undefined && (agrupamento.nome || agrupamento.turmas.length)) {
                $('#modal-certeza').openModal();
            } else {
                $scope.fecharFormulario();
            }
        };

        $scope.prepararRemover = function(agrupamento) {            
            var promise = Servidor.buscar('turmas', {agrupamento: agrupamento.id});
            promise.then(function(response) {
                var agrupamentos = response.data;
                if(agrupamentos.length) {
                    Servidor.customToast('Não é possível remover esta Turma Mista.');
                } else {
                    $('#remove-modal-turma-msita').material_select();
                }
            });
        };

        $scope.remover = function(agrupamento) {
            $scope.requisicoes++;
            Servidor.remover(agrupamento, 'Turma Mista');
            $scope.agrupamentos.forEach(function(a, i) {
                if(a.id === $scope.agrupamento.id) {
                    $scope.agrupamentos.splice(i, 1);
                    $scope.requisicoes--;
                    $scope.agrupamento = newAgrupamento();
                }
            });
        };

        $scope.fecharFormulario = function() {
            $scope.editando = false;
            $scope.agrupamento = newAgrupamento();
        };

        // Inicializar

        var inicializar = function() {
            $scope.buscar();
            buscarCursos();
            buildSelect('select');
            Servidor.entradaPagina();
        };

        inicializar();
    });
})();