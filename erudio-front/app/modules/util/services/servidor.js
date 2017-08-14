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
    var servidorModule = angular.module('servidorModule', ['toastModule','angular-md5','angular-sha1']);

    servidorModule.service('Servidor', ['Restangular', 'Toast', '$timeout', '$http', 'md5', 'sha1', '$templateCache', function(Restangular, Toast, $timeout, $http, md5, sha1, $templateCache) {
        $templateCache.removeAll();
        
        /* Testa se input tem somente nùmeros */
        this.somenteNumeros = function (valor) {
            if (valor.match(/[^0-9]/g)) { return false; }
            return true;
        };

        this.inputNumero = function() {
            $('.numeros').keypress(function(event) {
                var tecla = (window.event) ? event.keyCode : event.which;
                if ((tecla > 47 && tecla < 58)) {
                    return true;
                } else {
                    if (tecla === 8 || tecla === 0) {
                        return true;
                    } else {
                        return false;
                    }
                }
            });
        };

        /* Prepara a autenticação */
        /*this.criarHeader = function () {
            var username = sessionStorage.getItem('username');
            var key = sessionStorage.getItem('key');
            var created = moment().format();
            var nonce = this.guid();
            var nonceSend = btoa(nonce);
            var digest = btoa(sha1.hash(nonce + created + key));
            var header = 'UsernameToken Username="' + username + '", PasswordDigest="' + digest + '", Nonce="' + nonceSend + '", Created="' + created + '"';
            return header;
        };*/

        /* Prepara a chamada restangular */
        /*this.preparaRestangular = function () {
            var header = this.criarHeader();
            //var rest = Restangular.withConfig(function(conf){ conf.setDefaultHeaders({ "X-WSSE": header }); });
            var rest = Restangular;
            return rest;
        };*/
            
        //GET PDF
        this.getPDF = function(url){
            var token = "Bearer "+sessionStorage.getItem('token');
            return $http.get(url,{headers: {"JWT-Authorization":token},responseType: 'arraybuffer'}).success(function(data){
                var file = new Blob([data],{type: 'application/pdf'});
                var fileURL = URL.createObjectURL(file);
                window.open(fileURL);
            });
        };
        
        //PREPARA HEADER X-WSSE
        this.criarHeader = function () { var token = sessionStorage.getItem('token'); var header = "Bearer "+token; return header; };

        //INSERE O HEADER NA CHAMADA REST
        this.preparaRestangular = function() {
            var header = this.criarHeader();
            var rest = Restangular.withConfig(function(conf){ conf.setDefaultHeaders({ "JWT-Authorization": header }); });
            return rest;
        };

        /* Remove os tooltipps */
        this.removeTooltipp = function () {
            $('.tooltipped').tooltip('remove');
        };

        /* Cria o nonce */
        this.guid = function () {
            function s4() {
                return Math.floor((1 + Math.random()) * 0x10000)
                  .toString(16)
                  .substring(1);
            }

            return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
        };

        /* Faz a busca inicial */
        this.buscarPromise = function(endereco) {
            var rest = this.preparaRestangular();
            rest.setFullResponse(true);
            return rest.all(endereco);
        };

        /* Busca um elemento apenas */
        this.buscarUm = function(endereco, id) {
            var rest = this.preparaRestangular();
            rest.setFullResponse(true);
            return rest.one(endereco, id).get();
        };

        /* Busca com opções e retorna a promise para percorrer - RECOMENDADO */
        this.buscar = function(endereco, opcoes) {
            var rest = this.preparaRestangular();
            rest.setFullResponse(true);
            return rest.all(endereco).getList(opcoes);
        };
        
        this.getDate = function() {
            var rest = this.preparaRestangular();
            rest.setFullResponse(true);
            return rest.one('dias/atual').get();
        };

        /* Valida todos os campos dentro de um elemento pai */
        this.validar = function (id) {
            var counter = 0;
            var required = 0;
            var retorno = '';
            var $d = document.getElementById(id).querySelectorAll('.input-field');

            Array.prototype.forEach.call($d, function(d) {
                var input = d.querySelector('input');
                if (input !== null) {
                    if ($("#"+input.id).hasClass('ng-invalid') && !$("#"+input.id).hasClass('ng-invalid-required')) { $("#"+input.id).addClass('invalid'); counter += 1;
                    } else { $("#"+input.id).removeClass('invalid'); }
                    if ($("#"+input.id).hasClass('ng-invalid-required')) { 
                        required += 1;
                        if (typeof $('#'+input.id).attr('data-label') !== typeof undefined && $('#'+input.id).attr('data-label') !== false) { retorno += $('#'+input.id).attr('data-label') + ', '; }
                    }
                }

                var select = d.querySelector('select');
                if (select !== null) {
                    if ($("#"+select.id).hasClass('ng-invalid') && !$("#"+select.id).hasClass('ng-invalid-required')) { $("#"+select.id).addClass('invalid'); counter += 1;
                    } else { $("#"+select.id).removeClass('invalid'); }
                    if ($("#"+select.id).hasClass('ng-invalid-required')) { 
                        required += 1;
                        if (typeof $('#'+select.id).attr('data-label') !== typeof undefined && $('#'+select.id).attr('data-label') !== false) { retorno += $('#'+select.id).attr('data-label') + ', '; }
                    }
                }
            });

            if (counter > 0 || required > 0) {
                if (required > 0) {
                    Materialize.toast('Há campos obrigatórios não preenchidos!', 2000);
                    if (retorno !== ''){ Materialize.toast('Campos não preenchidos: ' + retorno, 5000); }
                }
                return false;
            } else {
                return true;
            }
        };

        /* Reinicia o validador para nova chamada */
        this.resetarValidador = function (id) {
            var $d = document.getElementById(id).querySelectorAll('.input-field');
            Array.prototype.forEach.call($d, function(d) {
                var input = d.querySelector('input');
                if (input !== null) {
                    $("#"+input.id).removeClass('invalid');
                }
            });
        };

        /* Envia uma entidade ao servidor para salvar/modificar */
        this.finalizar = function(objeto,endereco,label) {
            var result = [];
            if (objeto !== null && objeto.id) {
                if (objeto.route === undefined) { objeto.route = endereco; }
                result = objeto.put();
                result.then(function (data){
                    if (data.status >= 200 || data.status <= 204) {
                        if (label !== null && label !== '') { Toast.show(label, 'modificado(a)', data.status); }
                    } else {
                        result = false;
                    }
                }, function(error) { Toast.show(error.data.error.message,'', error.data.error.code); });
            } else {
                var promise = this.buscarPromise(endereco);
                result = promise.post(objeto);
                result.then(function(data) {
                    if (data.status >= 200 || data.status <= 204) {
                        if (label !== null && label !== '') { Toast.show(label, 'salvo(a)', data.status); }
                    } else {
                        result = false;
                    }
                }, function(error) { Toast.show(error.data.error.message,'', error.data.error.code); });
            }
            return result;
        };
        
        this.customPut = function (objeto, endereco, label) {
            resultado = $http({
                method: "PUT",
                url: Restangular.configuration.baseUrl + "/" + endereco,
                data: objeto,
                headers: {'JWT-Authorization': this.criarHeader()}
            });
            resultado.then(function(data){
                if (data.status === 200 || data.status === 204) {
                    if (label) { Toast.show(label, 'salvo(a)', data.status); }
                } else {
                    resultado = false;
                }
            }, function(error) { Toast.show('','', error.status); });
            return resultado;
        };

        this.salvarLote = function(objeto, endereco, label) {
            resultado = $http({
                method: "PUT",
                url: Restangular.configuration.baseUrl + "/" + endereco,
                data: objeto,
                headers: {'JWT-Authorization': this.criarHeader() }
            });
            resultado.then(function(data){
                if (data.status === 200 || data.status === 204) {
                    if (label) { Toast.show(label, 'salvo(a)', data.status); }
                } else {
                    resultado = false;
                }
            }, function(error) { Toast.show('','', error.status); });
            return resultado;
        };

        this.excluirLote = function(objeto,endereco) {
            var resultado = $http({
                method: "DELETE",
                url: Restangular.configuration.baseUrl + "/" + endereco,
                data: objeto,
                headers: {'Content-Type': 'application/json', 'JWT-Authorization': this.criarHeader()}
                //headers: {'Content-Type': 'application/json'}
            });
            return resultado;
        };

        /* Envia uma entidade ao servidor para remover */
        this.remover = function (objeto,label) {
            var result = objeto.remove();
            result.then(function (data){
                if (label !== null && label !== '') {
                    Toast.show(label, 'removido(a)', data.status);
                    return result;
                }
            });
        };

        /* Checa os labels ativos dos inputs */
        this.verificaLabels = function (){
            $('.input-field').each(function (){
                var val = $(this).find('input[class!="select-dropdown"]').val();
                if (val !== null && val !== undefined && val !== '') {
                    $(this).find('label').addClass('active');
                } else {
                    $(this).find('label').removeClass('active');
                }
                if ($(this).find('input').hasClass('select-dropdown')) {
                    $(this).find('label').removeClass('active');
                }
            });
        };

        /* Busca todos os estados */
        this.buscarEstados = function () {
            var rest = this.preparaRestangular();
            return rest.all('estados');
        };

        /* Busca endereço pelo endereço do CEP */
        var endereco = [];
        this.consultaCep = function (cep) {
            endereco = [];
            $.getScript('http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep='+cep, function (){
                if (resultadoCEP.resultado) {
                    endereco[0] = unescape(resultadoCEP.tipo_logradouro) + " " + unescape(resultadoCEP.logradouro);
                    endereco[1] = unescape(resultadoCEP.bairro);
                    endereco[2] = unescape(resultadoCEP.cidade);
                    endereco[3] = unescape(resultadoCEP.uf);
                }
            });
        };

        /* Busca endereço encontrado */
        this.recuperaCep = function (){
            return endereco;
        };

        /* Validar hora informada */
        this.validarFormatoHora = function (valor, classe){
            var hora = valor.split(':');
            if (hora.length > 0) {
                if (hora[0].length === 2) {
                    if (parseInt(hora[0]) < 0 || parseInt(hora[0]) > 23) {
                        var $d = document.getElementById("validate").querySelectorAll('paper-input-container');
                        Array.prototype.forEach.call($d, function(d) {
                            if ($(d).find("input").hasClass(classe)){
                                d.invalid = true;
                            }
                        });
                        return false;
                    }
                    if (parseInt(hora[1]) < 0 || parseInt(hora[1]) > 59) {
                        var $d = document.getElementById("validate").querySelectorAll('paper-input-container');
                        Array.prototype.forEach.call($d, function(d) {
                            if ($(d).find("input").hasClass(classe)){
                                d.invalid = true;
                            }
                        });
                        return false;
                    }
                } else {
                    var $d = document.getElementById("validate").querySelectorAll('paper-input-container');
                    Array.prototype.forEach.call($d, function(d) {
                        if ($(d).find("input").hasClass(classe)){
                            d.invalid = true;
                        }
                    });
                    return false;
                }

                var $d = document.getElementById("validate").querySelectorAll('paper-input-container');
                Array.prototype.forEach.call($d, function(d) {
                    if ($(d).find("input").hasClass(classe)){
                        d.invalid = false;
                    }
                });
                return true;
            }
        };

        /* Validar diferença entre horas */
        this.validarDiferencaHoras = function (inicio,termino, classeInicio, classTermino){
            var horaInicio = inicio.split(':');
            var horaTermino = termino.split(':');

            if (horaInicio[0].length === 2 && horaTermino[0].length === 2) {
                /* Hora inicial maior = inválido */
                if(parseInt(horaInicio[0]) !== parseInt(horaTermino[0]) && parseInt(horaInicio[0]) > parseInt(horaTermino[0])) {
                    var $d = document.getElementById("validate").querySelectorAll('paper-input-container');
                    Array.prototype.forEach.call($d, function(d) {
                        if ($(d).find("input").hasClass(classeInicio)){ d.invalid = true; }
                        if ($(d).find("input").hasClass(classTermino)){ d.invalid = true; }
                    });
                    return false;
                } else {
                    /* Horas iguais e minuto inicial maior = inválido */
                    if (parseInt(horaInicio[1]) > parseInt(horaTermino[1])) {
                        var $d = document.getElementById("validate").querySelectorAll('paper-input-container');
                        Array.prototype.forEach.call($d, function(d) {
                            if ($(d).find("input").hasClass(classeInicio)){ d.invalid = true; }
                            if ($(d).find("input").hasClass(classTermino)){ d.invalid = true; }
                        });
                        return false;
                    }
                }

                /* Diferença Válida */
                var $d = document.getElementById("validate").querySelectorAll('paper-input-container');
                Array.prototype.forEach.call($d, function(d) {
                    if ($(d).find("input").hasClass(classeInicio)){ d.invalid = false; }
                    if ($(d).find("input").hasClass(classTermino)){ d.invalid = false; }
                });
                return true;
            }
        };

        //Formata a hora de HH:mm:ss para HH:mm
        this.formatarHora = function (hora) {
            var separar = hora.split(':');
            if (separar.length === 3) {
                hora = separar[0]+':'+separar[1];
            } else {
                hora = hora + ":00";
            }
            return hora;
        };
        
        
        this.formataDataBanco = function (data) {
            var dataArr = data.split('/');
            return dataArr[2] + '-' + dataArr[1] + '-' + dataArr[0];
        };
        
        this.formataDataFront = function (data) {
            var dataArr = data.split('-');
            return dataArr[0] + '/' + dataArr[1] + '/' + dataArr[2];
        };

        /*Validador de cpf*/
        this.validarCpf = function (cpf){
            if (cpf === "00000000000" || cpf === "11111111111" || cpf === "22222222222" ||
                cpf === "33333333333" || cpf === "44444444444" || cpf === "55555555555" ||
                cpf === "66666666666" || cpf === "77777777777" || cpf === "88888888888" ||
                cpf === "99999999999") {
                return false;
            }
            var arrayCpf = cpf.split('');
            var primeiroDigito;
            var segundoDigito;
            /*Primeiro Digito*/
            var tamanho = cpf.length-1;
            var soma = 0;
            for(var i = 0; i < 9; i++){                
                soma += parseInt(arrayCpf[i])*tamanho--;
            }
            soma *= 10;
            primeiroDigito = (soma%11 === 10) ? 0 : soma%11;
            if(primeiroDigito !== parseInt(arrayCpf[cpf.length-2])){
                return false;
            /*Se o primeiro for valido verifica o segundo digito*/
            }else{
                soma = 0;
                tamanho = cpf.length;
                for(var i = 0; i < 10; i++){
                    soma += parseInt(arrayCpf[i])*tamanho--;                    
                }
                soma *= 10;
                segundoDigito = (soma%11 === 10) ? 0 : soma%11;
                if(segundoDigito === parseInt(arrayCpf[cpf.length-1])){
                    return true;
                }else{
                    return false;
                }
            }
        };

        /* Validador de CNPJ */
        this.validarCnpj = function (cnpj) {
            if (cnpj !== null && cnpj !== undefined) {
                //cnpj = cnpj.toString();
                console.log(cnpj);
                /* Proibe Números Iguais */
                if (cnpj === "00000000000000" || cnpj === "11111111111111" || cnpj === "22222222222222" ||
                    cnpj === "33333333333333" || cnpj === "44444444444444" || cnpj === "55555555555555" ||
                    cnpj === "66666666666666" || cnpj === "77777777777777" || cnpj === "88888888888888" ||
                    cnpj === "99999999999999") {
                    if(!document.getElementById("validate")){
                        var $d = document.getElementById("validate-unidade").querySelectorAll('.input-field');
                    } else {
                        var $d = document.getElementById("validate-unidade").querySelectorAll('.input-field');
                    }
                    Array.prototype.forEach.call($d, function(d) {
                        var input = d.querySelector('input');
                        if (input !== null) {
                            if ($("#"+input.id).hasClass('cnpj')){
                                $("#"+input.id).addClass('invalid');
                            }
                        }
                    });
                    this.customToast("CNPJ Invalido");
                    return false;
                }

                /* Cálculo do dígito verificador */
                var tamanho = cnpj.length - 2;
                var numeros = cnpj.substring(0,tamanho);
                var digitos = cnpj.substring(tamanho);
                var soma = 0;
                var pos = tamanho - 7;

                for (var i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2) {
                        pos = 9;
                    }
                }

                /* Dígito verificador inválido */
                var resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado !== parseInt(digitos.charAt(0))){
                    console.log(document.getElementById("validate"));
                    if(!document.getElementById("validate")){
                        var $d = document.getElementById("validate-unidade").querySelectorAll('.input-field');
                    }else{
                        var $d = document.getElementById("validate").querySelectorAll('.input-field');
                    }
                    Array.prototype.forEach.call($d, function(d) {
                        var input = d.querySelector('input');
                        if (input !== null) {
                            if ($("#"+input.id).hasClass('cnpj')){
                                $("#"+input.id).addClass('invalid');
                            }
                        }
                    });
                    alert('1');
                    this.customToast("CNPJ Invalido");
                    return false;
                }

                tamanho = tamanho + 1;
                numeros = cnpj.substring(0,tamanho);
                soma = 0;
                pos = tamanho - 7;

                for (var i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2) {
                          pos = 9;
                    }
                }

                /* Outro dígito verificador inválido */
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado !== parseInt(digitos.charAt(1))){
                    var $d = document.getElementById("validate").querySelectorAll('.input-field');
                    Array.prototype.forEach.call($d, function(d) {
                        var input = d.querySelector('input');
                        if (input !== null) {
                            if ($("#"+input.id).hasClass('cnpj')){
                                $("#"+input.id).addClass('invalid');
                            }
                        }
                    });
                    alert('2');
                    this.customToast("CNPJ Invalido");
                    return false;
                }

                /* Dígito verificador válido */
                if(!document.getElementById("validate")){
                    var $d = document.getElementById("validate-unidade").querySelectorAll('.input-field');
                } else {
                    var $d = document.getElementById("validate").querySelectorAll('.input-field');
                }
                Array.prototype.forEach.call($d, function(d) {
                    var input = d.querySelector('input');
                    if (input !== null) {
                        if ($("#"+input.id).hasClass('cnpj')){
                            $("#"+input.id).removeClass('invalid');
                        }
                    }
                });
                return true;
            }

            return true;
        };

        /* Gera um toast customizado */
        this.customToast = function (msg) {
            Materialize.toast(msg, 4000);
        };

        this.cardSai = function (itens, comBusca) {
            if (comBusca) { $('.busca').fadeOut(); }
            for (var i=0; i<itens.length; i++) { $(itens[i]).css('transform','scale(0.8)').css('opacity','0'); }
        };

        this.cardSaiVolta = function (itens, comBusca) {
            if (comBusca) { $('.busca').fadeIn(); }
            for (var i=0; i<itens.length; i++) { $(itens[i]).css('transform','').css('opacity',''); }
        };

        this.entradaSequencialIn = function (item, total) {
            var delay = 0;
            $(item).each(function(){
                delay = delay + 0.2;
                var style = $(this).attr('style');
                style += ' transition-delay: ' + delay + 's;';
                $(this).attr('style',style);
            });
            $(item).css('transform','').css('opacity','');
        };

        this.cardEntra = function(item) {
            $('.btn-voltar').fadeIn();
            $(item).css('transform','scale(1)').css('opacity','1');
        };

        this.cardEntraVolta = function (item) {
            $('.btn-voltar').fadeOut();
            $(item).css('transform','').css('opacity','');
        };

        this.verificarPermissoes = function (roleName) {
            if (roleName.length > 0) {
                var str = 'ROLE_' + roleName;
                var roles = sessionStorage.getItem('roles');
                var flag = false;
                roles = JSON.parse(roles);                
                if(roles === undefined || !roles) {
                    return false;
                }
                if (roles.length > 0) {
                    for (var i=0; i<roles.length; i++) {
                        var nome = roles[i].permissao.nomeIdentificacao;
                        if (nome === str || nome === 'ROLE_SUPER_ADMIN') { flag = true; break; }
                    }
                } else {
                    flag = false;
                }
                return flag;
            } else {
                return false;
            }
        };
        
        /* Função para desabilitar todos os campos que não são de busca quando usuário não possuir permissão de escrita
            if(!$scope.escrita) { $('input:not[class=busca]').attr('disabled'); }
            if(!$scope.escrita) { $('select:not[class=busca]').attr('disabled'); }
        */

        this.verificaAdmin = function() {
            var roles = JSON.parse(sessionStorage.getItem('roles'));
            for(var i = 0; i < roles.length; i++) {
                var nome = roles[i].permissao.nomeIdentificacao;
                if (nome === 'ROLE_SUPER_ADMIN' || nome === 'ROLE_ADMIN') {
                    return true;
                } else if (i === roles.length - 1) {
                    return false;
                }
            }
        };

        this.verificaEscrita = function (role) {
            if(this.verificaAdmin()) { return true; }
            if (role.length > 0) {
                var roleNome = 'ROLE_' + role;
                var roles = sessionStorage.getItem('roles');
                roles = JSON.parse(roles);
                var flag = false;
                for (var i=0; i<roles.length; i++) {
                    if (roles[i].permissao.nomeIdentificacao === roleNome && roles[i].tipoAcesso === 'E') { flag = true; break; }
                }
                return flag;
            } else {
                return false;
            }
        };

        /* Retorna um objeto com atributos booleanos (escrever e ler) */
        this.verificarTipoAcesso = function (nomeRole) {
            var roles = JSON.parse(sessionStorage.getItem('roles'));
            for (var i = 0; i < roles.length; i++) {
                if(roles[i].permissao.nomeIdentificacao === nomeRole) {
                    switch(roles[i].tipoAcesso) {
                        case "E": return { escrever: true, ler: true };
                        case "L": return { escrever: false, ler: true };
                        default: return { escrever: false, ler: false };
                    }
                }
            }
        };

        this.isolarElemento = function (array, id) {
            var result = [];
            if(id && array.length > 0) {
                array.forEach(function (elemento) {
                    if (parseInt(elemento.id) === parseInt(id)) {
                        result.push(elemento);
                    }
                });
                return result;
            }
            return array;
        };
        
        //this.mudarUrl = function (modulo) {
            //setTimeout(function(){ history.replaceState(null,'Erudio','http://' + sessionStorage.getItem('baseFrontUrl') + '/' + modulo); }, 3000);
        //};
        
        this.entradaPagina = function () { $('.loader-module').fadeOut(300); $('.inicio-modulo').css('opacity',1); };
        this.saidaPagina = function () { $('.inicio-modulo').css('opacity',0); };
        
        this.animacaoEntradaForm = function (marginCard) {
            window.scrollTo(0, 0);
            if (marginCard === true) { $('.title-card').addClass('fix-margin-card').addClass('animate-card-out-list'); } else { $('.title-card').addClass('animate-card-out-list'); }
            $('.list-items').addClass('animate-card-in-list');
            $('.card-list').addClass('z-depth-0');
            $('.main-list').addClass('animate-list-out');
            $('.btn-add').addClass('animate-button-out');
            $timeout(function(){
                $('.init-card, .list-items, .form-card-wrapper').addClass('fix-padding-card');
                $timeout(function(){ 
                    $('.btn-voltar').addClass('animate-button-in');
                    $('.form-opacity-out').addClass('form-opacity-in');
                }, 600);
            }, 500);
        };
        
        this.animacaoEntradaLista = function (marginCard){
            //window.scrollTo(0, 0);
            $('.btn-voltar').removeClass('animate-button-in');
            $('.form-opacity-out').removeClass('form-opacity-in');
            $timeout(function (){
                $('.list-card').removeClass('fix-margin-card');
                $('.init-card, .list-items, .form-card-wrapper').removeClass('fix-padding-card');
                if (marginCard === true) { $('.title-card').removeClass('animate-card-out-list').removeClass('fix-margin-card'); } else { $('.title-card').removeClass('animate-card-out-list'); }
                $('.list-items').removeClass('animate-card-in-list');
                $('.card-list').removeClass('z-depth-0');
                $('.main-list').removeClass('animate-list-out');
                $('.btn-add').removeClass('animate-button-out');
            },300);
        };
        
        //NOVAS FUNÇOES, OTIMIZAR DEPOIS
        
        this.verificarMenu = function () {
            var size = 0; var sizeHidden = 0;
            $(".menuLateral").each(function(j){
                size = $(this).find('li').length; sizeHidden = 0;
                $(this).find('li').each(function(i){
                    if ($(this).hasClass('ng-hide')) { sizeHidden++; }
                    if (i+1 === size) { if (size === sizeHidden) { $(this).parents().eq(2).hide(); } else { $(this).parents().eq(2).css('opacity', 1); } }
                });
            });
        };
    }]);
})();
