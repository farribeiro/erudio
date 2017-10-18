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

    var boletimEscolarModule = angular.module('boletimEscolarModule', ['servidorModule', 'boletimEscolarDirectives', 'dateTimeModule']);

    boletimEscolarModule.controller('boletimEscolarController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', 'makePdf', 'dateTime', function ($scope, Servidor, Restangular, $timeout, $templateCache, makePdf, dateTime) {
        $templateCache.removeAll();
            
        $scope.mostrarCortina = function() { $scope.cortina = true; };
        $scope.fecharCortina = function() { $scope.cortina = false; };
        $scope.editando = false;

        var montarSelect = function(seletor, tempo) {
            if (!tempo) { tempo = 250; }
            $(seletor).material_select('destroy');
            $('ul.tabs').tabs();
            setTimeout(function() { $(seletor).material_select(); }, tempo);
        };

        /* Limpar campos de Busca*/
        $scope.limparBusca = function(){
            $scope.busca = {
                curso: { id: null },
                etapa: { id: null },
                turma: { id: null }
            };
            $scope.turmasSelecionadas = [];
            $scope.alunosSelecionados = [];
            $scope.listaAlunos = false;
            $scope.etapas = [];
            $scope.turmas = [];
            $scope.alunos = [];
            montarSelect('#cursoBoletim, #etapaBoletim, #turmaBoletim');
        };

        /* buscar curso */
        $scope.buscarCursos = function() {
            var promise = Servidor.buscar('cursos', null);
            promise.then(function(response) {
                $scope.cursos = response.data;
                montarSelect('#cursoBoletim');
            });
        };

        /* buscar etapas de um curso */
        $scope.buscarEtapas = function(curso) {
            var promise = Servidor.buscar('etapas', {curso: curso});
            promise.then(function(response) {
                $scope.etapas = response.data;
                montarSelect('#etapaBoletim');
            });
        };

        /* buscar turmas de uma etapa */
        $scope.buscarTurmas = function(etapa) {
            var promise = Servidor.buscar('turmas',{etapa: etapa, 'unidadeEnsino': sessionStorage.getItem('unidade')});
            promise.then(function(response) {
                var turmas = response.data;
                $scope.requisicoes = 0;
                turmas.forEach(function(t) {
                    $scope.requisicoes++;
                    Servidor.buscarUm('turmas', t.id).then(function(response) {
                        Servidor.buscar('periodos', {calendario: response.data.calendario.id}).then(function(response) {
                            t.periodos = response.data;
                            if (--$scope.requisicoes === 0) {
                                $scope.turmas = angular.copy(turmas);
                                $scope.turmasSelecionadas = [];
                                $('.tooltipped').tooltip('remove');
                                $timeout(function(){
                                    $('.tooltipped').tooltip({delay: 50});
                                },50);
                            }
                        });
                    });
                });                    
            });
        };

        /* carrega alunos de uma turma */
        $scope.carregarAlunos = function(turma)  {
            $scope.turma = turma;
            console.log($scope.turma);
            var promise = Servidor.buscar('enturmacoes',{turma:turma.id});
            promise.then(function(response) {
                $('.tooltipped').tooltip('remove');
                $timeout(function(){
                    $('.tooltipped').tooltip({delay: 50});
                },50);
                $scope.alunos = response.data;
                $scope.alunosSelecionados = [];
                if(!$scope.alunos.length){
                    Servidor.customToast('Esta turma não possui alunos.');
                }
                else{
                    $scope.alunosSelecionados = [];
                    $scope.listaAlunos = true;
                }
            });
        };

        /* Selecionar Todas as Turmas da etapa selecionada */
         $scope.selecionarTodasTurmas = function(){
            if($scope.turmasSelecionadas.length === $scope.turmas.length){
                var checked = false;
                $scope.turmasSelecionadas = [];
            } else {
                checked = true;
                $scope.turmasSelecionadas = $scope.turmas;
            }
            for (var i = 0; i < $scope.turmas.length; i++) {
                $('#tur' + $scope.turmas[i].id).prop('checked', checked);
                $('#tur' + $scope.turmas[i].id).css("background-color","grey");
            }
        };

        /* Selecionar uma ou mais turmas da etapa selecionada */
        $scope.selecionarTurma = function (turma) {
            var achou = false;
            var vazio = false;
            if (!$scope.turmasSelecionadas.length) {
                $scope.turmasSelecionadas.push(turma);
                vazio = true;
                achou = true;
            }
            for (var i = 0; i < $scope.turmasSelecionadas.length && vazio === false; i++) {
                if (turma.id === $scope.turmasSelecionadas[i].id) {
                    $scope.turmasSelecionadas.splice(i, 1);
                    achou = true;
                }
            }
            if (!achou) {
                $scope.turmasSelecionadas.push(turma);
            }
        };

        /* Selecionar Todos os alunos da turma selecionada */
        $scope.selecionarTodosAlunos =  function(){
            if($scope.alunosSelecionados.length === $scope.alunos.length){
                var checked = false;
                $scope.alunosSelecionados = [];
            } else {
                checked = true;
                $scope.alunosSelecionados = angular.copy($scope.alunos);
            }
            for (var i = 0; i < $scope.alunos.length; i++) {
                $('#alu' + $scope.alunos[i].id).prop('checked', checked);
                $('#alu' + $scope.alunos[i].id).css("background-color","grey");
            }
        };

        /* Selecionar um ou mais alunos da turma selecionada */
        $scope.selecionarAluno = function(aluno){
            var achou = false;
            $scope.alunosSelecionados.forEach(function (a, i) {
                if (aluno.id === a.id) {
                    $scope.alunosSelecionados.splice(i, 1);
                    achou = true;
                }
            });
            if (!achou) {
                $scope.alunosSelecionados.push(angular.copy(aluno));
            }
        };

        /* Gerar Pdf-Boletim de todas as turmas ou todos alunos de uma turma .*/
        $scope.gerarPdf = function () {
            if ($scope.listaAlunos === true) {
                $scope.requisicoes = 0;
                $scope.alunosSelecionados.forEach(function(e,i){
                    $scope.requisicoes++;
                    var promise = Servidor.buscar('disciplinas-cursadas',{matricula:e.matricula.id, status:'CURSANDO'});
                    promise.then(function (response) {
                        e.disciplinasCursadas = response.data;
                        if(!e.disciplinasCursadas.length){
                            $scope.alunosSelecionados.splice(i,1);
                        }
                        e.disciplinasCursadas.forEach(function (d) {
                            var promise = Servidor.buscar('medias', {disciplinaCursada: d.id});
                            promise.then(function (response) {
                                d.medias = response.data;
                                $timeout(function() {
                                    makePdf.boletimEscolar($scope.alunosSelecionados);
                                }, 500);                                        
                                //d.medias.forEach(function(m) { m.frequencias = []; });
                                
                                /*var promise = Servidor.buscar('frequencias',{disciplina:d.id});
                                promise.then(function(response){
                                    d.frequencias = response.data;
                                    d.frequencias.forEach(function(f) {
                                        $scope.turma.periodos.forEach(function(p) {
                                            if (dateTime.dateBetween(f.aula.dia.data, p.dataInicio, p.dataTermino)) {
                                                d.medias[parseInt(p.media)-1].frequencias.push(f);
                                            }
                                        });
                                    });
                                    if(--$scope.requisicoes === 0){
                                        $timeout(function() {
                                            makePdf.boletimEscolar($scope.alunosSelecionados);
                                        }, 500);                                        
                                    }
                                });*/
                            });
                        });
                    });
                });
            }
        };

        $scope.preparaVoltar = function() {
            $scope.listaAlunos = false;
        };

        $scope.fecharFormulario = function(){
            $scope.editando = false;
        };

        $scope.inicializar = function(){
            $scope.buscarCursos();
            $timeout(function(){
                $('ul.tabs').tabs();
                $('select').material_select();
                Servidor.entradaPagina();
            }, 500);
        };

        $scope.inicializar();
    }]);
})();