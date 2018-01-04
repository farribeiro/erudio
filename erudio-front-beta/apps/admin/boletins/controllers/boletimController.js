(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class BoletimController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, cursoService, unidadeService, matriculaService, $scope, cursoOfertadoService, turmaService, enturmacaoService) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.scope = $scope;
            this.scope.matricula = null;
            this.enturmacaoService = enturmacaoService;
            this.matriculaService = matriculaService;
            this.cursoService = cursoService;
            this.unidadeService = unidadeService;
            this.cursoOfertadoService = cursoOfertadoService;
            this.turmaService = turmaService;
            this.permissaoLabel = "RELATORIOS";
            this.titulo = "Boletim Escolar";
            this.linkModulo = "/#!/boletins/";
            this.enturmacoes = [];
            this.enturmacao = null;
            this.scope.unidade = null;
            this.curso = {id: null};
            this.etapa = {id: null};
            this.turma = {id: null};
            this.encerrada = null;
            this.itemBusca = '';
            this.itemBuscaMatricula = '';
            this.isAdmin = false;
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome do Aluno' }];
            this.opcoes = [{tooltip: 'Imprimir', icone: 'print', opcao: 'imprimir', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Etapa', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('boletins');
        }
        
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/boletins/partials');
        }

        filtrar (query) { 
            if (query.length > 2) {
                return this.unidadeService.getAll({nome: query},true);
            } else { return []; }
        }

        filtrarMatricula (query) { 
            this.objetos = [];
            if (query.length > 2) {
                return this.matriculaService.getAll({aluno_nome: query},true);
            } else { return []; }
        }

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
        
        buscarCursos() {
            this.cursoOfertadoService.getAll({unidadeEnsino: this.scope.unidade.id}, true).then((cursos) => this.cursos = cursos);
        }
        
        buscarEtapas(loader) {
            this.service.getAll({curso: this.curso.id},loader).then((etapas) => { this.etapas = etapas; });
        }

        buscarTurmas() {
            this.turmaService.getAll({unidadeEnsino: this.scope.unidade.id, etapa: this.etapa.id, curso: this.curso.id, encerrado: this.encerrada}, true).then((turmas) => this.turmas = turmas);
        }

        limparBusca() {
            this.curso = {id: null}; this.etapa = {id: null}; this.turma = {id: null};
            this.scope.unidade = null; this.itemBusca = ''; this.scope.matricula = null;
        }

        executarOpcao(event,opcao,objeto) { this.enturmacao = objeto; this.imprimir(); }

        imprimir(tipo) {
            if (tipo === 'TURMA'){
                var url = this.erudioConfig.urlServidor+'/report/boletins?turma='+this.turma.id;
                this.util.getPDF(url,'application/pdf','_blank');
            } else {
                var url = this.erudioConfig.urlServidor+'/report/boletins?enturmacao='+this.enturmacao.id;
                this.util.getPDF(url,'application/pdf','_blank');
            }
        }
        
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