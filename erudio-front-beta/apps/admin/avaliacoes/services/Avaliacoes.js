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
     * @ErudioDoc Avaliações Service
     * @Module avaliacoes
     * @Controller AvaliacaoService
     */
    'use strict';
    class AvaliacaoService {
        constructor(rest,cursoService,etapaService,disciplinaService,tipoAvaliacaoService,turmaService, conceitoService, habilidadeService){
            this.rest = rest;
            this.cursoService = cursoService;
            this.etapaService = etapaService;
            this.disciplinaService = disciplinaService;
            this.tipoAvaliacaoService = tipoAvaliacaoService;
            this.turmaService = turmaService;
            this.conceitoService = conceitoService;
            this.habilidadeService = habilidadeService;
            this.urlQuali = 'avaliacoes-qualitativas';
            this.urlQuanti = 'avaliacoes-quantitativas';
            this.urlSalvarQuali = 'notas-qualitativas';
            this.urlSalvarQuanti = 'notas-quantitativas';
        }

        //QUALITATIVA

        /*
         * @method getNotaQualitativa
         * @methodReturn Object
         * @methodParams id|Int,loader|Boolean
         * @methodDescription Busca uma nota qualitativa pelo id.
         */
        getNotaQualitativa(id,loader){ return this.rest.um(this.urlSalvarQuali,id,loader); }
        /*
         * @method getNotasQualitativas
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Busca uma lista de notas qualitativas.
         */
        getNotasQualitativas(opcoes,loader){ return this.rest.buscar(this.urlSalvarQuali,opcoes,loader); }
        /*
         * @method getQualitativa
         * @methodReturn Object
         * @methodParams id|Int,loader|Boolean
         * @methodDescription Busca uma avaliação qualitativa pelo id.
         */
        getQualitativa(id,loader){ return this.rest.um(this.urlQuali,id,loader); }
        /*
         * @method getQualitativas
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Busca uma lista de avaliações qualitativas.
         */
        getQualitativas(opcoes,loader){ return this.rest.buscar(this.urlQuali,opcoes,loader); }
        /*
         * @method getEstruturaQualitativa
         * @methodReturn Object
         * @methodDescription Retorna a estrutura da avaliação qualitativa.
         */
        getEstruturaQualitativa() { return { media: null, disciplina: { id: null }, nome: null, tipo: null }; }
        /*
         * @method getEstruturaNotaQualitativa
         * @methodReturn Object
         * @methodDescription Retorna a estrutura da nota qualitativa.
         */
        getEstruturaNotaQualitativa() { return { media: { id: null }, avaliacao: { id: null }, habilidadesAvaliadas: [] }; }
        /*
         * @method salvarAvaliacaoQuali
         * @methodReturn Object
         * @methodParams objeto|Object,label|String,loader|Boolean
         * @methodDescription Cria uma avaliação qualitativa nova.
         */
        salvarAvaliacaoQuali(objeto,label,loader) {
            if (label) { return this.rest.salvar(this.urlQuali, objeto, "Avaliação", "F", loader); }
            else { return this.rest.salvar(this.urlQuali, objeto, null, null, loader); }
        }
        /*
         * @method salvarQualitativa
         * @methodReturn Object
         * @methodParams objeto|Object,label|String,loader|Boolean
         * @methodDescription Cria uma nota qualitativa nova.
         */
        salvarQualitativa(objeto,label,loader) {
            if (label) { return this.rest.salvar(this.urlSalvarQuali, objeto, "Avaliação", "F", loader); }
            else { return this.rest.salvar(this.urlSalvarQuali, objeto, null, null, loader); }
        }

        //QUANTITATIVA

        /*
         * @method getNotaQuantitativa
         * @methodReturn Object
         * @methodParams id|Int,loader|Boolean
         * @methodDescription Busca uma nota quantitativa pelo id.
         */
        getNotaQuantitativa(id,loader){ return this.rest.um(this.urlSalvarQuanti,id,loader); }
        /*
         * @method getNotasQuantitativas
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Busca uma lista de notas quantitativas.
         */
        getNotasQuantitativas(opcoes,loader){ return this.rest.buscar(this.urlSalvarQuanti,opcoes,loader); }
        /*
         * @method getQuantitativa
         * @methodReturn Object
         * @methodParams id|Int,loader|Boolean
         * @methodDescription Busca uma avaliação quantitativa pelo id.
         */
        getQuantitativa(id,loader){ return this.rest.um(this.urlQuanti,id,loader); }
        /*
         * @method getQuantitativas
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Busca uma lista de avaliações quantitativas.
         */
        getQuantitativas(opcoes,loader){ return this.rest.buscar(this.urlQuanti,opcoes,loader); }
        /*
         * @method getEstruturaQuantitativa
         * @methodReturn Object
         * @methodDescription Retorna a estrutura da avaliação quantitativa.
         */
        getEstruturaQuantitativa() { return { media: { id: null }, avaliacao: { id: null }, valor: null }; }
        /*
         * @method getEstrutura
         * @methodReturn Object
         * @methodDescription Retorna a estrutura da avaliação quantitativa.
         */
        getEstrutura() { return { nome: null, disciplina: { id: null }, dataEntrega: null, tipo: { id: null }, media: null, habilidades: [], peso: 1 }; }
        /*
         * @method salvarAvaliacaoQuanti
         * @methodReturn Object
         * @methodParams objeto|Object,label|String,loader|Boolean
         * @methodDescription Cria uma avaliação quantitativa nova.
         */
        salvarAvaliacaoQuanti(objeto,label,loader) {
            if (label) { return this.rest.salvar(this.urlQuanti, objeto, "Avaliação", "F", loader); }
            else { return this.rest.salvar(this.urlQuanti, objeto, null, null, loader); }
        }
        /*
         * @method salvarQuantitativa
         * @methodReturn Object
         * @methodParams objeto|Object,label|String,loader|Boolean
         * @methodDescription Cria uma nota quantitativa nova.
         */
        salvarQuantitativa(objeto,label,loader) {
            if (label) { return this.rest.salvar(this.urlSalvarQuanti, objeto, "Avaliação", "F", loader);  }
            else { return this.rest.salvar(this.urlSalvarQuanti, objeto, null, null ,loader);  }
        }
        /*
         * @method atualizar
         * @methodReturn Object
         * @methodParams objeto|Object,label|String,loader|Boolean
         * @methodDescription Atualiza uma avaliação ou nota.
         */
        atualizar(objeto, label, loader) {
            if (label) { return this.rest.atualizar(objeto, "Avaliação", "F", loader);  }
            else { return this.rest.atualizar(objeto, null, null, loader);  }
        }
        /*
         * @method remover
         * @methodReturn Void
         * @methodParams objeto|Object,loader|Boolean
         * @methodDescription Remove uma avaliação ou nota.
         */
        remover(objeto,loader) { this.rest.remover(objeto, "Avaliação", "F", loader); }
        /*
         * @method getCursos
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Retorna os cursos cadastrados.
         */
        getCursos(opcoes,loader){ return this.cursoService.getAll(opcoes,loader); }
        /*
         * @method getEtapas
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Retorna as etapas cadastradas.
         */
        getEtapas(opcoes,loader){ return this.etapaService.getAll(opcoes,loader); }
        /*
         * @method getDisciplinas
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Retorna as disciplinas cadastradas.
         */
        getDisciplinas(opcoes,loader){ return this.disciplinaService.getAll(opcoes,loader); }
        /*
         * @method getTurmas
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Retorna as turmas cadastradas.
         */
        getTurmas(opcoes,loader){ return this.turmaService.getAll(opcoes,loader); }
        /*
         * @method getConceitos
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Retorna os conceitos cadastrados.
         */
        getConceitos(opcoes,loader){ return this.conceitoService.getAll(opcoes,loader); }
        /*
         * @method getHabilidades
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Retorna as habilidades cadastradas.
         */
        getHabilidades(opcoes,loader){ return this.habilidadeService.getAll(opcoes,loader); }
        /*
         * @method getTiposAvaliacao
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Retorna os tipos de avaliação cadastrados.
         */
        getTiposAvaliacao(opcoes,loader){ return this.tipoAvaliacaoService.getAll(opcoes,loader); }
        /*
         * @method getTiposAValiacaoQuali
         * @methodReturn Array
         * @methodParams opcoes|Array,loader|Boolean
         * @methodDescription Retorna os tipos de avaliacao qualitativa cadastradas.
         */
        getTiposAvaliacaoQuali() { return [{id:'DIAGNOSTICO',nome:'Diagnóstico'},{id:'PROCESSUAL',nome:'Processual'},{id:'FINAL',nome:'Final'}]; }
    };

    angular.module('AvaliacaoService',[]).service('AvaliacaoService',AvaliacaoService);
    AvaliacaoService.$inject = ["BaseService","CursoService","EtapaService","DisciplinaService","TipoAvaliacaoService","TurmaService","ConceitoService","HabilidadeService"];
})();