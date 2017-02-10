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
    var turmaDirectives = angular.module('turmaDirectives', []);
    
    turmaDirectives.directive('formTurma', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/form.html' };
    });
    
    turmaDirectives.directive('controleTurma', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/controle.html' };
    });
    
    turmaDirectives.directive('listaTurma', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/lista.html' };
    });
    
    turmaDirectives.directive('modalTurma', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/modal.html' };
    });
    
    turmaDirectives.directive('opcoesTurma', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/opcoes.html' };
    });       
    
    turmaDirectives.directive('erroBuscaAlunos', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/erro.html' };
    });

    turmaDirectives.directive('enturmarAlunos', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/enturmar-alunos.html' };
    });
    
    turmaDirectives.directive('turmaEnturmarAlunos', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/turma-enturmar-alunos.html' };
    });    
    
    turmaDirectives.directive('alunosEnturmados', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/alunos-enturmados.html' };
    });  
            
    turmaDirectives.directive('turmaFrequencia', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/frequencia.html' };
    });         
    
    turmaDirectives.directive('informacoesTurma', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/informacoes-turma.html' };
    });
    
    turmaDirectives.directive('quadroHorario', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/quadro-horario.html' };
    });      
    
    turmaDirectives.directive('frequenciasAluno', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/frequencias-aluno.html' };
    });  
    
    turmaDirectives.directive('professoresTurma', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/professores-turma.html' };
    });     
    
    turmaDirectives.directive('notasTurma', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/notas-aluno-turma.html' };
    });
    
    turmaDirectives.directive('atestadoVaga', function (){
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/atestado-vaga.html' };
    });
    
    turmaDirectives.directive('calendarioAulas', function() {
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/calendario.html' };
    });
    
    turmaDirectives.directive('oferecerDisciplinas', function() {
        return { restrict: 'E', templateUrl: 'app/modules/turmas/partials/oferecer-disciplinas.html' };
    });
    
})();