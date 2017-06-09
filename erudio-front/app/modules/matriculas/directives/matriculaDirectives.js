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
    var matriculaDirectives = angular.module('matriculaDirectives', []);   
    
    matriculaDirectives.directive('formBusca', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/form-busca.html' };
    });
    
    matriculaDirectives.directive('atestadoMatriculaFrequencia', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/atestado-matricula-frequencia.html' };
    });
    
    matriculaDirectives.directive('controleMatricula', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/controle.html' };
    });    
    
    matriculaDirectives.directive('modalMatricula', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/modal.html' };
    });    
    
    matriculaDirectives.directive('formMatricula', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/form.html' };
    });
    
    /*matriculaDirectives.directive('formCadastro', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/form-cadastro.html' };
    });*/
    
    /*matriculaDirectives.directive('formEnturmacao', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/form-enturmacao.html' };
    });*/ 
    
    matriculaDirectives.directive('tabsBusca', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/tabs-busca.html' };
    });
    
    matriculaDirectives.directive('formBuscaMatricula', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/form-busca-matricula.html' };
    });
    
    matriculaDirectives.directive('listaMatriculas', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/lista-matriculas.html' };
    }); 
    
    matriculaDirectives.directive('disciplinasCursadas', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/disciplinas-cursadas.html' };
    });
    
    matriculaDirectives.directive('turmasMatricula', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/turmas-matricula.html' };
    });     
    
    matriculaDirectives.directive('matriculaDisciplinas', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/matricula-disciplina.html' };
    });
    
    matriculaDirectives.directive('matriculaErro', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/erro.html' };
    });     
    
    matriculaDirectives.directive('matriculaHistoricoMovimentacoes', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/historico-movimentacoes.html' };
    });  
    
    matriculaDirectives.directive('matriculaHistoricoDisciplinas', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/historico-disciplinas.html' };
    });    
    matriculaDirectives.directive('matriculaFrequencias', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/frequencias.html' };
    });
    matriculaDirectives.directive('matriculaForm', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/matricula-form.html' };
    });     
    matriculaDirectives.directive('enturmacoes', function (){
        return { restrict: 'E', templateUrl: 'app/modules/matriculas/partials/enturmacoes.html' };
    });
      
           
           
       
})();

