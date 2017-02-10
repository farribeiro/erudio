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
    var FormModule = angular.module('FormModule', ['toastModule']);

    FormModule.service('Form', ['Toast',function(Toast) {
        this.validarNumeros = function (val) { if (val.match(/[^0-9]/g)) { return false; } return true; };
        
        
        this.validarVazio = function (val,id) {
            var contador = 0; var obrigatorios = 0;
            // Busca todos os 'input-field' contidos no 'id'
            var $d = document.getElementById(id).querySelectorAll('.input-field');

            Array.prototype.forEach.call($d, function(d) {
                // Procura o elemento input
                var input = d.querySelector('input');
                if (input !== null) {
                    // Se o input estiver vazio e não for obrigatório, conta e passa reto
                    if ($("#"+input.id).hasClass('ng-invalid') && !$("#"+input.id).hasClass('ng-invalid-required')) { $("#"+input.id).addClass('invalid'); contador += 1;
                    } else { $("#"+input.id).removeClass('invalid'); }
                    // Se for obrigatório entra na conta
                    if ($("#"+input.id).hasClass('ng-invalid-required')) { obrigatorios += 1; }
                }

                // Procura os selects
                var select = d.querySelector('select');
                if (select !== null) {
                    if ($("#"+select.id).hasClass('ng-invalid') && !$("#"+select.id).hasClass('ng-invalid-required')) { $("#"+select.id).addClass('invalid'); contador += 1;
                    } else { $("#"+select.id).removeClass('invalid'); }
                    if ($("#"+select.id).hasClass('ng-invalid-required')) { obrigatorios += 1; }
                }
            });

            if (contador > 0 || obrigatorios > 0) {
                // Se tem obrigatórios não preeenchidos retorna false
                if (obrigatorios > 0) { Toast.custom('Há campos obrigatórios não preenchidos!'); } return false;
            } else { return true; }
        };
        
        this.resetarValidador = function (id) {
            var $d = document.getElementById(id).querySelectorAll('.input-field');
            Array.prototype.forEach.call($d, function(d) {
                var input = d.querySelector('input');
                if (input !== null) { $("#"+input.id).removeClass('invalid'); }
            });
        };
    }]);
})();
