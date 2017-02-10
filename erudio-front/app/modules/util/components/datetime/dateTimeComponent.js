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
    var dateTimeComponent = angular.module('dateTimeComponent', []);
    
    dateTimeComponent.service('DateTime', [ function() {
            this.fullDate = null;
            
            this.getData = function() {
                return this.fullDate;
            };
            
            this.setData = function(fullDate) {
                var array = fullDate.split("T");
                this.fullDate = array[0];
                return true;
            };
    }]);
    
    dateTimeComponent.controller('dateTimeController', ['$scope', '$timeout', 'DateTime', function($scope, $timeout, DateTime) {
            $scope.data = new Date();
            
            $scope.preencheDados = function (data, primeiraVez){
                $scope.data = data;
                DateTime.setData($scope.data.toISOString());
                $scope.dia = $scope.data.getDate();
                $scope.ano = $scope.data.getFullYear();
                $scope.mes = $scope.data.getMonth();
                $scope.primeiroDiaMesObj = new Date($scope.ano, $scope.mes, 1);
                $scope.primeiroDiaMes = $scope.primeiroDiaMesObj.getDate();
                $scope.primeiroDiaSemana = $scope.primeiroDiaMesObj.getDay();
                $scope.ultimoDiaMesObj = new Date($scope.ano, $scope.mes + 1, 0);
                $scope.ultimoDiaMes = $scope.ultimoDiaMesObj.getDate();
                $scope.preparaLabels();
                $scope.geraArrayMes();
                
                $timeout(function (){ 
                    if (primeiraVez) {
                        $scope.selectData($scope.dia);
                    }
                },1000);
            };
            
            $scope.preparaLabels = function () {
                var locale = 'pt-br';
                $scope.mesNome = $scope.data.toLocaleString(locale, {month: 'long'});
                var diasSemana = new Array(7);
                diasSemana[0] = "Domingo"; diasSemana[1] = "Segunda"; diasSemana[2] = "Terça"; diasSemana[3] = "Quarta"; diasSemana[4] = "Quinta";
                diasSemana[5] = "Sexta"; diasSemana[6] = "Sábado";
                $scope.diaSemanaNome = diasSemana[$scope.data.getDay()];
            };
            
            $scope.geraArrayMes = function () {
                $scope.arrayMes = new Array();
                for (i=0; i<$scope.primeiroDiaSemana; i++) {
                    $scope.arrayMes.push('');
                }
                for (j=0; j<$scope.ultimoDiaMes; j++) {
                    $scope.arrayMes.push(j+1);
                }              
            };
            
            $scope.selectData = function (dia) {
                var days = document.getElementsByClassName("day");
                for (i=0; i<days.length; i++) {
                    days[i].classList.remove("day-selected");
                }
                var element = document.getElementById("day"+dia);
                element.classList.add("day-selected");
                
                var date = new Date($scope.ano, $scope.mes, dia);
                $scope.preencheDados(date, false);
            };
            
            $scope.anterior = function () {
                $scope.preencheDados(new Date($scope.ano, $scope.mes - 1, 1), false);
            };
            
            $scope.proximo = function () {
                $scope.preencheDados(new Date($scope.ano, $scope.mes + 1, 1), false);
            };
            
            $scope.preencheDados($scope.data, true);
    }]);
    
    dateTimeComponent.directive('datepicker', function (){
        return { restrict: 'E', templateUrl: 'app/modules/util/components/datetime/partials/datepicker.html' };
    });
    
    dateTimeComponent.directive('datetimepicker', function (){
        return { restrict: 'E', templateUrl: 'app/modules/util/components/datetime/partials/datetimepicker.html' };
    });
    
    dateTimeComponent.directive('timepicker', function (){
        return { restrict: 'E', templateUrl: 'app/modules/util/components/datetime/partials/timepicker.html' };
    });
})();