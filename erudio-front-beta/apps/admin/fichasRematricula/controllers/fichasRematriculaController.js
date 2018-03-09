(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class FichaRematriculaController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, cursoService, unidadeService, $scope, cursoOfertadoService, turmaService) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.scope = $scope;
            this.scope.unidade = null;
            this.cursoService = cursoService;
            this.unidadeService = unidadeService;
            this.cursoOfertadoService = cursoOfertadoService;
            this.turmaService = turmaService;
            this.permissaoLabel = "RELATORIOS";
            this.titulo = "Ficha de Rematrícula";
            this.linkModulo = "/#!/rematricula/";
            this.curso = {id: null};
            this.etapa = {id: null};
            this.turma = {id: null};
            this.itemBusca = '';
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
            this.lista = this.util.getTemplateListaEspecifica('fichasRematricula');
        }
        
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/fichasRematricula/partials');
        }

        filtrar (query) { 
            if (query.length > 2) {
                return this.unidadeService.getAll({nome: query},true);
            } else { return []; }
        }
        
        buscarCursos() {
            this.cursoOfertadoService.getAll({unidadeEnsino: this.scope.unidade.id}, true).then((cursos) => this.cursos = cursos);
        }
        
        buscarEtapas(loader) {
            this.service.getAll({curso: this.curso.id},loader).then((etapas) => { this.etapas = etapas; });
        }

        buscarTurmas() {
            this.turmaService.getAll({unidadeEnsino: this.scope.unidade.id, etapa: this.etapa.id, curso: this.curso.id}, true).then((turmas) => {
                this.turmas = turmas
                if (turmas.length === 0) { this.util.toast("Esta etapa não possui turmas."); }
            });
        }

        limparBusca() {
            this.curso = {id: null}; this.etapa = {id: null}; this.turma = {id: null};
            this.scope.unidade = null; this.itemBusca = '';
        }

        imprimir() {
            var url = this.erudioConfig.urlServidor+'/report/ficha-rematricula?turma='+this.turma.id;
            this.util.getPDF(url,"application/pdf",'_blank');
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
                this.util.mudarImagemToolbar('fichasRematricula/assets/images/fichasRematricula.png');
                this.preparaLista();
                this.preparaBusca();
                this.timeout(() => { this.scope.$watch("unidade", (query) => {
                    $('.empty-state').hide(); $('botao-adicionar').hide(); $('.tab_busca').parent().css('padding','0');
                    if (!this.util.isVazio(this.scope.unidade)) { this.buscarCursos(); }
                }); },500);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    FichaRematriculaController.$inject = ["EtapaService","Util","$mdDialog","ErudioConfig","$timeout","CursoService","UnidadeService","$scope","CursoOfertadoService","TurmaService"];
    angular.module('FichaRematriculaController',['ngMaterial', 'util', 'erudioConfig']).controller('FichaRematriculaController',FichaRematriculaController);
})();