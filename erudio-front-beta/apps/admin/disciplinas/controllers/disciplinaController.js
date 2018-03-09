(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class DisciplinaController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, sharedService, cursoService, etapaService) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.sharedService = sharedService;
            this.cursoService = cursoService;
            this.etapaService = etapaService;
            this.permissaoLabel = "DISCIPLINAS";
            this.titulo = "Disciplina";
            this.linkModulo = "/#!/disciplinas/";
            this.disciplina = null;
            this.curso = {id: null};
            this.etapas = [];
            this.etapa = {id: null};
            this.pagina = 0;
            this.finalLista = false;
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome da Disciplina' }];
            this.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Disciplinas', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('disciplinas');
        }
        
        preparaBusca(){
            this.busca = '';
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/disciplinas/partials');
        }
        
        buscarCursos() {
            this.cursoService.getAll(null, true).then((cursos) => this.cursos = cursos);
        }
        
        buscarEtapas() {
            this.etapas = [];
            this.etapaService.getAll({curso: this.curso.id}, true).then((etapas) => this.etapas = etapas);
        }
        
        buscarDisciplinas(loader) {
            this.sharedService.setCursoEtapa(this.curso.id);
            this.sharedService.setEtapaDisciplina(this.etapa.id);
            this.service.getAll({page: this.pagina, etapa: this.etapa.id},loader).then((disciplinas) => {
                if (this.pagina === 0) { this.objetos = disciplinas; } else { 
                    if (disciplinas.length !== 0) { this.objetos = this.objetos.concat(disciplinas); } else { this.finalLista = true; this.pagina--; }
                }
            });
        }
        
        executarOpcao(event,opcao,objeto) {
            this.disciplina = objeto; this.modalExclusao(event);
        }
        
        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Disciplina", "Deseja remover esta disciplina?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.disciplina.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.disciplina, "Disciplina","f");
                    self.objetos.splice(index,1);
                }
            });
        }
        
        verificaBusca(query) { if (this.util.isVazio(query)) { this.buscarDisciplinas(); } else { this.executarBusca(query); } }
        
        executarBusca(query) {
            this.timeout.cancel(this.delayBusca); var self = this;
            this.delayBusca = this.timeout(() => {
                if (self.util.isVazio(query)) { query = ''; }
                let tamanho = query.length;
                if (tamanho > 3) {
                    self.service.getAll({ nome: query },true).then((disciplinas) => { self.objetos = disciplinas; });
                }
            },800);
        }
        
        paginar(){ this.pagina++; this.buscarDisciplinas(true); }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('disciplinas/assets/images/disciplinas.jpg');
                let etapaDisciplina = this.sharedService.getEtapaDisciplina();
                let cursoEtapa = this.sharedService.getCursoEtapa();
                if (!this.util.isVazio(etapaDisciplina)) { 
                    this.curso.id = cursoEtapa;
                    this.etapa.id = etapaDisciplina;
                    this.timeout(() => { this.buscarDisciplinas(); },500);
                }
                this.preparaLista();
                this.preparaBusca();
                this.buscarCursos();
                this.buscarEtapas();
                $(".fit-screen").scroll(function(){
                    let distancia = Math.floor(Number($(".conteudo").offset().top - $(document).height()));
                    let altura = Math.floor(Number($(".main-layout").height()));
                    let total = altura + distancia;
                    if (total === 0) { self.paginar(); }
                });
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    DisciplinaController.$inject = ["DisciplinaService","Util","$mdDialog","ErudioConfig","$timeout","Shared","CursoService","EtapaService"];
    angular.module('DisciplinaController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('DisciplinaController',DisciplinaController);
})();