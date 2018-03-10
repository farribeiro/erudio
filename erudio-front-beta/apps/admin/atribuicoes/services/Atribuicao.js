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
     * @ErudioDoc Atribuições Service
     * @Module atribuicoes
     * @Controller AtribuicaoService
     */
    'use strict';
    class AtribuicaoService {
        constructor(rest,grupoService,permissaoService){
            this.rest = rest;
            this.grupoService = grupoService;
            this.permissaoService = permissaoService;
            this.url = 'atribuicoes';
        }
        /*
         * @method get
         * @methodReturn Object
         * @methodParams id|Int,loader|Boolean
         * @methodDescription Busca uma atribuição pelo id.
         */
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        /*
         * @method getAll
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Busca uma lista de atribuições.
         */
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        /*
         * @method getGrupos
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Busca uma lista de grupos de permissão.
         */
        getGrupos(opcoes,loader){ return this.grupoService.getAll(opcoes,loader); }
        /*
         * @method getEstrutura
         * @methodReturn Object
         * @methodDescription Retorna a estrutura da alocação.
         */
        getEstrutura() { return { tipoAcesso:null, grupo:{id:null}, permissao:{id:null} }; }
        /*
         * @method salvar
         * @methodReturn Object
         * @methodParams objeto|Object,loader|Boolean
         * @methodDescription Cria uma atribuição nova.
         */
        salvar(objeto,loader) { return this.rest.salvar(this.url, objeto, "Atribuição", "F",loader); }
        /*
         * @method atualizar
         * @methodReturn Object
         * @methodParams objeto|Object,loader|Boolean
         * @methodDescription Atualiza uma atribuição.
         */
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Atribuição", "F",loader); }
        /*
         * @method remover
         * @methodReturn Void
         * @methodParams objeto|Object,loader|Boolean
         * @methodDescription Remove uma alocação
         */
        remover(objeto,loader) { this.rest.remover(objeto, "Atribuição", "F",loader); }
    };

    angular.module('AtribuicaoService',[]).service('AtribuicaoService',AtribuicaoService);
    AtribuicaoService.$inject = ["BaseService","GrupoService","PermissaoService"];
})();