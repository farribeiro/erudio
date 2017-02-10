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
    var dateTimeModule = angular.module('dateTimeModule', []);
    dateTimeModule.service('dateTime', [function() {
        
        function toDateObject (data) {
            if (data.split('/').length > 1) {
                var dia = parseInt(data.split('/')[0]);
                var mes = parseInt(data.split('/')[1]);
                var ano = parseInt(data.split('/')[2]);
            } else {
                dia = parseInt(data.split('-')[2]);
                mes = parseInt(data.split('-')[1]);
                ano = parseInt(data.split('-')[0]);
            }
            return  {
                dia: dia,
                mes: mes,
                ano: ano
            };
        }
        
        /**
         * @example Retorna o número do dia da semana
         * @param {String} data
         * @returns {Number}
         */
        this.getDiaDaSemana = function(data) {
            var diaDaSemana;
            if (data) {
                diaDaSemana = data.toDateString().split(' ')[0];
            } else {
                diaDaSemana = new Date().toDateString().split(' ')[0];
            }
            switch (diaDaSemana) {
                case 'Sun': diaDaSemana = 1; break
                case 'Mon': diaDaSemana = 2; break
                case 'Tue': diaDaSemana = 3; break
                case 'Wed': diaDaSemana = 4; break
                case 'Thu': diaDaSemana = 5; break
                case 'Fri': diaDaSemana = 6; break
                case 'Sat': diaDaSemana = 7; break
            }
            return diaDaSemana;
        };
        
        /**
         * @example Verifica se a data esta entre a data inicio e a data termino
         * @param {date} data -> Data a ser comparada
         * @param {date} inicio -> Data de início
         * @param {date} termino -> Data de término
         * @returns {boolean}
         */
        this.dateBetween = function (data, inicio, termino) {
            if (this.dateLessOrEqual(data, termino) && this.dateLessOrEqual(inicio, data)) {
                return true;
            }
            return false;
        };                
        
        /**
         * @example Verifica se a data de inicio e menor que a de termino
         * @param {date} inicio -> Data de inicio
         * @param {date} termino -> Data de termino                  
         * @returns {Boolean}        
         */
        this.dateLessOrEqual = function (inicio, termino) {            
            inicio = inicio.split('-'); termino = termino.split('-');
            if (inicio[0] < termino[0]) {
                return true;
            } else if (inicio[0] === termino[0]) {
                if (inicio[1] < termino[1]) {
                    return true;                        
                } else if(inicio[1] === termino[1]) { 
                    if (inicio[2] <= termino[2]) { return true; }
                } 
            }
            return false;
        };
        
        /**
         * @example Verifica se a primeira data é maior ou igual que a segunda data
         * @param {date} a -> Primeira data
         * @param {date} b -> Segunda data                  
         * @returns {Boolean}        
         */
        this.dateGreaterOrEqual = function (a, b) {
            a = a.split('-'); b = b.split('-');            
            if (a[0] > b[0]) {
                return true;
            } else if (a[0] === b[0]) {
                if (a[1] > b[1]) {
                    return true;                        
                } else if(a[1] === b[1]) { 
                    if (a[2] >= b[2]) { return true; }
                } 
            }
            return false;
        };
        
        /**
         * @example Verifica se a data de inicio é menor que a data de termino
         * @param {date} inicio
         * @param {date} termino
         * @returns {Boolean}
         */
        this.dateLessThan = function (inicio, termino) {            
            inicio = inicio.split('-'); termino = termino.split('-');     
            if (inicio[0] < termino[0]) {
                return true;
            } else if (inicio[0] === termino[0]) {
                if (inicio[1] < termino[1]) {
                    return true;
                } else if (inicio[1] === termino[1]) {
                    if (inicio[2] < termino[2]) { return true; }
                }
            }
            return false;
        };
        
        /**
         * @example Retorna o telefone no formato ( ) 0000-0000
         * @param {String} telefone
         * @returns {String}
         */
        this.formatarTelefone = function(telefone){
            var codigoPostal, numero;
            codigoPostal = telefone.slice(0, 2);
            numero = telefone.slice(2);
            numero = numero.slice(0, numero.length/2) + '-' + numero.slice(numero.length - numero.length/2);
            return ("(" + codigoPostal + ") " + numero).trim();
        };
        
        /**
         * @example Retorna o horário no formato 00:00
         * @param {String} horario
         * @returns {String}
         */
        this.formatarHorario = function(horario) {
            var array = horario.split(':');
            horario = array[0] + ':' + array[1];
            return horario;
        };
        
        /**
         * @example Converte o formato da data de 0000-00-00 para 00/00/0000
         * @param {date} data
         * @returns {date}
         */
        this.converterDataForm = function (data) {            
            if (data && data !== undefined) {
                var arrayData = data.split('T')[0].split('-');
                data = arrayData[2] + '/' + arrayData[1] + '/' + arrayData[0];                
            }            
            return data;            
        };
        
        /**
         * @example Converte o formato da data de 00/00/0000 para 0000-00-00
         * @param {date} data
         * @param {type} timezone Retorna com a timezone
         * @returns {date}
         */
        this.converterDataServidor = function (data, timezone) {
            if (data !== undefined && data) {
                var arrayData = data.split('/');
                if(arrayData.length === 3) {
                    data = arrayData[2] + '-' + arrayData[1] + '-' + arrayData[0];
                }                
//                data = new Date(arrayData[2], (arrayData[1] - 1), arrayData[0]).toJSON().split('T')[0];
                if (timezone) { data += 'T00:00:00'; }
            }
            return data;
        };
                
        /**
         * @example Verifica se a data é válida
         * @param {date} data
         * @returns {Boolean}
         */
        this.validarData = function(data) {           
            if (data.split('/').length > 1) {
                var dia = parseInt(data.split('/')[0]);
                var mes = parseInt(data.split('/')[1]);
                var ano = parseInt(data.split('/')[2]);
            } else {
                dia = parseInt(data.split('-')[2]);
                mes = parseInt(data.split('-')[1]);
                ano = parseInt(data.split('-')[0]);
            }
            if (ano > 0) {
                if (mes > 0 && mes < 13) {
                    var limiteDias = 31;
                    if (mes === 4 || mes === 6 || mes === 9 || mes === 11) {
                        limiteDias = 30;
                    }
                    if (mes === 2) {
                        limiteDias = 28;
                        if (ano%400 === 0) {                            
                            limiteDias = 29;
                        } else if (ano%4 === 0 && ano%100 !==0) {
                            limiteDias = 29;
                        }
                    }
                    if (dia > 0 && dia <= limiteDias) {
                        return true;
                    }
                }
            }
            return false;
        };
                
        /**
         * @example Verifica se a data de término é igual ou maior que a data de início
         * @param {date} termino
         * @param {date} inicio
         * @returns {Boolean}
         */
        this.validarDataAgendamento = function(termino, inicio) {
            if (inicio) {
                if (inicio.split('/').length > 1) {
                    diaInicio = parseInt(inicio.split('/')[0]);
                    mesInicio = parseInt(inicio.split('/')[1]);
                    anoInicio = parseInt(inicio.split('/')[2]);
                } else {
                    diaInicio = parseInt(inicio.split('-')[2]);
                    mesInicio = parseInt(inicio.split('-')[1]);
                    anoInicio = parseInt(inicio.split('-')[0]);
                }                         
            } else {      
                inicio = new Date().toJSON().split('T')[0];
                var diaInicio = parseInt(inicio.split('-')[2]);
                var mesInicio = parseInt(inicio.split('-')[1]);
                var anoInicio = parseInt(inicio.split('-')[0]);
            }
            if (termino.split('/').length > 1) {
                var dia = parseInt(termino.split('/')[0]);
                var mes = parseInt(termino.split('/')[1]);
                var ano = parseInt(termino.split('/')[2]);
            } else {
                dia = parseInt(termino.split('-')[2]);
                mes = parseInt(termino.split('-')[1]);
                ano = parseInt(termino.split('-')[0]);
            }          
            if (ano >= anoInicio) {
                if (ano > anoInicio) { 
                    return true; 
                } else {
                    if (mes > mesInicio) {
                        return true;
                    } else {
                        if (mes === mesInicio && dia >= diaInicio) {
                            return true;
                        }
                    }
                }
            }
            return false;
        };
        
        /**
         * @example Verifica se o horário é válido
         * @param {string} horario
         * @returns {Boolean}
         */
        this.validarHorario = function(horario) {
            var horas = horario.split(':')[0];
            var minutos = horario.split(':')[1];
            if (horas.length <= 2 && minutos.length <= 2 ) {
                horas = parseInt(horas); minutos = parseInt(minutos);
                if (horas >= 0 && horas <= 23 && minutos >= 0 && minutos <= 59) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        };
        
        /**
         * @example Retorna o nome do mês
         * @param {int} mes
         * @returns {String}
         */
        this.converterMes = function(mes){            
            switch (parseInt(mes)){
                case 1: return 'Janeiro';
                case 2: return 'Fevereiro'; 
                case 3: return 'Março'; 
                case 4: return 'Abril'; 
                case 5: return 'Maio'; 
                case 6: return 'Junho'; 
                case 7: return 'Julho'; 
                case 8: return 'Agosto'; 
                case 9: return 'Setembro'; 
                case 10: return 'Outubro'; 
                case 11: return 'Novembro'; 
                case 12: return 'Dezembro'; 
            }
        };
        
        /**
         * @example 1 de Janeiro de 1970
         * @param {Date} data
         * @returns {String} Data por extenso
         */
        this.dataPorExtenso = function(data) {
            var dia = data.getDate();
            var mes = data.getMonth() + 1;
            var ano = data.getFullYear();
            if (mes < 10){mes = '0' + (data.getMonth() + 1);}
            if (dia < 10){dia = '0' + data.getDate();}
            return dia+' de '+ this.converterMes(mes) +' de '+ano;
        };
        
        this.idadePessoa = function(dataNascimento) {
            var dataAtual = new Date().toJSON().split('T')[0];
            var anoAtual = new Date().getFullYear();
            var diaPessoa = dataNascimento.split('-')[2];
            var mesPessoa = dataNascimento.split('-')[1];
            var anoPessoa = parseInt(dataNascimento.split('-')[0]);
            var idade = anoAtual - anoPessoa;
            if (this.dateGreaterOrEqual(diaPessoa + '-' + mesPessoa + '-' + anoAtual, dataAtual)) {
                return --idade;
            }
            return idade;
        };
        
    }]);
})();