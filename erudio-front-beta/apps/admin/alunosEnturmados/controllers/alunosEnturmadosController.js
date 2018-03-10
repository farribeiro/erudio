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
     * @ErudioDoc Alunos Enturmados Controller
     * @Module alunosEnturmados
     * @Controller AlunosEnturmadosController
     */
    class AlunosEnturmadosController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, $scope, unidadeService, etapaService, cursoService, cursoOfertadoService, turmaService, instituicaoService, etapaOfertadaService) {
            this.service = service; this.util = util; this.mdDialog = $mdDialog; this.aluno = null; this.unidadeService = unidadeService;
            this.erudioConfig = erudioConfig; this.timeout = $timeout; this.scope = $scope; this.cursoOfertadoService = cursoOfertadoService; this.etapaOfertadaService = etapaOfertadaService;
            this.scope.busca = {unidadeEnsino:null, cursoOfertado:null, etapa: null, turma: null}; this.objetos = []; this.isAdmin = false; this.itemBusca = '';
            this.etapaService = etapaService; this.cursoService = cursoService; this.cursoOfertadoService = cursoOfertadoService; this.etapas = []; this.turmas = [];
            this.itemBuscaPorUnidade = ''; this.scope.buscaPorUnidade = {unidadeEnsino:null}; this.itemBuscaPorUnidadeQuanti = '';
            this.scope.buscaQuanti = {instituicao:null, curso:null}; this.scope.buscaPorUnidadeQuanti = {unidadeEnsino:null};
            this.turmaService = turmaService; this.instituicaoService = instituicaoService;
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
            this.titulo = "Alunos Enturmados";
            /*
            * @attr linkModulo
            * @attrType int
            * @attrDescription Link raiz do módulo.
            * @attrExample
            */
            this.linkModulo = "/#!/alunos-enturmados/";
            this.iniciar();
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
            this.lista = this.util.getTemplateListaEspecifica('alunosEnturmados');
        }
        /*
         * @method preparaBusca
         * @methodReturn Void
         * @methodDescription Carrega os templates da busca.
         */
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/alunosEnturmados/partials');
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
        limparBusca() { this.scope.busca = {unidadeEnsino:null, cursoOfertado:null, etapa: null, turma: null}; this.itemBusca = ''; this.cursosOfertados = []; this.etapas = []; this.turmas = []; }
        /*
         * @method limparBuscaPorUnidade
         * @methodReturn Void
         * @methodDescription Limpa os campos de busca por unidade.
         * @methodExample this.limparBuscaPorUnidade();
         */
        limparBuscaPorUnidade() { this.itemBuscaPorUnidade = ''; this.scope.buscaPorUnidade = {unidadeEnsino:null}; }
        /*
         * @method limparBuscaQuanti
         * @methodReturn Void
         * @methodDescription Limpa os campos de busca quantitativa.
         * @methodExample this.limparBuscaQuanti();
         */
        limparBuscaQuanti() { this.scope.buscaQuanti = {instituicao:null, curso:null}; }
        /*
         * @method limparBuscaPorUnidadeQuanti
         * @methodReturn Void
         * @methodDescription Limpa os campos de busca quantitativa por unidade.
         * @methodExample this.limparBuscaPorUnidadeQuanti();
         */
        limparBuscaPorUnidadeQuanti() { this.itemBuscaPorUnidadeQuanti = ''; this.scope.buscaPorUnidadeQuanti = {unidadeEnsino:null}; }
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
         * @method buscarTurmas
         * @methodReturn Void
         * @methodParams curso|Int,etapa|Int
         * @methodDescription Busca a lista de turmas cadastradas.
         * @methodExample this.buscarTurmas();
         */
        buscarTurmas(curso,etapa) {
            this.turmaService.getAll({curso: curso, etapa: etapa, unidadeEnsino: this.scope.busca.unidadeEnsino.id},true).then((turmas) => {
                this.turmas = turmas;
            });
        }
        /*
         * @method buscarEtapas
         * @methodReturn Void
         * @methodParams curso|Int
         * @methodDescription Busca a lista de etapas cadastradas.
         * @methodExample this.buscarEtapas();
         */
        buscarEtapas(curso) {
            this.etapaOfertadaService.getAll({unidadeEnsino: this.scope.busca.unidadeEnsino.id, curso: curso},true).then((etapas) => {
                this.etapas = etapas;
            });
        }
        /*
         * @method buscarCursos
         * @methodReturn Void
         * @methodDescription Busca a lista de cursos cadastrados.
         * @methodExample this.buscarCursos();
         */
        buscarCursos() {
            this.cursoService.getAll(null,true).then((cursos) => {
                this.cursos = cursos;
            });
        }
        /*
         * @method buscarCursosOfertados
         * @methodReturn Void
         * @methodDescription Busca a lista de cursos ofertados cadastrados.
         * @methodExample this.buscarCursosOfertados();
         */
        buscarCursosOfertados() {
            this.cursoOfertadoService.getAll({unidadeEnsino: this.scope.busca.unidadeEnsino.id},true).then((cursos) => {
                this.cursosOfertados = cursos;
            });
        }
        /*
         * @method imprimir
         * @methodReturn Void
         * @methodDescription Imprime o relatório em nova janela.
         * @methodExample this.imprimir();
         */
        imprimir() {
            var url = this.service.getURL(this.scope.busca.turma);
            this.util.getPDF(url,"application/pdf",'_blank');
        }
        /*
         * @method imprimirNominalPorUnidade
         * @methodReturn Void
         * @methodDescription Imprime o relatório nominal por unidade em novajanela.
         * @methodExample this.imprimirNominalPorUnidade();
         */
        imprimirNominalPorUnidade() {
            var url = this.service.getURLNominalUnidade(this.scope.buscaPorUnidade.unidadeEnsino.id);
            this.util.getPDF(url,"application/pdf",'_blank');
        }
        /*
         * @method imprimirQuantiPorInstituicao
         * @methodReturn Void
         * @methodDescription Imprime o relatório quantitativo por instituição em nova janela.
         * @methodExample this.imprimirQuantiPorInstituicao();
         */
        imprimirQuantiPorInstituicao() {
            var url = this.service.getURLQuantiInstituicao(this.scope.buscaQuanti.instituicao, this.scope.buscaQuanti.curso);
            this.util.getPDF(url,"application/pdf",'_blank');
        }
        /*
         * @method imprimirQuantiPorUnidade
         * @methodReturn Void
         * @methodDescription Imprime o relatório quantitativo por unidade em nova janela.
         * @methodExample this.imprimirQuantiPorUnidade();
         */
        imprimirQuantiPorUnidade() {
            var url = this.service.getURLQuantiPorUnidade(this.scope.buscaPorUnidadeQuanti.unidadeEnsino.id);
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
                    this.scope.buscaPorUnidade.unidadeEnsino = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao;
                    this.scope.itemBuscaPorUnidadeQuanti.unidadeEnsino = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao;
                    this.buscarCursos();
                }
                this.util.mudarImagemToolbar('alunosEnturmados/assets/images/alunosEnturmados.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarInstituicoes();
                this.buscarCursos();
                this.timeout(() => {
                    $('.empty-state').hide(); $('botao-adicionar').hide();
                    this.util.aplicarMascaras();
                    this.scope.$watch("busca.unidadeEnsino", (query) => {
                        if (!this.util.isVazio(this.scope.busca.unidadeEnsino)) { this.buscarCursosOfertados(); }
                    });
                },500);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }

    AlunosEnturmadosController.$inject = ["AlunosEnturmadosService","Util","$mdDialog","ErudioConfig","$timeout","$scope","UnidadeService","EtapaService","CursoService","CursoOfertadoService","TurmaService","InstituicaoService","EtapaOfertadaService"];
    angular.module('AlunosEnturmadosController',['ngMaterial', 'util', 'erudioConfig']).controller('AlunosEnturmadosController',AlunosEnturmadosController);
})();