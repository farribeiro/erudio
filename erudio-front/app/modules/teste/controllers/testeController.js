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
    var testeModule = angular.module('testeModule', ['servidorModule', 'testeDirectives','erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    testeModule.controller('TesteController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$rootScope', '$compile', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $rootScope, $compile) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('TESTE') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('teste'); $scope.lista = true;
        //ATRIBUTOS
        $scope.titulo = "Testes"; $scope.progresso = false; $scope.cortina = false; $scope.conteudoAula = '';
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.title-module').html($scope.titulo); $('.material-tooltip').remove();
            $timeout(function () {
                $('.collapsible-header').click(function(){
                    if (!$(this).hasClass('active')) {
                        $('.selectable').each(function(){
                            var w = $(this).width(); var h = $(this).height();
                            $(this).css('width',w+'px').css('height',h+'px');
                        });
                    }
                });
                
                $('.selectable').click(function(){
                    if ($('teste-aula').length === 0) {
                        var elem = $(this).parent(); $scope.conteudoAula = $(this).html(); var actual = $(this); $('.cortina-aula').show();
                        $scope.mark = actual.find('.marker'); $scope.mark.hide();
                        $('.cortina-aula').click(function(){
                            actual.addClass('hoverable').removeClass('absolute').removeClass('z-depth-3').removeClass('aula-ativa'); $(this).hide(); $('teste-aula').remove();
                            console.log($scope.mark); $scope.mark.show();
                        });
                        actual.removeClass('hoverable').addClass('absolute').addClass('z-depth-3').addClass('aula-ativa');
                        var aula = angular.element(document.createElement('teste-aula'));
                        var elemento = $compile(aula)($scope); actual.html('').append(elemento);
                        $timeout(function(){
                            $('.edit-aula').click(function(){
                                var id = $(this).attr('data-justificativa');
                                $('#'+id).show();
                            });
                            
                            $('.aula-dia').click(function(){ 
                                var id = $(this).attr('for'); var bool = $('#'+id).is(':checked');
                                if (!bool) { Servidor.customToast('Presença computada.'); } else { Servidor.customToast('Falta computada.'); }
                            });

                            $('.aula-todos').click(function(){
                                var id = $(this).attr('for'); var bool = $('#'+id).is(':checked');
                                if (bool) { $('.check').removeAttr('checked'); } else { $('.check').trigger("click"); }
                            });
                        },200);
                    }
                });
                
                $('.collapsible').collapsible();
                $('.tooltipped').tooltip({delay: 50});
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                $('select').material_select('destroy'); $('select').material_select();
            }, 1000);
        };

        //INICIALIZANDO
        $scope.inicializar();
    }]);
})();
