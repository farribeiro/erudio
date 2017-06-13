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

(function (){
    var turnoModule = angular.module('turnoModule', ['servidorModule','turnoDirectives']);
    turnoModule.controller('TurnoController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', '$filter', function($scope, Servidor, Restangular, $timeout, $templateCache, $filter) {
        $templateCache.removeAll();
        
        $scope.escrita = Servidor.verificaEscrita('TURNO');
        /* Atributos Específicos */        
        $scope.turnos = []; 
        $scope.inicio = null; 
        $scope.termino = null;
        
        /* Atributos de controle da página */        
        $scope.editando = false; $scope.progresso = false;
        $scope.loader = false; $scope.acoesMobile = false;
        $scope.hasTurno = false; $scope.acao = "";
        
        /* Estrutura de turno */        
        $scope.turno = { nome:null, inicio:null, termino:null };
        
        /* Controle da barra de progresso */        
        $scope.mostraProgresso = function () { $scope.progresso = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; };
        $scope.mostraLoader = function () { $scope.loader = true; };
        $scope.fechaLoader = function () { $scope.loader = false; };
        
        /* Reinciando estrutura de turno */        
        $scope.reiniciar = function (){
            $scope.turno = { nome:null, inicio:null, termino:null };
            $scope.inicio = null; $scope.termino = null;
        };
        $scope.inicializar = function () {
            $('.tooltipped').tooltip('remove');
            $timeout(function (){            
                $('.counter').each(function(){  $(this).characterCounter(); $('.modal-trigger').leanModal(); });
                $('.tooltipped').tooltip({delay: 50});
                /*Inicializando controles via Jquery Mobile */
                if ($(window).width() < 993) {
                        $('.hora').lolliclock({autoclose: false, hour24: false});
                        $(".swipeable").on("swiperight", function () {
                            $('.swipeable').removeClass('move-right');
                            $(this).addClass('move-right');
                        });
                        $(".swipeable").on("swipeleft", function () {
                            $('.swipeable').removeClass('move-right');
                        });
                }
               $('.time').mask('00:00');
               $("#inicio, #termino").keypress(function(e) {
                    var tecla=(window.event)?event.keyCode:e.which;
                    if((tecla > 47 && tecla < 58)) {
                        return true;
                    } else{
                        if (tecla===8 || tecla===0) { return true; }
                        else  return false;
                    }
                });
                Servidor.entradaPagina();
            },1000);
        };
        
        /* Buscando turnos - Lista */        
        $scope.buscarTurnos = function() {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('turnos',null);
            promise.then(function (response){ 
                $('.tooltipped').tooltip('remove');
                $('.tooltipped').tooltip({delay: 50});
                $timeout(function() {
                    $scope.turnos = response.data;                
                    for (var i=0; i < $scope.turnos.length; i++) {
                        $scope.turnos[i].inicio = Servidor.formatarHora($scope.turnos[i].inicio);
                        $scope.turnos[i].termino = Servidor.formatarHora($scope.turnos[i].termino);
                    }                        
                },50);
                $('.tooltipped').tooltip('remove');
                $timeout(function() { 
                    $scope.fechaProgresso(); 
                    $('.tooltipped').tooltip({delay: 50});
                }, 500);              
            });
        };
        
        /* Abre o formulário de edição/cadastro */       
        $scope.carregar = function (turno){    
            $('#inicio, #termino').mask("99:99");            
            $scope.mostraLoader(); Servidor.cardSai(['.info-card','.lista-geral', '.add-btn'], true);
            $scope.acao = "Cadastrar";
            if (turno) {               
                $scope.acao = "Editar"; $scope.turno = turno;
                $scope.inicio = $scope.converterHora(turno.inicio, true);
                $scope.termino = $scope.converterHora(turno.termino, true);
            } else { $scope.reiniciar();}
            $timeout(function (){
                $timeout(function(){ $("#turnoNome").focus(); },150);                
                $scope.editando = true; $scope.fechaLoader();
                Servidor.verificaLabels();
                $timeout(function(){ Servidor.cardEntra('.form-geral'); },500);
            },100);
        };
        
        /* Guarda o turno para futura remoção e abre o modal de confirmação */
        $scope.prepararRemover = function (turno){ 
            $scope.turnoRemover = turno;
            $('#remove-modal-turno').openModal();
        };
        
        /* Remove o turno */
        $scope.remover = function (){
            $scope.mostraProgresso(); Servidor.remover($scope.turnoRemover, 'Turno');
            $timeout(function (){ $scope.fechaProgresso(); $scope.buscarTurnos(); $('.lean-overlay').hide(); },1000);
        }; 
        $scope.prepararVoltar = function(objeto) {
            if (objeto.nome && !objeto.id) {             
                $('#modal-certeza-turno').openModal();
            } else {
                $scope.fecharFormulario();
            }
        };  
        
        /* Abre o formulário de edição/cadastro */        
        $scope.carregarFormulario = function (){
            Servidor.cardSai(['.info-card','.lista-geral', '.add-btn'], true);
            if ($scope.turno.nome !== null){$scope.hasTurno = true;}$scope.editando = true;
            $timeout(function(){$('#turnoNome').focus();},150);
        };
        
        /* Fecha o formulário de cadastro/edição */        
        $scope.fecharFormulario = function () {
            $scope.editando = false;
            $scope.reiniciar(); $scope.hasTurno = false; Servidor.resetarValidador('validate-turno');
            $scope.buscarTurnos();
        };
        
        /* Converte a hora para um formato aceitado pelo servidor */
        $scope.converterHora = function(date, query) {
            //var separar = date.split(':'); date = separar[0]+':'+separar[1];      
            var separar = date.split(' ');
            if (separar[1] === 'PM') {
                var hora = parseInt(separar[0].split(':')[0])+12;
                if (hora === 24) { hora = 00; }
                var minuto = separar[0].split(':')[1];
                date = hora + ':' + minuto;
            } else {
                date = separar[0];
            }
            if (query) { return date; } else { return date+':00'; }
        }; 
        
        /* Salvando turno */        
        $scope.finalizar = function() {
           if(Servidor.validar('validate-turno')){
                if ($scope.turno.inicio !== $scope.turno.termino) {
                    $scope.turno.inicio = $scope.converterHora($scope.turno.inicio, false);
                    $scope.turno.termino = $scope.converterHora($scope.turno.termino, false);                
                    $scope.mostraProgresso();
                    var result = Servidor.finalizar($scope.turno,'turnos','Turno');
                    result.then(function(response){
                        $timeout(function () {$scope.buscarTurnos(); $scope.fechaProgresso(); $scope.fecharFormulario(); },500);
                    });
                } else {
                    Materialize.toast('Horário inválido.', 2500);
                }
            }
        };  
        
        /* Validando Formulário */        
        $scope.validar = function (id) {
            var result = Servidor.validar(id);
            if($scope.turno.inicio && $scope.turno.termino){
                var inicioResult = Servidor.validarFormatoHora($scope.inicio,'horaInicio');
                var terminoResult = Servidor.validarFormatoHora($scope.termino,'horaTermino');
                var diferenca = Servidor.validarDiferencaHoras($scope.inicio,$scope.termino,'horaInicio','horaTermino'); 
            }
            if (result && inicioResult && terminoResult && diferenca) { return true; } else { return false; }
        };
        
        /* Inicializando turnos */
        $scope.inicializar();
        $scope.buscarTurnos();
    }]);
})();
