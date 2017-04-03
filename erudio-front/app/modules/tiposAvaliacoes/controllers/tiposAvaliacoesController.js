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
    var tiposAvaliacoesModule = angular.module('tiposAvaliacoesModule', ['servidorModule', 'tiposAvaliacoesDirectives']);

    tiposAvaliacoesModule.controller('tiposAvaliacoesController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', function ($scope, Servidor, Restangular, $timeout, $templateCache) {
        $templateCache.removeAll();
        
        $scope.escrita = Servidor.verificaEscrita('TIPOS_AVALIACAO');

        $scope.tipos = [];
        $scope.tipo = {
            'nome': ''
        };
        $scope.limparTipo = function () {
            $scope.tipo = {
                'nome': ''
            };
        };
        $scope.editando = false;
        $scope.acao = '';

        /* Controle da barra de loader */
        $scope.mostraLoader = function () { $scope.loader = true; };
        $scope.fechaLoader = function () { $scope.loader = false; };

        /* Buscar */
        $scope.buscar = function () {
            $scope.mostraLoader();
            var promise = Servidor.buscar('avaliacoes/tipos', null);
            promise.then(function (response) {
                $scope.tipos = response.data;
                $('.tooltipped').tooltip('remove');
                $timeout(function() { $('.tooltipped').tooltip({delay: 50}); $scope.fechaLoader(); Servidor.entradaPagina(); }, 250);
            });            
        };
        
        /* Prepara objeto para remover */
        $scope.prepararVoltar = function(objeto) {
            if (objeto.nome && !objeto.id) {                
                $('#modal-certeza').modal();
            } else {
                $scope.fecharFormulario();
            }
        };

        /* Preparar Remover */
        $scope.prepararRemover = function (tipo) {
            $scope.tipoRemover = tipo;
            $('#remove-modal-atividades').modal();
        };

        /* Remover */
        $scope.remover = function () {
            Servidor.remover($scope.tipoRemover, 'Tipo de Avaliação');
            $timeout(function () {
                $scope.buscar();
            }, 150);
        };
        
        /* Carregar */
        $scope.carregar = function (tipo){
            $scope.mostraLoader();
            if (tipo) {
                $scope.tipo = tipo;
                $scope.acao = 'Editar';
            } else {
                $scope.acao = 'Cadastrar';
            }
            $scope.editando = true;
            $timeout(function(){                
               Servidor.verificaLabels();
               $('#nomeTipoAvaliacao').focus();
               $scope.fechaLoader();               
            }, 500);
        };

        $scope.salvar = function(){
            var result = Servidor.finalizar($scope.tipo, 'avaliacoes/tipos', 'Tipo de Avaliação');
            result.then(function () {
                $scope.fecharFormulario();
                $scope.fechaLoader();
            });
        };

        /* Salvar */
        $scope.verificaSalvar = function (nome){
            $scope.mostraLoader();
            if ($scope.tipos.length) {
                for (var i = 0; i < $scope.tipos.length; i++) {
                    if ($scope.tipos[i].nome.toUpperCase() === nome.toUpperCase() && $scope.tipo.id !== $scope.tipos[i].id) {
                        Servidor.customToast('Ja existe um tipo de avaliaçao com este mesmo nome.');
                        $scope.fechaLoader();
                        return 0;
                    }
                    if (i === $scope.tipos.length - 1) {
                        $scope.salvar();
                    }
                }
            }
            else {
                $scope.salvar();
            }
        };

        /* Fechar Formulario */
        $scope.fecharFormulario = function(){
            $scope.editando = false;
            $scope.limparTipo();
            $scope.buscar();
        };
        $scope.buscar();
    }]);
})();
