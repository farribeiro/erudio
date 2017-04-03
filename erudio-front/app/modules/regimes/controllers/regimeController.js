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
    var regimeModule = angular.module('regimeModule', ['servidorModule','regimeDirectives']);
    regimeModule.controller('RegimeController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', 'Elementos', '$compile', function($scope, Servidor, Restangular, $timeout, $templateCache, Elementos, $compile) {
        $templateCache.removeAll(); $scope.elementos = Elementos; $scope.escopo = $scope;
        $scope.escrita = Servidor.verificaEscrita('REGIME_ENSINO');
        /* Atributos Específicos */
        $scope.regimes = [];

        /* Atributos de controle da página */
        $scope.editando = false; $scope.progresso = false; $scope.loader = false;

        /* Estrutura de Regime */
        $scope.regime = { nome: null };

        /* Controle da barra de progresso */
        $scope.mostraProgresso = function () { $scope.progresso = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; };
        $scope.mostraLoader = function () { $scope.loader = true; };
        $scope.fechaLoader = function () { $scope.loader = false; };

        /* Reinciando estrutura de regime */
        $scope.reiniciar = function (){ $scope.regime = { nome: null }; };
        
        $scope.salvarBind = function (target) { var el = angular.element(target); el.bind('click',$scope.finalizar()); };

        /* Inicializando */
        $scope.inicializar = function(){
            $('.tooltipped').tooltip('remove');
            $timeout(function(){                           
                $('.tooltipped').tooltip({delay: 50}); 
                /*Inicializando controles via Jquery Mobile */
                if ($(window).width() < 993) {
                    $(".swipeable").on("swiperight",function(){ $('.swipeable').removeClass('move-right'); $(this).addClass('move-right'); });   
                    $(".swipeable").on("swipeleft",function(){ $('.swipeable').removeClass('move-right'); });
                }
                Servidor.entradaPagina();
            }, 700);
        };
        
        $scope.prepararVoltar = function(objeto){
            if(objeto.nome && !objeto.id) {                
                $('#modal-certeza').modal();
            } else{
                $scope.fecharFormulario();
            }
        };
        
        /* Buscando regimes - Lista */
        $scope.buscarRegimes = function(){
            $('.tooltipped').tooltip('remove');
            $timeout(function(){
                $('.tooltipped').tooltip({delay: 50});
            }, 150);
            $scope.mostraProgresso();
            var promise = Servidor.buscar('regimes',null);
            promise.then(function (response){ 
                $scope.regimes = response.data;
                $timeout(function (){ $scope.fechaProgresso(); },500);
            });
        };
        
        //Abrir modal para remover Regime
         $scope.prepararRemover = function(regime) {
            $scope.regimeRemover = regime;
            $('#remove-modal-regime').modal();
            
        };                
                                    
        /* Remove o regime  */ 
        $scope.remover = function(){
            $('.lean-overlay').hide(); 
            $scope.mostraProgresso(); var id = $scope.regimeRemover.id;
            Servidor.remover($scope.regimeRemover, 'Regime');
            $timeout(function (){ $('.regime'+id).remove(); $scope.buscarRegimes(); $scope.fechaProgresso();},500); 
            
        };  
        
        /* Abre o formulário de edição/cadastro */
        $scope.carregarFormulario = function (regime) {     
            Servidor.animacaoEntradaForm(false);
            $scope.acao = "Cadastrar";
            $scope.mostraLoader();
            if (regime) { $scope.regime = regime; $scope.acao = "Editar"; }
            $timeout(function(){
                $scope.editando = true; Servidor.verificaLabels(); $scope.fechaLoader();
                $timeout(function(){$('#nomeRegime').focus();},150);
            }, 500);                     
        };
        
        /* Fecha o formulário de cadastro/edição */
        $scope.fecharFormulario = function () { 
            Servidor.animacaoEntradaLista(false);
            $timeout(function (){ $scope.editando = false; },300);
            $scope.reiniciar(); Servidor.resetarValidador('validate');
            $scope.buscarRegimes();
        };

        /* Salvando Regime */
        $scope.finalizar = function (nome) {
            $scope.mostraProgresso();
            if ($scope.validar('validate')) {
                if($scope.regimes.length) {
                    for (var i = 0; i < $scope.regimes.length; i++) {
                        if ($scope.regimes[i].nome.toUpperCase() === nome.toUpperCase() && $scope.regime.id !== $scope.regimes[i].id){
                            console.log($scope.regimes[i].nome);
                            Servidor.customToast("Já existe um regime de ensino com este nome.");
                            $scope.fechaProgresso();
                            return true;
                        }
                        if (i === $scope.regimes.length - 1) {
                            var result = Servidor.finalizar($scope.regime, 'regimes', 'Regime');
                            result.then(function (promise) {
                                $scope.fecharFormulario();
                            });
                        }
                    }
                } else {
                    var result = Servidor.finalizar($scope.regime, 'regimes', 'Regime');
                    result.then(function (promise) {
                        $scope.fecharFormulario();
                    });
                }            
    //              Servidor.customToast('Digite o nome do Regime de Ensino');
                $timeout(function () {
                    $('#nomeRegime').focus();
                }, 150);
            }
        };
            
        /* Validando Formulário */
        $scope.validar = function (id) { var result = Servidor.validar(id); return result; };
        
        /* Inicializando Regimes */
        $scope.buscarRegimes();
        $scope.inicializar();
    }]);
})();