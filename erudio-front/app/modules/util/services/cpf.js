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
    var CPFModule = angular.module('CPFModule', []);

    CPFModule.service('ValidarCPF', [function() {
        this.validar = function (cpf) {
            if (cpf === "00000000000" || cpf === "11111111111" || cpf === "22222222222" || cpf === "33333333333" || cpf === "44444444444" || cpf === "55555555555" ||
                cpf === "66666666666" || cpf === "77777777777" || cpf === "88888888888" || cpf === "99999999999") { return false; }
            var arrayCpf = cpf.split(''); var primeiroDigito = ''; var segundoDigito = '';

            /* Verifica Primeiro Digito */
            var tamanho = cpf.length-1; var soma = 0;
            for(var i = 0; i <= 9; i++){
                if(i < 9){ soma = soma + (arrayCpf[i]*tamanho--); }
                if(i === 8){ soma = soma*10; }
            }
            if(soma % 11 === 10 || soma % 11 === 11){ primeiroDigito = 0; }else{ primeiroDigito = soma % 11; }
            if(primeiroDigito !== parseInt(arrayCpf[9])){ return false;
            /* Se o primeiro for valido verifica o segundo digito */
            }else{
                soma = 0; tamanho = cpf.length;
                for(var i = 0; i <= 10; i++){
                    if(i < 10){ soma = soma + (arrayCpf[i]*tamanho--); }
                    if(i === 9){ soma = soma*10; }
                }
                if(soma % 11 === 10 || soma % 11 === 11){ segundoDigito = 0;
                }else{
                    segundoDigito = soma % 11;
                    if(segundoDigito === parseInt(arrayCpf[10])){ return true; }else{ return false; }
                }
            }
        };
    }]);
})();
