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
     * @ErudioDoc Alunos ANEE Controller
     * @Module alunosANEE
     * @Controller AlunosANEEController
     */
    class AlunosANEEController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, $scope, unidadeService, instituicaoService) {
            this.service = service; this.util = util; this.mdDialog = $mdDialog; this.aluno = null; this.unidadeService = unidadeService;
            this.erudioConfig = erudioConfig; this.timeout = $timeout; this.scope = $scope; this.instituicaoService = instituicaoService;
            this.scope.busca = {unidadeEnsino:null, instituicao:null}; this.isAdmin = false;
            /*
            * @attr permissaoLabel
            * @attrType String
            * @attrDescription Nome da permissão do módulo.
            * @attrExample
            */
            this.permissaoLabel = "RELATORIOS";
            /*
            * @attr titulo
            * @attrType String
            * @attrDescription Título da página.
            * @attrExample
            */
            this.titulo = "Alunos ANEE";
            /*
            * @attr linkModulo
            * @attrType int
            * @attrDescription Link raiz do módulo.
            * @attrExample
            */
            this.linkModulo = "/#!/alunos-anee/";
            this.itemBusca = ''; this.iniciar();
        }
        /*
         * @method verificarPermissao
         * @methodReturn Void
         * @methodDescription Verifica permissão de acesso ao módulo
         */
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        /*
         * @method verificaEscrita
         * @methodReturn Void
         * @methodDescription Verifica permissão de escrita ao módulo
         */
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        /*
         * @method validarEscrita
         * @methodReturn Void
         * @methodDescription Verifica a permissão de visualizar os itens de interface que dependem da escrita.
         */
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        /*
         * @method preparaLista
         * @methodReturn Void
         * @methodDescription Carrega os templates da página.
         */
        preparaLista(){
            this.subheaders = [{ label: 'Nome do Aluno' }];
            this.opcoes = [{tooltip: 'Imprimir', icone: 'print', opcao: 'imprimir', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Etapa', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('alunosANEE');
        }
        /*
         * @method preparaBusca
         * @methodReturn Void
         * @methodDescription Carrega os templates da busca.
         */
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/alunosANEE/partials');
        }
        /*
         * @method mostrarImpressao
         * @methodReturn Void
         * @methodDescription Verifica se é necessário mostrar o ícone de impressão.
         */
        mostrarImpressao() { if (!this.util.isVazio(this.turmas)) { return true; } else { return false; } }
        /*
         * @method limparBusca
         * @methodReturn Void
         * @methodDescription Limpa os campos de busca.
         * @methodExample this.limparBusca();
         */
        limparBusca() { this.scope.busca = {unidadeEnsino:null, instituicao:null}; }
        /*
         * @method filtrar
         * @methodReturn Array
         * @methodParams nome|String
         * @methodDescription Filtra as unidades de ensino do autocomplete.
         */
        filtrar (query) {
            if (query.length > 2) {
                return this.unidadeService.getAll({nome: query},true);
            } else { return []; }
        }
        /*
         * @method buscarInstituicoes
         * @methodReturn Void
         * @methodDescription Busca a lista de instituições cadastradas.
         * @methodExample this.buscarInstituicoes();
         */
        buscarInstituicoes() {
            this.instituicaoService.getAll(null,true).then((instituicoes) => {
                this.instituicoes = instituicoes;
            });
        }
        /*
         * @method imprimir
         * @methodReturn Void
         * @methodDescription Imprime o relatório em nova janela.
         * @methodExample this.imprimir();
         */
        imprimir() {
            var url = this.service.getURLPorInstituicao(this.scope.busca.instituicao);
            this.util.getPDF(url,"application/pdf",'_blank');
        }
        /*
         * @method imprimirNominalPorUnidade
         * @methodReturn Void
         * @methodDescription Imprime o relatório em nova janela.
         * @methodExample this.imprimirNominalPorUnidade();
         */
        imprimirNominalPorUnidade() {
            var url = this.service.getURL(this.scope.busca.unidadeEnsino.id);
            this.util.getPDF(url,"application/pdf",'_blank');
        }
        /*
         * @method iniciar
         * @methodReturn Void
         * @methodDescription Iniciar o controller, verifica permissões e aplica gatilhos e listeners.
         */
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                if (this.util.isAdmin()) { this.isAdmin = true;} else {
                    this.isAdmin = false;
                    this.scope.busca.unidadeEnsino = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao;
                }
                this.util.mudarImagemToolbar('alunosANEE/assets/images/alunosANEE.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarInstituicoes();
                this.timeout(() => {
                    $('.empty-state').hide(); $('botao-adicionar').hide();
                    this.util.aplicarMascaras();
                },500);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }

    AlunosANEEController.$inject = ["AlunoANEEService","Util","$mdDialog","ErudioConfig","$timeout","$scope","UnidadeService","InstituicaoService"];
    angular.module('AlunosANEEController',['ngMaterial', 'util', 'erudioConfig']).controller('AlunosANEEController',AlunosANEEController);
})();