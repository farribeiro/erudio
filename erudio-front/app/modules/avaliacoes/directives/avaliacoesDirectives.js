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
    var avaliacoesDirectives = angular.module('avaliacoesDirectives', []);
    
    avaliacoesDirectives.directive('avaliacoes', function(){
       return {restrict: 'E', templateUrl: 'app/modules/avaliacoes/partials/avaliacoes.html'}; 
    });
    
    avaliacoesDirectives.directive('controleAvaliacoes', function(){
        return { restrict: 'E', templateUrl: 'app/modules/avaliacoes/partials/controle.html'};
    });
    
    avaliacoesDirectives.directive('formAvaliacoes', function(){
       return {restrict: 'E', templateUrl: 'app/modules/avaliacoes/partials/form.html'}; 
    });
    
    avaliacoesDirectives.directive('listaAvaliacoes', function(){
       return {restrict: 'E', templateUrl:'app/modules/avaliacoes/partials/lista.html'}; 
    });
    
    avaliacoesDirectives.directive('notasAvaliacoes', function(){
       return {restrict: 'E', templateUrl:'app/modules/avaliacoes/partials/notas.html'}; 
    });
    
    avaliacoesDirectives.directive('modalAvaliacoes', function(){
       return {restrict: 'E', templateUrl:'app/modules/avaliacoes/partials/modal.html'}; 
    });
    
    avaliacoesDirectives.directive('calendarioAvaliacoes', function(){
       return {restrict: 'E', templateUrl:'app/modules/avaliacoes/partials/calendario.html'}; 
    });
})();