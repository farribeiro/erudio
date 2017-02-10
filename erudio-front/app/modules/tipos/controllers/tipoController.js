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
    var tipoModule = angular.module('tipoModule', ['servidorModule','tipoDirectives']);    
    tipoModule.controller('TipoController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', function($scope, Servidor, Restangular, $timeout, $templateCache) {
        $templateCache.removeAll();        
        
        $scope.escrita = Servidor.verificaEscrita('TIPO_UNIDADE');        
        
        /* Atributos Específicos */
        
        $scope.tipos = [];
        /* Atributos de controle da página */
        
        $scope.editando = false;
        $scope.loader = false; 
        $scope.progresso = false;
        
            /* Estrutura de tipo */
        $scope.tipo = { nome: null, sigla: null };
        
        /* Controle da barra de progresso */
        $scope.mostraProgresso = function () { $scope.progresso = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; };
        $scope.mostraLoader = function () { $scope.loader = true; };
        $scope.fechaLoader = function () { $scope.loader = false; };
        
        /* Reinciando estrutura de tipo */
        $scope.reiniciar = function (){ $scope.tipo = { nome: null, sigla: null };};
        
        /* Inicializando */
        $scope.inicializar = function () {
            $('.tooltipped').tooltip('remove');
            $timeout(function (){            
                $('.counter').each(function(){  $(this).characterCounter(); });
                $('.tooltipped').tooltip({delay: 50});
                /*Inicializando controles via Jquery Mobile */
                if ($(window).width() < 993){
                    $(".swipeable").on("swiperight",function(){ $('.swipeable').removeClass('move-right'); $(this).addClass('move-right'); });
                    $(".swipeable").on("swipeleft",function(){ $('.swipeable').removeClass('move-right'); });
                }
            },1000);
        };
        
        $scope.prepararVoltar = function(objeto) {
            if (objeto.nome && !objeto.id && objeto.sigla) {                
                $('#modal-certeza').openModal();
            } else {
                $scope.fecharFormulario();
            }
        };

      /* Buscando tipos - Lista */
        $scope.buscarTipos = function(){
            var promise = Servidor.buscar('unidades-ensino/tipos', null);
            promise.then(function(response){
                $scope.tipos = response.data;
                $('.tooltipped').tooltip('remove');
                $timeout(function(){ Servidor.entradaPagina(); $('.tooltipped').tooltip({delay: 50}); },500);
            });
        };

        /* Salvando Tipos */
        $scope.finalizar = function(){
            if ($scope.tipo.nome && $scope.tipo.sigla){
                $scope.mostraProgresso();
                if ($scope.tipos.length) {
                    $scope.tipos.forEach(function(tipo, i) {
                        if ($scope.tipo.id) {
                            if ($scope.tipo.id !== tipo.id && $scope.tipo.nome === tipo.nome && $scope.tipo.sigla === tipo.sigla) {
                                Servidor.customToast('Já existe um tipo de unidade com o mesmo nome.');
                                $scope.fechaProgresso();
                                return;
                            }
                        } else {
                            if ($scope.tipo.nome === tipo.nome && $scope.tipo.sigla === tipo.sigla) {
                                Servidor.customToast('Já existe um tipo de unidade com o mesmo nome.');
                                $scope.fechaProgresso();
                                return;
                            }
                        }                        
                        if (i === $scope.tipos.length-1) {
                            var result = Servidor.finalizar($scope.tipo, 'unidades-ensino/tipos', 'Tipo de Unidade');
                            result.then(function (response) {
                                $scope.fecharFormulario();
                                $scope.fechaProgresso();
                                $scope.tipos.push(response.data);
                                $timeout(function () {
                                    $('#nome').focus();
                                }, 150);
                            });
                        }
                    });
                } else {
                    var result = Servidor.finalizar($scope.tipo, 'unidades-ensino/tipos', 'Tipo de Unidade');
                    result.then(function (response) {
                        $scope.fecharFormulario();
                        $scope.fechaProgresso();
                        $scope.tipos.push(response.data);
                        $timeout(function () {
                            $('#nome').focus();
                        }, 150);
                    });
                }                
            }
            else if($scope.tipo.nome === null || $scope.tipo.sigla === null ){
                Servidor.customToast('Insira os campos obrigatorios.');
            }
        };

        /* Validando Formulário */
        $scope.validar = function (id) { var result = Servidor.validar(id); return result; };   
        
        /* Guarda o tipo para futura remoção e abre o modal de confirmação */
        $scope.prepararRemover = function (tipo){ $('#remove-modal').openModal(); $scope.tipo = tipo; };
            
        /* Remover o tipo */
        $scope.remover = function (){
            $('.lean-overlay').hide();            
            $scope.mostraProgresso(); var id = $scope.tipo.id;
            Servidor.remover($scope.tipo, 'Tipo de Unidade');
            $timeout(function (){ 
                $('.tipo'+id).remove();
                $scope.fechaProgresso(); 
                $scope.buscarTipos();                
            }, 500);

        };

        /* Abre o formulário de edição/cadastro */
        $scope.carregar = function (tipo){            
            $scope.mostraLoader(); //Servidor.cardSai(['.info-card','.lista-geral', '.add-btn'], true);
            Servidor.animacaoEntradaForm(true);
            $scope.acao = "CADASTRAR";
            if (tipo) { $scope.acao = "EDITAR"; $scope.tipo = tipo; }
            else{$scope.reiniciar();}
            $timeout(function (){               
                Servidor.verificaLabels();
                $scope.editando = true;
                $scope.fechaLoader();
                //$timeout(function(){ Servidor.cardEntra('.form-geral'); },500);
                $timeout(function(){$('#nomeTipo').focus();},150);
            },500);
        };

        /* Fecha o formulário de cadastro/edição */
        $scope.fecharFormulario = function () {
            Servidor.animacaoEntradaLista(true);
            $timeout(function (){ $scope.editando = false; },300); $scope.reiniciar(); $scope.buscarTipos();
        };

        /* Inicializando tipos */
        $scope.inicializar();
        $scope.buscarTipos();
    }]);
})();
