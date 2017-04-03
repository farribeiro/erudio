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
    var elementosModule = angular.module('elementosModule', []);

    elementosModule.service('Elementos', ['$templateCache', function($templateCache) {
        $templateCache.removeAll();
        
        this.getBotao = function (tipo,id,waves,color,disabled,custom,label,icon,iconPos,target,scope) {
            if (waves !== null || waves !== undefined) { waves = 'waves-effect waves-light'; } if (disabled) { disabled = 'disabled'; }
            var strBtn = '<button id='+id+' class="btn '+tipo+' '+waves+' '+color+' '+disabled+'" '+custom+'>';
            if (icon !== null) { strBtn += '<i class="material-icons '+iconPos+'">'+icon+'</i>'; }
            strBtn += label+'</button>'; //$('#'+target).html('').append(strBtn);
            return true;
        };
        
        this.criarTabela = function (array,titles,attrs) {
            var objArray = []; var keys = []; var str = '';
            
            for (var i=0; i<array.length; i++) { objArray.push(array[i]); }
            if (objArray.length !== 0) { keys = Object.keys(objArray[0]); } else { return false; }
            
            str += '<div class="navbar-fixed">';
            str += '<nav class="material-table-nav">';
            str += '<div class="nav-wrapper white">';
            str += '<a class="brand-logo" style="color: #000; padding-left: 20px;">Teste</a>';
            str += '</div></nav></div>';
            str += '<div class="row card table-listagem">';
            str += '<table style="margin-top: 64px;" class="material-table">';
            str += '<thead><tr class="tr-head">';
            for (var i=0; i<titles.length; i++) { str += '<th class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Teste">'+titles[i]+'</th>'; }
            str += '</tr></thead>';
            str += '<tbody>';
            for (var i in objArray) {
                for (var j in objArray[i]) {
                    for (var a in attrs) {
                        if (attrs[a].indexOf(j) !== -1) {
                            //console.log(eval("objArray[i].attrs[a]"));
                            //console.log(objArray[i][j]);
                        }
                    }
                }
            }
            
            str += '</tbody>';
            str += '</div>';
            
            return str;
            /*$('input[type="radio"]').click(function(){
                $('.material-table thead tr, .material-table tbody tr').css('background',''); $(this).parents().eq(2).css('background','#F5F5F5'); $('.opcoes').show();
                //truncate th if overflow
            });*/
            /*
             * 
        
            
                
                <ul class="right hide-on-med-and-down opcoes" style="display: none;">
                    <li><a style="color: #000;" href="#"><i class="material-icons">people</i></a></li>
                    <li><a style="color: #000;" href="#"><i class="material-icons">local_library</i></a></li>
                    <li><a style="color: #000;" href="#"><i data-position='top' data-delay='50' data-tooltip='Movimentações' class="material-icons tooltipped">swap_horiz</i></a></li>
                    <li><a style="color: #000;" href="#"><i data-position='top' data-delay='50' data-tooltip='Atestado de Matrícula e Frequência' class="material-icons tooltipped">local_printshop</i></a></li>
                    <li><a style="color: #000;" href="#"><i data-position="top" data-delay="50" data-tooltip="Reativar" class="material-icons tooltipped">arrow_upward</i></a></li>
                </ul>
                
            
                <tr>
                    <td style="width: 40px;"><div style="width: 40px; padding-left: 15px;" class="center-align"><input type="radio" id="teste1" class="filled-in" name="teste" /><label for="teste1">&nbsp;</label></div></td>
                    <td class="right-align">20171100285</td> <td>Samuel Siebert dos Santos</td> <td>Educação Infantil</td> <td>CURSANDO</td> <td>E.B Francisco Celso Mafra</td>
                </tr>
                <tr>
                    <td style="width: 40px;"><div style="width: 40px; padding-left: 15px;" class="center-align"><input type="radio" id="teste2" class="filled-in" name="teste" /><label for="teste2">&nbsp;</label></div></td>
                    <td class="right-align">20171100294</td> <td>Mariany Siebert Vieira</td> <td>Educação Infantil</td> <td>CURSANDO</td> <td>E.B Francisco Celso Mafra</td>
                </tr>
            
            <tfoot>
                <tr>
                    <td colspan="4">&nbsp;</td>
                    <td>1-10 de 100</td>
                    <td class="right-align"><i style="margin-left: 10px !important;" class="material-icons">first_page</i> <i class="material-icons">chevron_left</i> <i style="margin-left: 20px !important;" class="material-icons">chevron_right</i> <i style="margin-right: 20px;" class="material-icons">last_page</i></td>
                </tr>
            </tfoot>
        </table>
    
             */
        };
    }]);
})();
