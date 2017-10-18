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
    var reclassificacaoDirectives = angular.module('reclassificacaoDirectives', []);

    reclassificacaoDirectives.directive('reclassificacaoControle', function (){
        return { restrict: 'E', templateUrl: 'app/modules/reclassificacao/partials/controle.html' };
    });

    reclassificacaoDirectives.directive('reclassificacaoLista', function (){
        return { restrict: 'E', templateUrl: 'app/modules/reclassificacao/partials/lista-historico.html' };
    });

    reclassificacaoDirectives.directive('reclassificacaoInformacoes', function() {
        return { restrict: 'E', templateUrl: 'app/modules/reclassificacao/partials/informacoes.html' };
    });

    reclassificacaoDirectives.directive('reclassificacaoEnturmacao', function() {
        return { restrict: 'E', templateUrl: 'app/modules/reclassificacao/partials/movimentacoes-turma.html' };
    });

    reclassificacaoDirectives.directive('historicoListaTransferirReclassificacao', function() {
        return { restrict: 'E', templateUrl: 'app/modules/reclassificacao/partials/lista-transferir.html' };
    });
    
    reclassificacaoDirectives.directive('historicoFormularioReclassificacao', function() {
        return { restrict: 'E', templateUrl: 'app/modules/reclassificacao/partials/form-movimentacoes.html' };
    });
    
    reclassificacaoDirectives.directive('historicoFormularioReclassificacaoDesligar', function() {
        return { restrict: 'E', templateUrl: 'app/modules/reclassificacao/partials/form-desligar.html' };
    });
    
    reclassificacaoDirectives.directive('historicoFormularioReclassificacaoTransferir', function() {
        return { restrict: 'E', templateUrl: 'app/modules/reclassificacao/partials/form-transferir.html' };
    });
    
    reclassificacaoDirectives.directive('historicoFormularioReclassificacaoMovimentar', function() {
        return { restrict: 'E', templateUrl: 'app/modules/reclassificacao/partials/form-movimentar.html' };
    });
    
    reclassificacaoDirectives.directive('modalReclassificacao', function() {
        return { restrict: 'E', templateUrl: 'app/modules/reclassificacao/partials/modal.html' };
    });
    
})();
