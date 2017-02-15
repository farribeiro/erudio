(function (){
    var registroMatriculasModule = angular.module('registroMatriculasModule', ['servidorModule', 'registroMatriculaDirectives']);
    registroMatriculasModule.controller('registroMatriculasController', ['$scope', 'makePdf', 'Servidor', 'Restangular', '$timeout', '$templateCache','$compile','dateTime', function ($scope, makePdf, Servidor, Restangular, $timeout, $templateCache, $compile, dateTime){
        $templateCache.removeAll();
        $scope.editando = false;
        $scope.requisicoes = 0;
        $scope.etapa = {turmas:[]};
        $scope.etapasSelecionadas = [];
        
        $scope.mostrarCortina = function () { $scope.cortina = true; };
        $scope.esconderCortina = function () { $scope.cortina = false; };
        
        $scope.buscarVinculo = function() {
            var promise = Servidor.buscarUm('vinculos', sessionStorage.getItem('vinculo'));
            promise.then(function(response) {
                $scope.vinculo = response.data;
            });
        };
        
        $scope.buscarAlocacao = function() {
            var promise = Servidor.buscarUm('alocacoes', sessionStorage.getItem('alocacao'));
            promise.then(function(response) {
                $scope.alocacao = response.data;
                var promise = Servidor.buscarUm('instituicoes', $scope.alocacao.instituicao.id);
                promise.then(function(response) {
                    $scope.alocacao.instituicao = response.data;
                });
            });
        };
        
        $scope.buscarCursos = function () {
            var promiseInstituicao = Servidor.buscarUm('instituicoes', sessionStorage.getItem('unidade'));
            promiseInstituicao.then(function(response){
               var instituicao = response.data; 
               $scope.cursos = instituicao.cursos;
               if ($scope.cursos.length === 1) {
                   $scope.curso = $scope.cursos[0];
                   $scope.buscarEtapas($scope.curso.id);
               }
               $timeout(function(){
                   $('#cursoRegistroMatricula').material_select();
               },50);
            });
        };
        
        $scope.buscarEtapas = function (id) {
            var promise = Servidor.buscar('etapas', {'curso': id});
            promise.then(function(response){
                $scope.etapas = response.data;
                $('.tooltipped').tooltip('remove');
                $timeout(function(){
                    $('.tooltipped').tooltip({delay: 50});
                }, 50);
            });
        };
        
        $scope.buscarTurmas = function(etapa){
            var promise = Servidor.buscarUm('etapas', etapa);
            promise.then(function(response) {
                $scope.etapa = response.data;
                $scope.etapa.turmas = [];
                var promise = Servidor.buscar('turmas', {'etapa': etapa, unidadeEnsino: sessionStorage.getItem('unidade')});
                promise.then(function(response){
                   $scope.turmas = response.data;
                });
            });                
        };
        
        $scope.selecionarEtapa = function(etapa) {
            var encontrou = false;
            $scope.etapasSelecionadas.forEach(function(etapaS, i) {
                if (parseInt(etapaS.id) === parseInt(etapa.id)) {
                    if ($scope.etapasSelecionadas.length === $scope.etapas.length) {
                        $('#checkboxEtapas').prop('checked', false);
                    }
                    $scope.etapasSelecionadas.splice(i, 1);
                    encontrou = true;
                }
            });
            if (!encontrou) {
                $scope.etapasSelecionadas.push(angular.copy(etapa));
                $('#eta'+etapa.id)[0].checked = true;
                if ($scope.etapasSelecionadas.length === $scope.etapas.length) {
                    $('#checkboxEtapas').prop('checked', true);
                }
            }
        };

        $scope.selecionarTodasEtapas = function() {
            if ($scope.etapasSelecionadas.length === $scope.etapas.length) {
                if (!$('#checkboxEtapas')[0].checked) {
                    $scope.etapasSelecionadas = [];
                    $('.checkbox-etapa').prop('checked', false);
                }
            } else {
                if ($('#checkboxEtapas')[0].checked) {
                    $scope.etapasSelecionadas = angular.copy($scope.etapas);
                    $('.checkbox-etapa').prop('checked', true);
                }
            }
        };
        
        $scope.selecionarTurma = function(turma) {
            for(var i = 0; i < $scope.etapa.turmas.length; i++) {
                if (parseInt($scope.etapa.turmas[i].id) === parseInt(turma.id)) {
                    if ($scope.etapa.turmas.length === $scope.turmas.length) {
                        $('#checkboxTurmas').prop('checked', false);
                    }
                    $scope.etapa.turmas.splice(i, 1);
                    return 0;
                }
            };
            $scope.etapa.turmas.push(angular.copy(turma));
            $('#tur'+turma.id)[0].checked = true;
            if ($scope.etapa.turmas.length === $scope.turmas.length) {
                $('#checkboxTurmas').prop('checked', true);
            }
        };

        $scope.selecionarTodasTurmas = function() {
            if ($scope.etapa.turmas.length === $scope.turmas.length) {
                if (!$('#checkboxTurmas')[0].checked) {
                    $scope.etapa.turmas = [];
                    $('.checkbox-turma').prop('checked', false);
                }
            } else {
                if ($('#checkboxTurmas')[0].checked) {
                    $scope.etapa.turmas = angular.copy($scope.turmas);
                    $('.checkbox-turma').prop('checked', true);
                }
            }
        };
        
        $scope.prepararDocumento = function(visualizar) {
            $scope.mostrarCortina();
            if ($scope.etapa.turmas.length) {
                $scope.buscarDadosEnturmacoes([$scope.etapa], visualizar);
            } else {
                $scope.requisicoes = 0;
                $scope.etapasSelecionadas.forEach(function(etapa, i) {
                    $scope.requisicoes++;
                    var promise = Servidor.buscar('turmas', {etapa: etapa.id, unidadeEnsino: sessionStorage.getItem('unidade')});
                    promise.then(function(response) {
                        etapa.turmas = response.data;
                        if (--$scope.requisicoes === 0) {
                            $scope.buscarDadosEnturmacoes($scope.etapasSelecionadas, visualizar);
                        }                        
                    });
                });                
            }
        };
        
        $scope.buscarDadosEnturmacoes = function(etapas, visualizar) {
            etapas.forEach(function(etapa) {                
                etapa.turmas.forEach(function(turma) {
                    $scope.requisicoes++;
                    var promise = Servidor.buscar('enturmacoes', {turma: turma.id});
                    promise.then(function(response) {
                        $scope.requisicoes--;
                        turma.enturmacoes = response.data;
                        turma.enturmacoes.forEach(function(enturmacao) {
                            $scope.requisicoes++;
                            var promise = Servidor.buscarUm('pessoas', enturmacao.matricula.aluno.id);
                            promise.then(function(response) {
                                enturmacao.matricula.aluno = response.data;                                
                                if (--$scope.requisicoes === 0) {                                    
                                    if (visualizar) {
                                        $scope.visualizarDocumento(etapas.sort(compararEtapas));
                                    } else {
                                        makePdf.gerarRegistroMatricula(etapas, $scope.vinculo, $scope.alocacao, $scope.assinatura);
                                        $scope.esconderCortina();
                                    }
                                }
                            });
                        });
                    });
                });
            });
        };
        
        $scope.prepararVisualizacaoDocumento = function(origem, objeto) {
            switch(origem) {
                case 'etapa':
                    $scope.etapasSelecionadas = [objeto];                    
                break;
                case 'turma':
                    $scope.etapa.turmas = [objeto];                    
                break;
            }
            $scope.prepararDocumento(true);
        };
        
        $scope.visualizarDocumento = function(etapas) {
            $scope.enturmacoes = [];
            etapas[0].turmas.forEach(function(turma) {
                turma.enturmacoes.forEach(function(enturmacao) {
                    enturmacao.matricula.aluno.idade = dateTime.idadePessoa(enturmacao.matricula.aluno.dataNascimento);
                    if (enturmacao.matricula.aluno.idade < 0) { enturmacao.matricula.aluno.idade = null; }
                    $scope.enturmacoes.push(enturmacao);
                });
            });
            $timeout(function() {                
                $scope.visualizando = true;
                $timeout(function() { $scope.tamanhoTabela = $('#registros-matricula').innerHeight(); }, 500);                
                $scope.esconderCortina();
            }, 500);          
        };
        
        $scope.voltarInicio = function () {            
            $scope.turmas = [];
            $scope.etapa = {id: null, turmas: []};
            if ($scope.visualizando) {
                $scope.etapasSelecionadas = [];
                $scope.visualizando = false;
            }
            $('#checkboxTurmas').prop('checked', false);
        };
        
        function compararEtapas(a, b) {
            var retorno = 0;
            (a.nomeExibicao > b.nomeExibicao) ? retorno++ : retorno--;
            return retorno;
        }
        
        $scope.inicializar = function(){
            $('.tooltipped').tooltip('remove');
            $timeout(function(){
                $scope.buscarCursos();
                $('select').material_select();
                Servidor.entradaPagina();
                $(document).ready(function () {
                    $('.tooltipped').tooltip({delay: 50});
                });
            },500);
            $('.tooltipped').tooltip({delay: 50});
            $scope.buscarVinculo();
            $scope.buscarAlocacao();
        };     
        
        $scope.inicializar();
    }]);

})();
