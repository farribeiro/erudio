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
    var cursoModule = angular.module('cursoModule', ['cursoDirectives', 'etapaDirectives', 'servidorModule', 'erudioConfig']);
    cursoModule.controller('CursoController', ['$scope', '$timeout', 'Servidor', 'EtapaService', '$templateCache', 'ErudioConfig', function ($scope, $timeout, Servidor, EtapaService, $templateCache, ErudioConfig) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll();
        $scope.escrita = Servidor.verificaEscrita('CURSO') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('cursos'); $scope.lista = true;
        //ATRIBUTOS
        $scope.cursos = []; $scope.cursoRemover = null; $scope.progresso = false; $scope.loader = false;
        $scope.cortina = false; $scope.pagina = 0; $scope.EtapaService = EtapaService; $scope.titulo = "Cursos";
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        $scope.mostraLoader = function (cortina) { $scope.loader = true; if (cortina) { $scope.cortina = true; } };
        $scope.fechaLoader = function () { $scope.loader = false; $scope.cortina = false; };
        //PREPARA REMOÇÃO DO CURSO
        $scope.prepararRemover = function (curso) { $('#remove-modal-curso').openModal(); $scope.cursoRemover = curso; };
        //PASSA ARGUMENTO PARA O MÓDULO DE ETAPAS - TESTAR
        $scope.intraForms = function (curso) { EtapaService.abreForm(); EtapaService.curso = curso; };
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-curso').openModal(); };
        //INICIALIZAÇÃO BÁSICA
        $scope.inicializar = function () { $('.title-module').html($scope.titulo); $('#modal-ajuda-curso').leanModal(); $('.material-tooltip').remove(); };
        
        //LISTANDO CURSOS
        $scope.buscarCursos = function () {
            $scope.mostraProgresso(); $('.tooltipped').tooltip('remove');
            var promise = Servidor.buscar('cursos', { 'page': $scope.pagina });
            promise.then(function (response) {
                if (response.data.length > 0) {
                    $scope.cursos = response.data; $('.tooltipped').tooltip('remove');
                    $timeout(function() { $('.tooltipped').tooltip({delay: 50}); }, 250);
                } else { if($scope.pagina > 0) { $scope.pagina--; } }
                $scope.fechaProgresso(); Servidor.entradaPagina();
            });
        };

        //REMOVE O CURSO
        $scope.remover = function () {
            $scope.mostraProgresso();
            Servidor.remover($scope.cursoRemover, 'Curso');
            $timeout(function(){ $scope.fechaProgresso(); $scope.buscarCursos(); }, 150);
        };        
        
        //INICIALIZANDO
        $scope.buscarCursos(); $scope.inicializar();
    }]);
})();