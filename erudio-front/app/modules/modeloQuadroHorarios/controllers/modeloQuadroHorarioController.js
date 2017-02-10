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
    
    var modeloQuadroHorarioModule = angular.module('modeloQuadroHorarioModule', ['servidorModule', 'modeloQuadroHorarioDirectives']);
    modeloQuadroHorarioModule.controller('modeloQuadroHorarioController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', function ($scope, Servidor, Restangular, $timeout, $templateCache) {

        $templateCache.removeAll();

        /* Atributos Específicos */
        $scope.modeloQuadroHorarios = null;
        $scope.modeloQuadroHorario = {
            'nome': null,
            'curso': {id: null},
            'quantidadeAulas': null,
            'duracaoAula': null,
            'duracaoIntervalo': null,
            'posicaoIntervalo': null
        };
        $scope.etapa = {'nome':''};
        $scope.etapas = [];
        $scope.nomeEtapa = '';

        /* Atributos de controle da página */
        $scope.editando = false;
        $scope.loader = false;
        $scope.progresso = false;

        /* Controle da barra de progresso */
        $scope.mostraProgresso = function () { $scope.progresso = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; };
        $scope.mostraLoader = function () { $scope.loader = true; };
        $scope.fechaLoader = function () { $scope.loader = false; };

        /* Buscando modeloQuadroHorarios - Lista */
        $scope.buscarModeloQuadroHorarios = function () {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('modelo-quadro-horarios', null);
            $('.tooltipped').tooltip('remove');
            promise.then(function (response) {
                $scope.modeloQuadroHorarios = response.data;
                $timeout(function () {
                    $scope.fechaProgresso();
                    $('.tooltipped').tooltip({delay: 50});
                }, 500);
            });
        };

        /* Buscando apenas um objeto modeloQuadroHorarios */
        $scope.buscarUmModeloQuadroHorario = function (id) {
            var promise = Servidor.buscarUm('modelo-quadro-horarios', id);
            promise.then(function (response) {
                $scope.modeloQuadroHorario = response.data;
                $scope.modeloQuadroHorario.posicaoIntervalo--;
                $timeout(function(){Servidor.verificaLabels();},100);
            });
        };

        /* salva o objeto modeloQuadroHorario */
        $scope.finalizar = function () {
            $scope.mostraProgresso();
            if($scope.validar('validate-modelo-quadro')) {
                var qtdAulas = parseInt($scope.modeloQuadroHorario.quantidadeAulas);
                var posInt = parseInt($scope.modeloQuadroHorario.posicaoIntervalo);
                if (qtdAulas > posInt || (qtdAulas === 1 && posInt <= 1)) {
                    if ($scope.modeloQuadroHorario.duracaoAula.length > 2) {
                        var tamanho = $scope.modeloQuadroHorario.duracaoAula.length;
                        var array = $scope.modeloQuadroHorario.duracaoAula.split('');
                        $scope.modeloQuadroHorario.duracaoAula = array[tamanho-2]+array[tamanho-1];
                    }
                    if ($scope.modeloQuadroHorario.duracaoIntervalo.length > 2) {
                        var tamanho = $scope.modeloQuadroHorario.duracaoIntervalo.length;
                        var array = $scope.modeloQuadroHorario.duracaoIntervalo.split('');
                        $scope.modeloQuadroHorario.duracaoIntervalo = array[tamanho-2]+array[tamanho-1];
                    }
                    $scope.modeloQuadroHorario.posicaoIntervalo++;
                    var result = Servidor.finalizar($scope.modeloQuadroHorario, 'modelo-quadro-horarios', 'Modelo de Horário');
                    result.then(function(response){                        
                        $timeout(function () {
                            $scope.fecharFormulario();
                        },300);                            
                    });
                } else {
                    Servidor.customToast('Quantidade de aulas inválida.');
                    $scope.fechaProgresso();
                }
            } else {
                Materialize.toast('Número de aulas inválido.', 1000);
                $scope.fechaProgresso();
            }
        };

        $scope.validar = function(id){
            if(Servidor.validar(id)){
                return true;
            }else{
                return false;
            }
        };
        
        /* Busca de cursos*/
        $scope.buscarCursos = function () {
            var promise = Servidor.buscar('cursos', null);
            promise.then(function(response){
                $scope.cursos = response.data;
                $timeout(function(){ 
                    $('#cursoModelo').material_select('destroy'); 
                    $('#cursoModelo').material_select(); 
                },100);
            });
        };
        
        /*Verifica quais cursos já possuem quadro de horario modelo*/
        $scope.verificarCursos = function () {
            var cont = 0;
            $scope.modeloQuadroHorarios.forEach(function(m, index){
                var promise = Servidor.buscarUm('modelo-quadro-horarios', m.id);
                promise.then(function(response){
                    cont++;
                    $scope.modeloQuadroHorarios[index] = response.data;
                    if(cont === $scope.modeloQuadroHorarios.length){
                        $scope.cursos.forEach(function(c, indexC){
                           $scope.modeloQuadroHorarios.forEach(function(h, indexH){
                                if(c.id === h.curso.id){
                                    $scope.cursos.splice(indexC, 1);
                                }                               
                           });
                        });
                    }
                });
            });
            $timeout(function(){ 
                $('#cursoModelo').material_select('destroy'); 
                $('#cursoModelo').material_select(); 
            },100);            
        };                       

        /* Verifica o Curso*/
        $scope.verificaSelectCurso = function (id) {
            if (id === $scope.modeloQuadroHorario.curso.id){
                return true;
            }              
        };       

        /* Verifica a etapa*/
        $scope.verificaSelectEtapa = function (id) {
            if (id === $scope.modeloQuadroHorario.etapa.id){
                return true;
            }
        };

        /* Abre o formulário do quadro de horario modelo */
        $scope.carregarFormulario = function (modeloQuadroHorario) { 
            $scope.mostraLoader(); Servidor.cardSai(['.info-card','.lista-geral', '.add-btn'], true);
            $scope.acao = "Cadastrar";
            if (modeloQuadroHorario){
                $scope.buscarUmModeloQuadroHorario(modeloQuadroHorario.id);
                $scope.acao = "Editar";
            }
            $timeout(function() {
                $scope.buscarCursos();
                $scope.editando = true;
                $scope.fechaLoader();
                Servidor.verificaLabels();
                $timeout(function(){$('#cursoModelo').focus();},150);
                $('#cursoModelo, #etapa').material_select('destroy');
                $('#cursoModelo, #etapa').material_select();   
                $timeout(function(){ Servidor.cardEntra('.form-geral'); },500);
                Servidor.inputNumero();
            }, 300);                
        };

        /* fecha o formulario */
        $scope.fecharFormulario = function () {
            $scope.mostraProgresso();
            $scope.cursos = [];
            $scope.editando = false;
            $scope.reiniciar();
            $timeout(function (){                
                Servidor.verificaLabels(); 
                $scope.buscarModeloQuadroHorarios();
            }, 100);
        };

        /*Reinicia estrutura de ModeloQuadroHorario */
        $scope.reiniciar = function () {
            $scope.modeloQuadroHorario = {
                nome: null,
                quantidadeAulas: null,
                duracaoAula: null,
                duracaoIntervalo: null,
                posicaoIntervalo: null,
                curso: {id: null}
            };
        };
        
        $scope.prepararVoltar = function(objeto) {
            if (objeto.nome && !objeto.id) {                
                $('#modal-certeza-modelo-quadro').openModal();
            } else {
                $scope.mostraProgresso();
                $scope.fecharFormulario();
            }
        };

        /* Inicializando */
        $scope.inicializar = function (inicializaContador, primeiraVez) {
            $('.tooltipped').tooltip('remove');
            $timeout(function () {
                if (inicializaContador) {
                    $('.counter').each(function () {
                        $(this).characterCounter();
                    });
                }                      
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
                $('#etapa').dropdown({
                        inDuration: 300,
                        outDuration: 225,
                        constrain_width: true, // Does not change width of dropdown to that of the activator
                        hover: false, // Activate on hover
                        gutter: 40, // Spacing from edge
                        belowOrigin: true, // Displays dropdown below the button
                        alignment: 'left' // Displays dropdown with edge aligned to the left of button
                    }
                );
                Servidor.verificaLabels();
                if (primeiraVez) { Servidor.entradaPagina(); };                
            }, 1000);
        };

        /* Guarda o modulo para futura remoção e abre o modal de confirmação */
        $scope.prepararRemover = function (modeloQuadroHorario) {
            var permissaoExcluir = true;
            var promise = Servidor.buscar('quadro-horarios', null);
            promise.then(function(response){                
                if (response.data.length) {
                    var cont = 0;
                    response.data.forEach(function(q){
                        var promiseQ = Servidor.buscarUm('quadro-horarios', q.id);
                        promiseQ.then(function(responseQ){
                            cont++;
                            if(responseQ.data.modelo.id === modeloQuadroHorario.id){                            
                                permissaoExcluir = false;
                            }
                            if(permissaoExcluir && cont === response.data.length){
                                $scope.modeloQuadroHorarioRemover = modeloQuadroHorario;
                                $('#remove-modal-modeloQuadroHorario').openModal();
                            }else if(!permissaoExcluir && cont === response.data.length){
                                Servidor.customToast('Modelo de Quadro de Horários não pode ser excluido.');
                            }
                        });
                    });
                } else {
                    $scope.modeloQuadroHorarioRemover = modeloQuadroHorario;
                    $('#remove-modal-modeloQuadroHorario').openModal();
                }
            });
        };

        /* Remove o modulo */
        $scope.remover = function () {
            $scope.mostraProgresso();
            Servidor.remover($scope.modeloQuadroHorarioRemover, 'Modelo de Horário');
            $timeout(function () {
                $scope.buscarModeloQuadroHorarios();
                $scope.fechaProgresso();
            }, 500);
        };

        $scope.inicializar(false, true);
        $scope.buscarModeloQuadroHorarios();
    }]);
})();
