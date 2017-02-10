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
    var animationsModule = angular.module('animationsModule', []);
    
    animationsModule.service('Animatron', [ function() {
        
        /* Atributos usados para o efeito accordion. */
        this.accordionElement = null;
        this.accordionHeight = null;
        this.accordionTop = null;
        this.accordionLeft = null;
        
        /* 
         * Anima um item de lista para uma página interna, de dentro para fora. 
         * Parâmetros: id do objeto, elemento que contém a lista, elemento que contém a página destino.
         * */
        this.accordion = function (id, element, target) {
            var mainElement = $("#item-lista"+id).clone();
            var position = $("#item-lista"+id).position();
            
            /* Guardando altura do item para fazer a operação inversa. */
            this.accordionHeight = $("#item-lista"+id).height();
            this.accordionTop = position.top;
            this.accordionLeft = position.left;
            
            /* Cálculo da distância entre a Toolbar e a página para delimitar o fim da animação no topo. */
            var heightToolbar = $('.main-toolbar').height();
            var height = $(element).height();
            var topHeight = height - heightToolbar;
            
            /* Início da Animação. */
            mainElement.css('position','fixed')
                    .css('width', '100%')
                    .css('z-index',90)
                    .css('top',position.top+'px')
                    .css('left',position.left+'px')
                    .css('background', '#e4e4e4');

            mainElement.find("#paper-item"+id).css('background', 'transparent');
            mainElement.find("#paper-item"+id+" > .list-view > .first-line").css('display','none');
            mainElement.find("#paper-item"+id+" > .list-view > .second-line").css('display','none');
            
            $(element).append(mainElement);
            this.accordionElement = mainElement;
            
            mainElement.delay(200).animate({
                'top': heightToolbar+'px',
                'height': topHeight+'px'
            }, 500, function () {
                mainElement.hide();
                $(element).hide();
                $(target).show();
            });
            /* Fim da Animação. */
        };
        
        /* Reiniciando todos os atributos. */
        this.resetAccordion = function () {
            this.accordionElement = null;
            this.accordionHeight = null;
            this.accordionTop = null;
            this.accordionLeft = null;
        };
        
        /* 
         * Anima uma página interna para um item de lista, de fora para dentro. 
         * Parâmetros: id do objeto, elemento que contém a lista, elemento que contém a página destino.
         * */
        this.reverseAccordion = function (element, target) {            
            $(element).show();
            $(target).hide();
            
            var top = this.accordionTop + (this.accordionHeight/2);
            
            /* Recuperando elemento a ser animado. */
            var mainElement = this.accordionElement;
            mainElement.show();
            
            /* Início da Animação. */
            mainElement.animate({
                'top': top+'px',
                'height': '0'
            }, 500, function () {
                this.resetAccordion;
                mainElement.remove();
            });
            /* Fim da Animação. */
        };
        
        
    }]);
})();