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
    var turmaProfessoresModule = angular.module('turmaProfessoresModule', ['servidorModule', 'turmaDirectives', 'erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    turmaProfessoresModule.controller('TurmaProfessoresController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$routeParams', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $routeParams) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.isAdmin = Servidor.verificaAdmin(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('TURMA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateCustom('turmas','professores');
        //ATRIBUTOS
        $scope.disciplinasOfertadasTurma = []; $scope.progresso = false; $scope.cortina = false; $scope.titulo = "Professores da Turma"; $scope.mostraProfessores = true; $scope.mostraAlunos = true;
        $scope.disciplinaProfessor = { 'id': null, 'professores': [{'id': null}] };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //PREPARA VOLTAR
        $scope.prepararVoltar = function (objeto) { window.location = $scope.config.dominio+'/#/turmas/' + $routeParams.id; };
        //MOSTRA LABELS MENU FAB
        $scope.mostrarLabels = function () { $('.toolchip').fadeToggle(250); };
        //GUARDA ALOCAÇÃO PARA REMOVER
        $scope.prepararRemoverProfessor = function (alocacao, disciplina) { $scope.disciplinaRemover = disciplina; $scope.alocacaoRemover = alocacao; $('#remove-modal-professor').modal('open'); };
        
        //ADICIONAR PROFESSOR
        $scope.adicionarProfessor = function (fromModal) {
            $scope.disciplinaProfessor.id = null; $scope.nomeProfessor = '';
            $timeout(function () {
                if(!fromModal) {
                    $('#modal-adicionar-professor').modal('open');
                    $timeout(function () { $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' }); }, 200);
                }
                $('#disciplinaProfessor').material_select('destroy'); $('#disciplinaProfessor').material_select();
            }, 200);
        };
        
        //BUSCA DISCIPLINAS DO PROFESSOR
        $scope.professoresDisciplinas = function (turma) {
            $scope.mostraProgresso();
            $scope.disciplinasOfertadasProfessores(turma);
            $('.tooltipped').tooltip('remove'); $timeout(function() { $('.tooltipped').tooltip({delay: 50}); $scope.fechaProgresso(); }, 250);
        };
        
        //RETORNA OS DIAS DA SEMANA
        $scope.getDiaSemanaNome = function(diaSemana) {
            switch(diaSemana) {
                case "1": return "Domingo"; case "2": return "Segunda"; case "3": return "Terça"; case "4": return "Quarta";
                case "5": return "Quinta"; case "6": return "Sexta"; case "7": return "Sábado"; default : return null;
            }
        };
        
        //BUSCA DISCIPLINA DE ACORDO COM O VINCULO
        $scope.disciplinasOfertadasProfessores = function (turma) {
            $scope.mostraProgresso(); $scope.disciplinasOfertadasTurma = [];
            var promise = Servidor.buscar('disciplinas-ofertadas', {'turma': turma.id});
            promise.then(function (response) {
                if (response.data.length) {
                    $scope.disciplinasOfertadasTurma = response.data;
                    response.data.forEach(function (d, indexD) {
                        var promiseD = Servidor.buscarUm('disciplinas-ofertadas', d.id);
                        promiseD.then(function (responseD) {
                            $scope.disciplinasOfertadasTurma[indexD] = responseD.data;
                            if (responseD.data.professores.length > 0) {
                                responseD.data.professores.forEach(function (a, $index) {
                                    var promise = Servidor.buscarUm('alocacoes', a.id);
                                    promise.then(function (responseA) {
                                        responseD.data.professores[$index] = responseA.data;
                                        if ($index === responseD.data.professores.length - 1) {
                                            $scope.disciplinasOfertadasTurma[indexD] = responseD.data;
                                            $('.tooltipped').tooltip('remove'); $timeout(function () { $('.tooltipped').tooltip({delay: 50}); }, 50);
                                        }
                                    });
                                });
                            }
                            if (indexD === $scope.disciplinasOfertadasTurma.length - 1) { $scope.diasDisciplinas(); }
                        });
                    });
                } else { Servidor.customToast('Turma não possui nenhuma disciplina cadastrada'); $scope.fechaProgresso(); }
            });
        };
        
        //PEGA DIAS DA DISCIPLINA
        $scope.diasDisciplinas = function () {
            var cont = 0;
            $scope.disciplinasOfertadasTurma.forEach(function(d, index){
                var promiseH = Servidor.buscar('horarios-disciplinas', {'disciplina': d.id});
                promiseH.then(function(responseH){
                    cont++;
                    if(responseH.data.length){
                        $scope.disciplinasOfertadasTurma[index].dias = [];
                        responseH.data.forEach(function(h, indexH){
                            if($scope.disciplinasOfertadasTurma[index].dias.length > 0){
                                var cont = 0;
                                for (var i = 0; i < $scope.disciplinasOfertadasTurma[index].dias.length;i++) {
                                    if(h.horario.diaSemana.diaSemana === $scope.disciplinasOfertadasTurma[index].dias[i]){ cont++; }
                                    if(i === $scope.disciplinasOfertadasTurma[index].dias.length-1 && cont === 0){ $scope.disciplinasOfertadasTurma[index].dias.push(h.horario.diaSemana.diaSemana); }
                                }
                            }else{ $scope.disciplinasOfertadasTurma[index].dias.push(h.horario.diaSemana.diaSemana); }
                        });
                    }
                    if(cont === $scope.disciplinasOfertadasTurma.length){ $scope.converteDiasSemana(); }
                });

            });
        };
        
        //CONVERTE NUMERO PARA NOME
        $scope.converteDiasSemana = function () {
            $scope.mostraProgresso();
            $scope.disciplinasOfertadasTurma.forEach(function(d, index){
                if(d.dias){
                    d.dias.sort();
                    d.dias.forEach(function(dia, i){
                        switch (dia){
                            case "2": $scope.disciplinasOfertadasTurma[index].dias[i] = 'Segunda'; break;
                            case "3": $scope.disciplinasOfertadasTurma[index].dias[i] = 'Terça'; break;
                            case "4": $scope.disciplinasOfertadasTurma[index].dias[i] = 'Quarta'; break;
                            case "5": $scope.disciplinasOfertadasTurma[index].dias[i] = 'Quinta'; break;
                            case "6": $scope.disciplinasOfertadasTurma[index].dias[i] = 'Sexta'; break;
                        };
                    });
                }
                if(index === $scope.disciplinasOfertadasTurma.length-1){ $scope.fechaProgresso(); }
            });
        };
        
        //ABRE MODAL HORARIOS
        $scope.abrirModalHorarios = function(disciplina) {
            $scope.quadroHorario.modelo.posicaoIntervalo = parseInt($scope.quadroHorario.modelo.posicaoIntervalo);
            var promise = Servidor.buscar('horarios-disciplinas', {disciplina: disciplina.id});
            promise.then(function(response) {
                $scope.disciplina = disciplina; $scope.horarios = response.data;
                var aulas = parseInt($scope.quadroHorario.modelo.quantidadeAulas);
                $scope.horarios.forEach(function(h) {
                    var count = 1;
                    $scope.quadroHorario.horarios.forEach(function(hs) {                            
                        if(h.horario.id === hs.id) { h.naula = count; }
                        if(++count > aulas) { count = 1; }
                    });
                });                    
                $('#horarios-disciplina').modal('open');
            });
        };
        
        //PEGA MODAL INFO PROFESSOR
        $scope.carregaInfoProfessor = function (alocacao) {
            $scope.mostraProgresso(); $scope.funcionario = null; $scope.telefones = []; $scope.disciplinasMinistradas = []; $scope.alocacao = alocacao;
            $scope.alocacao.disciplinasMinistradas.forEach(function (d) {
                var promise = Servidor.buscarUm('disciplinas-ofertadas', d.id);
                promise.then(function (response) { $scope.disciplinasMinistradas.push(response.data); });
            });
            var promise = Servidor.buscarUm('pessoas', alocacao.vinculo.funcionario.id);
            promise.then(function (response) {
                $scope.funcionario = response.data;
                var promise = Servidor.buscar('telefones', {'pessoa': $scope.funcionario.id});
                promise.then(function (response) { $scope.telefones = response.data; $('#info-professor').modal('open'); $scope.fechaProgresso(); });
            });
        };
        
        //REMOVE ALOCAÇÃO
        $scope.removerProfessor = function () {
            var promise = Servidor.buscarUm('disciplinas-ofertadas', $scope.disciplinaRemover.id);
            promise.then(function (response) {
                $scope.disciplina = response.data;
                $scope.disciplina.professores.forEach(function (a, $index) {
                    if (a.id === $scope.alocacaoRemover.id) {
                        $scope.disciplina.professores.splice($index, 1);
                        var promise = Servidor.finalizar($scope.disciplina, 'disciplinas-ofertadas', 'Disciplina Ofertada');
                        promise.then(function (response) {
                            $scope.disciplinasOfertadasTurma.forEach(function (d, $indexD) {
                                if (d.id === response.data.id) {
                                    var dias = $scope.disciplinasOfertadasTurma[$indexD].dias;
                                    $scope.disciplinasOfertadasTurma[$indexD] = response.data; $scope.disciplinasOfertadasTurma[$indexD].dias = dias;
                                    $scope.disciplinasOfertadasTurma[$indexD].professores.forEach(function (a) {
                                        var promise = Servidor.buscarUm('alocacoes', a.id);
                                        promise.then(function (responseA) {
                                            $scope.disciplinasOfertadasTurma[$indexD].professores.forEach(function (p, $index) {
                                                if (p.id === responseA.data.id) { $scope.disciplinasOfertadasTurma[$indexD].professores[$index] = responseA.data; }
                                            });
                                        });
                                    });
                                }
                            });
                        });
                    }
                });
                $timeout(function () { $('#remove-modal-professor').modal('close'); }, 300);
            });
        };
        
        //SALVA PROFESSORES
        $scope.finalizarProfessores = function () {
            if($scope.disciplinaProfessor.professores[0] === undefined || !$scope.disciplinaProfessor.professores[0].id) { return Servidor.customToast('Selecione um professor.'); }
            $scope.mostraProgresso(); var cont = 0; var adicionaProfessor = false;
            var promise = Servidor.buscarUm('disciplinas-ofertadas', $scope.disciplinaProfessor.id);
            promise.then(function (response) {
                $scope.disciplina = response.data;
                if ($scope.disciplina.professores.length) {
                    $scope.disciplinaProfessor.professores.forEach(function (f) {
                        $scope.disciplina.professores.forEach(function (a) {
                            if (f.id === a.id) {
                                Servidor.customToast('Professor já ministra esta disciplina.'); $scope.nomeProfessor = ''; $scope.professores = []; $scope.fechaProgresso(); return false;
                            } else { cont++; }
                            if (cont === $scope.disciplina.professores.length) { $scope.disciplina.professores.push(f); adicionaProfessor = true; }
                        });
                    });
                } else { adicionaProfessor = true; $scope.disciplina.professores = $scope.disciplinaProfessor.professores; }
                if (adicionaProfessor) {
                    var promise = Servidor.finalizar($scope.disciplina, 'disciplinas-ofertadas', null);
                    promise.then(function (response) {
                        Servidor.customToast('Professor adicionado com sucesso.');
                        $scope.disciplinasOfertadasTurma.forEach(function (d, $indexD) {
                            if (d.id === response.data.id) {
                                var dias = $scope.disciplinasOfertadasTurma[$indexD].dias; $scope.disciplinasOfertadasTurma[$indexD] = response.data;
                                $scope.disciplinasOfertadasTurma[$indexD].dias = dias;
                                $scope.disciplinasOfertadasTurma[$indexD].professores.forEach(function (a) {
                                    var promise = Servidor.buscarUm('alocacoes', a.id);
                                    promise.then(function (responseA) {
                                        $scope.disciplinasOfertadasTurma[$indexD].professores.forEach(function (p, $index) {
                                            if (p.id === responseA.data.id) { $scope.disciplinasOfertadasTurma[$indexD].professores[$index] = responseA.data; }
                                        });
                                    });
                                });
                            }
                        });
                        $timeout(function () { $scope.fechaProgresso(); $scope.adicionarProfessor(true); }, 300);
                    });
                }
            });
        };
        
        //BUSCA ALOCACAO
        $scope.buscarAlocacoes = function () {
            if (!$scope.nomeProfessor) { $scope.professores = [];
            } else {
                if ($scope.nomeProfessor.length >= 3) {
                    var promise = Servidor.buscar('alocacoes', {'funcionario_nome': $scope.nomeProfessor, 'professor': 1, 'instituicao': $scope.turma.unidadeEnsino.id});
                    promise.then(function (response) {
                        if (response.data.length) { $timeout(function () { $scope.professores = response.data; }, 200);
                        } else { Servidor.customToast('Nenhum professor encontrado'); }
                    });
                }
            }
        };
        
        //CARREGA FUNCIONARIO
        $scope.carregaFuncionario = function (professor) {
            $scope.nomeProfessor = professor.vinculo.funcionario.nome; $("#nomeProfessor").val($scope.nomeProfessor); $scope.disciplinaProfessor.professores[0].id = professor.id;
            $timeout(function () { Servidor.verificaLabels(); }, 100);
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.material-tooltip').remove(); var promise = Servidor.buscarUm('turmas',$routeParams.id); $('.title-module').html($scope.titulo);
            promise.then(function(response){
                $scope.turma = response.data; $scope.professoresDisciplinas($scope.turma); $('#modal-adicionar-professor').modal();
                var promise = Servidor.buscarUm('quadro-horarios', $scope.turma.quadroHorario.id);
                promise.then(function(response) { $scope.quadroHorario = response.data; });
                $timeout(function () { $('.tooltipped').tooltip({delay: 50}); Servidor.entradaPagina();}, 1000);
            });
        };
        
        $scope.inicializar(); 
    }]);
})();
