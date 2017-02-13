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
    var moduloModule = angular.module('moduloModule', ['servidorModule', 'moduloDirectives']);
    moduloModule.controller('moduloController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', function ($scope, Servidor, Restangular, $timeout, $templateCache) {
            $templateCache.removeAll();
            
            $scope.escrita = Servidor.verificaEscrita('MODULO');
            
            /* Atributos Específicos */
            $scope.modulos = []; 
            $scope.cursos = [];
            
            /* Atributos de controle da página */
            $scope.editando = false; 
            $scope.loader = false; 
            $scope.progresso = false;
            
            /* Estrutura de nível */
            $scope.modulo = {nome: null, curso: {id: null}};
            
            /* Controle da barra de progresso */
            $scope.mostraProgresso = function () { $scope.progresso = true; };
            $scope.fechaProgresso = function () { $scope.progresso = false; };
            $scope.mostraLoader = function () { $scope.loader = true; };
            $scope.fechaLoader = function () { $scope.loader = false; };
            
            /* Reinciando estrutura de regime*/
            $scope.reiniciar = function () { $scope.modulo = {nome: null, curso: {id: null}}; };
            
            /* Inicializando */
            $scope.inicializar = function(){
                $('.tooltipped').tooltip('remove');
                $timeout(function () {
                    $('.counter').each(function () { $(this).characterCounter(); });
                    $('.tooltipped').tooltip({delay: 50});
                    /*Inicializando controles via Jquery Mobile */
                    if ($(window).width() < 993) {
                        $(".swipeable").on("swiperight", function () { $('.swipeable').removeClass('move-right'); $(this).addClass('move-right'); });
                        $(".swipeable").on("swipeleft", function () { $('.swipeable').removeClass('move-right'); });
                    }
                    Servidor.entradaPagina();
                }, 1000);
            };
            
            /* Buscando níveis - Lista */
            $scope.buscarModulos = function(){
                $scope.mostraProgresso();
                var promise = Servidor.buscar('modulos', null);
                promise.then (function (response){
                    $scope.modulos = response.data;
                    $('.tooltipped').tooltip('remove');
                    $timeout(function(){ 
                        $scope.fechaProgresso();
                        $('.tooltipped').tooltip({delay: 50});
                    }, 1000);
                });
            };
            
            /*Buscando cursos - Lista*/
            $scope.buscarCursos = function () {
                $scope.mostraProgresso();
                var promise = Servidor.buscar('cursos', null);
                promise.then(function (response) {
                    $scope.cursos = response.data;
                    $timeout(function () { $scope.fechaProgresso(); }, 1000);
                });
            };
            
            $scope.prepararVoltar = function(objeto) {
                if (objeto.nome && !objeto.id) {                
                    $('#modal-certeza').openModal();
                } else {
                    $scope.fecharFormulario();
                }
            };
            
            /* Preparando carregamento do modulo */
            $scope.carregar = function (modulo) {
                $scope.mostraLoader(); Servidor.cardSai(['.info-card','.lista-geral', '.add-btn'], true);
                $scope.acao = "Cadastrar";
                if (modulo) { $scope.acao = "Editar"; $scope.modulo = modulo; }
                $timeout(function () {
                    $timeout(function(){$('#nome').focus();},150);
                    $scope.editando = true;
                    $('#buscaCurso').material_select('destroy'); $('#buscaCurso').material_select();
                    Servidor.verificaLabels(); $scope.fechaLoader();
                    $timeout(function(){ Servidor.cardEntra('.form-geral'); },500);
                }, 1000);
            };
            
            $scope.verificaSelectCurso = function (cursoId) { if (cursoId === $scope.modulo.curso.id) { return true; } };
            
            /* Guarda o modulo para futura remoção e abre o modal de confirmação */
            $scope.prepararRemover = function (modulo) { $scope.moduloRemover = modulo; $('#remove-modal-modulo').openModal(); };
            
            /* Remove o modulo */
            $scope.remover = function () {
                $scope.mostraProgresso();
                Servidor.remover($scope.moduloRemover, 'Módulo');
                $timeout(function () { $scope.buscarModulos(); $scope.fechaProgresso(); }, 1000);
            };
            
            /* Fecha o formulário de cadastro/edição */
            $scope.fecharFormulario = function () {
                $scope.editando = false;                
                $scope.reiniciar(); 
                Servidor.resetarValidador('validate');
                $scope.fechaProgresso();
                $scope.buscarModulos();
            };
            
            /* Salvando modulo */
            $scope.finalizar = function (modulo) {
                $scope.modulo = modulo;
                if (modulo.nome === null || modulo.curso.id === null) {
                    Servidor.customToast("Preencha os campos obrigatorios");
                    return false;
                }else {
                    var result = Servidor.finalizar($scope.modulo, 'modulos', 'Módulo');
                    result.then(function (response) {
                        $scope.fecharFormulario();
                    });
                }
            };
            
            /* Validando Formulário */
            $scope.validar = function (id) { var result = Servidor.validar(id); return result; };
            
            /* Inicializando modulos */
            $scope.buscarCursos();
            $scope.inicializar();
            $scope.buscarModulos();
        }]);
})();
