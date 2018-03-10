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
    /*
     * @ErudioDoc Aula Service
     * @Module aulas
     * @Controller AulaService
     */
    'use strict';
    class AulaService {
        constructor(rest){
            this.rest = rest;
            this.url = 'professor/aulas';
            this.urlAnotacoes = 'professor/anotacoes-aula';
        }
        /*
         * @method get
         * @methodReturn Object
         * @methodParams id|Int,loader|Boolean
         * @methodDescription Busca uma aula pelo id.
         */
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        /*
         * @method getAll
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Busca uma lista de aulas.
         */
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        /*
         * @method getAnotações
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Busca uma lista de anotações de aula.
         */
        getAnotacoes(opcoes,loader){ return this.rest.buscar(this.urlAnotacoes,opcoes,loader); }
        /*
         * @method getEstruturaAula
         * @methodReturn Object
         * @methodDescription Retorna a estrutura da aula.
         */
        getEstruturaAula() { return { turma: {id:null}, dia: {id:null}, horario: {id:null}, disciplina: {id:null} }; }
        /*
         * @method getEstrutura
         * @methodReturn Object
         * @methodDescription Retorna a estrutura do array de aulas.
         */
        getEstrutura() { return { aulas: [] }; }
        /*
         * @method getEstruturaAnotacao
         * @methodReturn Object
         * @methodDescription Retorna a estrutura da anotação de aula.
         */
        getEstruturaAnotacao() { return { aula:{id:null}, conteudo:null }; }
        /*
         * @method salvar
         * @methodReturn Object
         * @methodParams objeto|Object,loader|Boolean
         * @methodDescription Cria uma aula nova.
         */
        salvar(objeto,loader) { return this.rest.salvarLote(objeto, this.url, "Aula", "F", loader, true); }
        /*
         * @method salvarAnotação
         * @methodReturn Object
         * @methodParams objeto|Object,loader|Boolean
         * @methodDescription Cria uma anotação nova.
         */
        salvarAnotacao(objeto,loader) { return this.rest.salvarLote(objeto, this.urlAnotacoes, "Anotação", "F", loader, true); }
        /*
         * @method atualizar
         * @methodReturn Object
         * @methodParams objeto|Object,loader|Boolean
         * @methodDescription Atualiza uma aula.
         */
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Aula", "F",loader); }
        /*
         * @method atualizarAnotacao
         * @methodReturn Object
         * @methodParams objeto|Object,loader|Boolean
         * @methodDescription Atualiza uma anotação.
         */
        atualizarAnotacao(objeto,loader) { return this.rest.atualizar(objeto, "Anotação", "F",loader); }
        /*
         * @method remover
         * @methodReturn Void
         * @methodParams objeto|Object,loader|Boolean
         * @methodDescription Remove uma anotação ou aula
         */
        remover(objeto,loader,anotacao) {
            if (anotacao) { this.rest.remover(objeto, "Anotação", "F",loader); } else { this.rest.remover(objeto, "Aula", "F",loader); }
        }
    };

    angular.module('AulaService',[]).service('AulaService',AulaService);
    AulaService.$inject = ["BaseService"];
})();