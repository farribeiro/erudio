/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                          *
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
    var cargosModule = angular.module('cargosModule', ['servidorModule', 'cargosDirectives']);

    cargosModule.controller('cargoController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', function ($scope, Servidor, Restangular, $timeout, $templateCache) {
        $templateCache.removeAll();
        
        $scope.escrita = Servidor.verificaEscrita('CARGO') || Servidor.verificaAdmin();
        $scope.cargos = null;
        $scope.cargo = {
            'nome': '',
            'professor': false,
            'grupo': {'id': null}
        };
        $scope.nomeCargo = '';
        $scope.editando = false;

        /* Inicializando */
        $scope.inicializar = function () {
            $scope.buscarGruposPermissoes();
            $timeout(function () {
                $(window).scroll(function () {
                    if ($(this).scrollTop() + $(this).height() === $(document).height()) {
                        if (!$scope.editando) {
                            $scope.pagina++;
                        }
                    }
                });
                Servidor.entradaPagina();
            }, 700);
        };

        /* Reseta a estrutura de cargo */
        $scope.limpaCargo = function () {
            $scope.cargo = {
                'nome': '',
                'professor': false
            };
        };

        /* Verifica se o usuário deseja descartar os dados preenchidos*/
        $scope.prepararVoltar = function (objeto) {
            if (objeto.nome && !objeto.id) {
                $('#modal-certeza').modal();
            } else {
                $scope.fecharFormulario();
            }
        };

        /* Buscar Cargos */
        $scope.buscar = function () {
            var promise = Servidor.buscar('cargos', null);
            $('.tooltipped').tooltip('remove');
            promise.then(function (response) {
                $scope.cargos = response.data;
                $timeout(function(){ $('.tooltipped').tooltip({delay: 50}); });
            });
        };

        /* Carregar Cargo */
        $scope.carregar = function (cargo){
            if(cargo){
                $scope.cargo = cargo;
            }
            $scope.editando = true;
            $timeout(function () {
                Servidor.verificaLabels();
                $('#nomeCargo').focus();
                $('#grupoPermissaoCargo').material_select();
            }, 50);
        };
        
        $scope.buscarGruposPermissoes = function() {
            var promise = Servidor.buscar('grupos', null);
            promise.then(function(response) {
                $scope.gruposPermissoes = response.data;
            });
        };

        /* Preparar remover */
        $scope.prepararRemover = function (cargo) {
            $scope.cargoRemover = cargo;
            $('#remove-modal-cargo').modal();
        };

        /* Exclui um cargo */
        $scope.remover = function (cargo) {
            Servidor.remover($scope.cargoRemover, 'cargo');
            $timeout(function(){
                $scope.buscar();
           }, 1000);
        };

        /* Salvar cargo */
        $scope.salvarCargo = function (nome) {
            if ($scope.cargo.nome.length > 0 && $scope.cargo.grupo.id) {
                var cargos = $scope.cargos.filter(function(cargo) {
                    return cargo.nome === $scope.cargo.nome;
                });
                if(cargos.length && !$scope.cargo.id) {
                    return Servidor.customToast('Já existe um cargo com este nome.');
                } else {
                    $scope.cargo.grupo = {id: $scope.cargo.grupo.id};
                    var result = Servidor.finalizar($scope.cargo, 'cargos', 'Cargo');
                    result.then(function () {
                        $scope.limpaCargo();
                        $scope.editando = false;
                        $scope.buscar();
                    });
                }
                /*if ($scope.cargos.length > 0) {
                    for (var i = 0; i < $scope.cargos.length; i++) {
                        if ($scope.cargos[i].nome.toUpperCase() === nome.toUpperCase() && $scope.cargo.id !== $scope.cargos[i].id) {
                            Servidor.customToast('Já existe um cargo com este nome.');
                            return true;
                        }
                        if (i === $scope.cargos.length - 1) {
                            var result = Servidor.finalizar($scope.cargo, 'cargos', 'Cargo');
                            result.then(function () {
                                $scope.limpaCargo();
                                $scope.editando = false;
                                $scope.buscar();
                            });
                        }
                    }
                } else {
                    var result = Servidor.finalizar($scope.cargo, 'cargos', 'Cargo');
                    result.then(function () {
                        $scope.limpaCargo();
                        $scope.editando = false;
                        $scope.buscar();
                    });
                }*/
            } else {
                Servidor.customToast('Há campos obrigatórios não preenchidos.');
            };
        };
            
        /* Fechar formulario */
        $scope.fecharFormulario = function () {
            $scope.editando = false;
            $scope.limpaCargo();
            $scope.buscar();
        };
        
        $scope.buscar();
        $scope.inicializar();
    }]);
})();
