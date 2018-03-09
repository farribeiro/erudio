(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class EtapaController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, sharedService, cursoService) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.sharedService = sharedService;
            this.cursoService = cursoService;
            this.permissaoLabel = "ETAPAS";
            this.titulo = "Etapas";
            this.linkModulo = "/#!/etapas/";
            this.etapa = null;
            this.curso = {id: null};
            this.pagina = 0;
            this.finalLista = false;
            this.buscaIcone = 'search';
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome da Etapa' }];
            this.opcoes = [{tooltip: 'Disciplinas', icone: 'import_contacts', opcao: 'disciplinas' },{tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Etapa', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('etapas');
        }
        
        preparaBusca(){
            this.busca = '';
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/etapas/partials');
        }
        
        buscarCursos() {
            this.cursoService.getAll(null, true).then((cursos) => this.cursos = cursos);
        }
        
        buscarEtapas(loader) {
            this.sharedService.setCursoEtapa(this.curso.id);
            this.service.getAll({page: this.pagina, curso: this.curso.id},loader).then((etapas) => {
                if (this.pagina === 0) { this.objetos = etapas; } else { 
                    if (etapas.length !== 0) { this.objetos = this.objetos.concat(etapas); } else { this.finalLista = true; this.pagina--; }
                }
            });
        }
        
        executarOpcao(event,opcao,objeto) {
            this.etapa = objeto;
            switch (opcao.opcao) {
                case 'remover': this.modalExclusao(event); break;
                case 'disciplinas': this.verDisciplinas(); break;
                default: return false; break;
            }
        }
        
        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Etapa", "Deseja remover esta etapa?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.etapa.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.etapa, "Etapa","f");
                    self.objetos.splice(index,1);
                }
            });
        }
        
        verificaBusca(query) { if (this.util.isVazio(query)) { this.buscarEtapas(); this.buscaIcone = 'search'; } else { this.executarBusca(query); self.buscaIcone = 'clear'; } }
        
        limparBusca() { this.busca = ''; $('.busca-simples').val(''); this.buscaIcone = 'search'; this.buscarEtapas(true); }

        executarBusca(query) {
            this.timeout.cancel(this.delayBusca); var self = this;
            this.delayBusca = this.timeout(() => {
                self.buscaIcone = 'clear';
                if (self.util.isVazio(query)) { query = ''; this.buscaIcone = 'search'; }
                let tamanho = query.length;
                if (tamanho > 3) {
                    self.service.getAll({ nome: query },true).then((etapas) => { self.objetos = etapas; });
                } else {
                    self.util.toast('A busca é ativada com no mínimo 4 caracteres.');
                }
            },800);
        }
        
        verDisciplinas() {
            this.sharedService.setCursoEtapa(this.curso.id);
            this.sharedService.setEtapaDisciplina(this.etapa.id);
            this.util.redirect(this.erudioConfig.dominio + '/#!/disciplinas');
        }
        
        paginar(){ this.pagina++; this.buscarEtapas(true); }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('etapas/assets/images/etapas.jpg');
                let cursoEtapa = this.sharedService.getCursoEtapa();
                if (!this.util.isVazio(cursoEtapa)) { this.curso.id = cursoEtapa; this.buscarEtapas(); }
                this.preparaLista();
                this.preparaBusca();
                this.buscarCursos();
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
    
    EtapaController.$inject = ["EtapaService","Util","$mdDialog","ErudioConfig","$timeout","Shared","CursoService"];
    angular.module('EtapaController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('EtapaController',EtapaController);
})();