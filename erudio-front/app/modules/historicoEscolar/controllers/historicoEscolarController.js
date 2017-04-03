(function () {
    var historicoEscolarModule = angular.module('historicoEscolarModule', ['servidorModule', 'historicoEscolarDirectives']);
    
    historicoEscolarModule.controller('historicoEscolarController', ['$scope', 'makePdf', 'Servidor', 'Restangular', '$timeout', '$templateCache','$compile', function ($scope, makePdf, Servidor, Restangular, $timeout, $templateCache, $compile) {
        $templateCache.removeAll();
        
        $scope.matriculaBusca = {
            'aluno': '',
            'status': '',
            'codigo': '',
            'curso': null
        };
       
        $scope.editando = false;
        $scope.inicializar = function(){
            $timeout(function(){
                $(document).ready(function() {
                    $('select').material_select();
                });
                Servidor.entradaPagina();
            },500);
            $scope.buscarVinculo();
            $scope.buscarAlocacao();
        };
        
        
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        $scope.mostraLoader = function () { $scope.loader = true; };
        $scope.fechaLoader = function () { $scope.loader = false; };
        
        $scope.fecharFormulario = function(){
            $scope.editando = false;
            $scope.matricula = {};
        };
        
        $scope.limparBusca = function(){
          $scope.matriculaBusca = {};  
        };
        
        $scope.buscarPessoas = function() {
            if ($scope.nomePessoa && $scope.nomePessoa !== undefined && $scope.nomePessoa.length > 3) {
                var params = { 'nome': '', 'cpf': '' };
                if (parseInt($scope.nomePessoa)) {
                    params.cpf = $scope.nomePessoa;
                } else {
                    params.nome = $scope.nomePessoa;
                }
                var promise = Servidor.buscar('pessoas', params);
                promise.then(function(response) {
                    if (response.data.length) {
                        $scope.pessoas = response.data;
                    } else {
                        $scope.pessoas = [];
                    }
                });
            } else {
                $scope.pessoas = [];
            }
        };
        $scope.buscarAlocacao = function(){
            var promiseAlocacao = Servidor.buscarUm('alocacoes', sessionStorage.getItem('alocacao'));
            promiseAlocacao.then(function(response){
                $scope.alocacao = response.data;
                $scope.buscarCursos();
            });
        };
        
        $scope.buscarVinculo = function() {
            var promise = Servidor.buscarUm('vinculos', sessionStorage.getItem('vinculo'));
            promise.then(function(response) {
                $scope.vinculo = response.data;
            });
        };
        
        $scope.buscarHistorico = function(matricula){
            $scope.disciplinasCursadas = [];
            var encontrou;
            $scope.etapas = [];
                 if(matricula.aluno.genero === 'f' || matricula.aluno.genero === 'F'){
                matricula.aluno.genero = 'a aluna';
            }else {
                matricula.aluno.genero = 'o aluno';
            }
                var promiseEtapa = Servidor.buscar('etapas', {curso: matricula.curso.id});
                promiseEtapa.then(function(response){
                     response.data.forEach(function(etapa){
                       var promiseDisciplina = Servidor.buscar('disciplinas-cursadas', {etapa: etapa.id, matricula: matricula.id});
                       promiseDisciplina.then(function(response){
                           if(response.data.length){
                               $scope.etapas.push(etapa);
                               response.data.forEach(function(disciplina, i){
                                   encontrou = false;
                                   $scope.disciplinasCursadas.forEach(function(disc, j){
                                        if (disc.nome === disciplina.nomeExibicao) {
                                            disc.notas.push(8);
                                            encontrou = true;
                                        }
                                        if (encontrou === false && $scope.disciplinasCursadas.length-1 === j) {
                                            $scope.disciplinasCursadas.push({nome:angular.copy(disciplina.nomeExibicao), notas: ['\t\t\t\t' + 1]});
                                            encontrou = true;
                                        }                                        
                                   });
                                    if (encontrou === false) {
                                        $scope.disciplinasCursadas.push({nome:angular.copy(disciplina.nomeExibicao), notas: ['\t\t\t\t' + 1]});
                                    }
                                });
                               console.log($scope.disciplinasCursadas);
                           }
                       });
                    });
                });
          
        };
        
        

        $scope.chamarHistoricoEscolar = function (matricula) {
            var body = [];
            var body2 = [];
            var widths = [];
            var headers = [];

            var encontrou = false;
            var disciplinasCursadas = [];
            headers.push({text: 'DISCIPLINAS', bold: true, style: 'tabela'});
            widths.push('auto');
            var promiseEtapa = Servidor.buscar('etapas', {curso: matricula.curso.id});
            promiseEtapa.then(function (responseEtapa) {
                responseEtapa.data.forEach(function (etapa) {
                    var promiseDisciplina = Servidor.buscar('disciplinas-cursadas', {etapa: etapa.id, matricula: matricula.id});
                    promiseDisciplina.then(function (response) {
                        if (response.data.length) {
                            var cursadas = response.data;
                            headers.push({text: etapa.nomeExibicao, bold: true, style: 'tabela'});
                            widths.push('*');
                            response.data.forEach(function (disciplinas) {
                                disciplinasCursadas.forEach(function (cursadas) {
                                    if (cursadas.nomeExibicao === disciplinas.nomeExibicao) {
                                        cursadas.notas.push(8);
                                        encontrou = true;
                                    }
                                });
                                if (encontrou === false) {
                                    disciplinasCursadas.push({nome: disciplinas.nomeExibicao, notas: ['\t\t\t\t' + 1]});
                                    console.log(disciplinasCursadas);
                                }
                            });
                            body.push(headers);
                            disciplinasCursadas.forEach(function (disciplina) {
                                var linha = [];
                                linha.push(disciplina.nome);
                                disciplina.notas.forEach(function (n) {
                                    linha.push(n + '');
                                });
                                body.push(linha);
                                linha = [];
                            });
                            if (cursadas.length) {
                                var reprovado = false;
                                var aprovado = false;
                                var cursando = false;
                                var incompleto = false;
                                var etapasFinalizadas = {etapa: null, situacao: null};
                                cursadas.forEach(function (disciplina) {
                                    if (disciplina.status === 'CURSANDO') {
                                        cursando = true;
                                    } else if (disciplina.status === 'APROVADO') {
                                        aprovado = true;
                                    } else if (disciplina.status === 'REPROVADO') {
                                        reprovado = true;
                                    } else if (disciplina.status === 'INCOMPLETO') {
                                        incompleto = true;
                                    }
                                });
                                if (cursando) {

                                }
                                else if (aprovado && !reprovado) {
                                    etapasFinalizadas.push({etapa: etapa.nome, situacao: 'APROVADO'});
                                } else if (reprovado) {
                                    etapasFinalizadas.push({etapa: etapa.nome, situacao: 'REPROVADO'});
                                } else if (incompleto) {
                                    etapasFinalizadas.push({etapa: etapa.nome, situacao: 'INCOMPLETO'});
                                }
                                var text = [];
                                if (etapasFinalizadas.length) {
                                    etapasFinalizadas.forEach(function (t) {
                                        text.push(t.etapa, '2016', t.situacao, matricula.unidadeEnsino.nome, matricula.unidadeEnsino.endereco.cidade.nome, matricula.unidadeEnsino.endereco.cidade.estado.sigla);
                                    });
                                    var head2 = [
                                        {text: 'SÉRIE', bold: true},
                                        {text: 'ANO', bold: true},
                                        {text: 'RESULTADO FINAL', bold: true},
                                        {text: 'ESTABELECIMENTO DE ENSINO', bold: true},
                                        {text: 'MUNICÍPIO', bold: true},
                                        {text: 'UF', bold: true}
                                    ];
                                    body2.push(head2);
                                    body2.push(text);
                                }                                                                                
                                setTimeout(function() {
                                    makePdf.pdfHistoricoEscolar(matricula, body, body2, widths, $scope.vinculo);
                                }, 250);
                            }
                        };
                    });
                });
            });
        };
            
        $scope.buscarCursos = function () {
            var promiseInstituicao = Servidor.buscarUm('instituicoes', $scope.alocacao.instituicao.id);
            promiseInstituicao.then(function(response){
               var instituicao = response.data; 
               $scope.cursos = instituicao.cursos;
               $timeout(function(){
                   $('#cursoBusca').material_select();
               },50);
            });
        };
        
        $scope.buscarMatriculas = function (matricula, pagina, origem) {
            if (pagina !== $scope.paginaAtual) {
                if (origem === 'botao') {
                    $scope.mostraLoader();
                    $scope.matriculas = [];
                }
                if (!pagina) {
                    $scope.paginaAtual = 0;
                    $(".paginasLista0").addClass('active');
                } else {
                    $scope.paginaAtual = pagina;
                }
                if (origem === 'botao' && $scope.qtdPaginas || pagina === '') {
                    for (var i = 1; i <= $scope.qtdPaginas; i++) {
                        $(".paginasLista" + parseInt(i)).remove();
                    }
                }
                
                if (matricula.codigo !== '' || matricula.aluno !== '' || matricula.unidade !== null || matricula.curso !== null || matricula.status !== null) {
                    $scope.mostraLoader();
                    var promise = Servidor.buscar('matriculas', {'page': pagina, 'codigo': matricula.codigo, 'aluno_nome': matricula.aluno,
                        'unidadeEnsino': sessionStorage.getItem('unidade'), 'curso': matricula.curso, 'status': matricula.status});
                    promise.then(function (response) {
                        $('#btn-cadastro-matricula').show();
                        if (response.data.length === 0) {
                            Servidor.customToast('Nenhuma Matrícula encontrada.');
                            $scope.fechaLoader();
                        } else {
                            if (origem === 'botao') {
                                $scope.qtdPaginas = Math.ceil(response.data.length / 50);
                                for (var i = 1; i < $scope.qtdPaginas; i++) {
                                    var a = '<li class="waves-effect paginasLista' + i + '" data-ng-click="alterarPagina(' + parseInt(i) + '); buscarMatriculas(matriculaBusca, ' + parseInt(i) + ');"><a href="#!">' + parseInt(i + 1) + '</a></li>';
                                    $(".paginasLista" + parseInt(i - 1)).after($compile(a)($scope));
                                }
                            }
                            $scope.matriculas = response.data;
                            $('.tooltipped').tooltip('remove');
                            $timeout(function () {  
                                window.scrollTo(0, 600);                     
                                $('.modal-trigger').modal();
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
                                $scope.fechaLoader();
                            }, 500);
                        }
                    });
                } else {
                    $scope.fechaLoader();
                    Servidor.customToast('Preencha ao menos um item para buscar');
                }
            }
        };
        
        $scope.carregar= function(matricula){
         
          var promise = Servidor.buscarUm('matriculas', matricula.id);
          promise.then(function(response){
             $scope.matricula = response.data; 
             $scope.editando = true;
             $scope.buscarHistorico(matricula);
          });
        };
        
       
        
        $scope.inicializar();
        
        
    }]);
})();