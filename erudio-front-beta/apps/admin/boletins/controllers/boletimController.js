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
     * @ErudioDoc Boletim Controller
     * @Module boletins
     * @Controller BoletimController
     */
    class BoletimController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, cursoService, unidadeService, matriculaService, $scope, cursoOfertadoService, turmaService, enturmacaoService) {
            this.service = service; this.util = util; this.mdDialog = $mdDialog; this.erudioConfig = erudioConfig; this.timeout = $timeout;
            this.scope = $scope; this.scope.matricula = null; this.enturmacaoService = enturmacaoService; this.matriculaService = matriculaService;
            this.cursoService = cursoService; this.unidadeService = unidadeService; this.cursoOfertadoService = cursoOfertadoService; this.turmaService = turmaService;
            this.enturmacoes = []; this.enturmacao = null; this.scope.unidade = null; this.curso = {id: null}; this.etapa = {id: null};
            this.turma = {id: null}; this.encerrada = null; this.itemBusca = ''; this.itemBuscaMatricula = ''; this.isAdmin = false;
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
            this.titulo = "Boletim Escolar";
            /*
            * @attr linkModulo
            * @attrType int
            * @attrDescription Link raiz do módulo.
            * @attrExample 
            */
            this.linkModulo = "/#!/boletins/";
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
            this.lista = this.util.getTemplateListaEspecifica('boletins');
        }
        /*
         * @method preparaBusca
         * @methodReturn Void
         * @methodDescription Carrega os templates da busca.
         */
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/boletins/partials');
        }
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
         * @method filtrarMatricula
         * @methodReturn Array
         * @methodParams nome|String
         * @methodDescription Filtra os nomes de aluno no autocomplete.
         */
        filtrarMatricula (query) { 
            this.objetos = [];
            if (query.length > 2) {
                return this.matriculaService.getAll({aluno_nome: query},true);
            } else { return []; }
        }
        /*
         * @method buscarEnturmacoes
         * @methodReturn Void
         * @methodDescription Busca a lista de enturmações cadastradas.
         * @methodExample this.buscarEnturmacoes();
         */
        buscarEnturmacoes() {
            if (!this.util.isVazio(this.scope.matricula)) {
                this.timeout(() => {
                    if (this.isAdmin) {
                        this.enturmacaoService.getAll({matricula: this.scope.matricula.id, encerrado: 0},true).then((enturmacoes) => {
                            this.objetos = enturmacoes;
                        });
                    } else {
                        var ativa = JSON.parse(sessionStorage.getItem("atribuicao-ativa"));
                        this.enturmacaoService.getAll({'turma_unidadeEnsino': ativa.instituicao.id, matricula: this.scope.matricula.id, encerrado: 0},true).then((enturmacoes) => {
                            this.objetos = enturmacoes;
                        });
                    }
                },500);
            }
        }
        /*
         * @method buscarCursos
         * @methodReturn Void
         * @methodDescription Busca a lista de cursos cadastrados.
         * @methodExample this.buscarCursos();
         */
        buscarCursos() {
            this.cursoOfertadoService.getAll({unidadeEnsino: this.scope.unidade.id}, true).then((cursos) => this.cursos = cursos);
        }
        /*
         * @method buscarEtapas
         * @methodReturn Void
         * @methodParams loader|Boolean
         * @methodDescription Busca a lista de etapas cadastradas.
         * @methodExample this.buscarEtapas();
         */
        buscarEtapas(loader) {
            this.service.getAll({curso: this.curso.id},loader).then((etapas) => { this.etapas = etapas; });
        }
        /*
         * @method buscarTurmas
         * @methodReturn Void
         * @methodDescription Busca a lista de turmas cadastradas.
         * @methodExample this.buscarTurmas();
         */
        buscarTurmas() {
            this.turmaService.getAll({unidadeEnsino: this.scope.unidade.id, etapa: this.etapa.id, curso: this.curso.id, encerrado: this.encerrada}, true).then((turmas) => this.turmas = turmas);
        }
        /*
         * @method limparBusca
         * @methodReturn Void
         * @methodDescription Limpa os campos de busca.
         * @methodExample this.limparBusca();
         */
        limparBusca() {
            this.curso = {id: null}; this.etapa = {id: null}; this.turma = {id: null};
            this.scope.unidade = null; this.itemBusca = ''; this.scope.matricula = null;
        }
        /*
         * @method executarOpcao
         * @methodReturn Void
         * @methodDescription Chama evento do botão
         */
        executarOpcao(event,opcao,objeto) { this.enturmacao = objeto; this.imprimir(); }
        /*
         * @method imprimir
         * @methodReturn Void
         * @methodDescription Imprime o relatório em nova janela.
         * @methodExample this.imprimir();
         */
        imprimir(tipo) {
            if (tipo === 'TURMA'){
                var url = this.erudioConfig.urlServidor+'/report/boletins?turma='+this.turma.id;
                this.util.getPDF(url,'application/pdf','_blank');
            } else {
                var url = this.erudioConfig.urlServidor+'/report/boletins?enturmacao='+this.enturmacao.id;
                this.util.getPDF(url,'application/pdf','_blank');
            }
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
                    this.scope.unidade = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao;
                    this.buscarCursos();
                }
                this.util.mudarImagemToolbar('boletins/assets/images/boletins.png');
                this.preparaLista();
                this.preparaBusca();
                this.timeout(() => { this.scope.$watch("unidade", (query) => {
                    $('.empty-state').hide(); $('botao-adicionar').hide(); $('.tab_busca').parent().css('padding','0');
                    if (!this.util.isVazio(this.scope.unidade)) { this.buscarCursos(); }
                }); },500);
                this.timeout(() => { this.scope.$watch("matricula", (query) => {
                    this.buscarEnturmacoes();
                }); },500);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    BoletimController.$inject = ["EtapaService","Util","$mdDialog","ErudioConfig","$timeout","CursoService","UnidadeService","MatriculaService","$scope","CursoOfertadoService","TurmaService","EnturmacaoService"];
    angular.module('BoletimController',['ngMaterial', 'util', 'erudioConfig']).controller('BoletimController',BoletimController);
})();