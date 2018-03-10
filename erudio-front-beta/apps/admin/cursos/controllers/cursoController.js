(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class CursoController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, sharedService) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.sharedService = sharedService;
            this.permissaoLabel = "CURSOS";
            this.titulo = "Cursos";
            this.linkModulo = "/#!/cursos/";
            this.curso = null;
            this.pagina = 0;
            this.finalLista = false;
            this.buscaIcone = 'search';
            this.iniciar();
        }

        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }

        preparaLista(){
            this.subheaders = [{ label: 'Nome do Curso' }];
            this.opcoes = [{tooltip: 'Etapas', icone: 'chrome_reader_mode', opcao: 'etapas', validarEscrita: false, opcaoPublica: true},{tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Curso', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('cursos');
        }

        preparaBusca(){
            this.busca = '';
            this.buscaSimples = this.util.getTemplateBuscaSimples();
        }

        buscarCursos(loader) {
            this.service.getAll({page: this.pagina},loader).then((cursos) => {
                if (this.pagina === 0) { this.objetos = cursos; } else {
                    if (cursos.length !== 0) { this.objetos = this.objetos.concat(cursos); } else { this.finalLista = true; this.pagina--; }
                }
            });
        }

        executarOpcao(event,opcao,objeto) {
            this.curso = objeto;
            switch (opcao.opcao) {
                case 'remover': this.modalExclusao(event); break;
                case 'etapas': this.verEtapas(); break;
                default: return false; break;
            }
        }

        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Curso", "Deseja remover este curso?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.curso.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.curso, "Curso","m");
                    self.objetos.splice(index,1);
                }
            });
        }

        verificaBusca(query) { if (this.util.isVazio(query)) { this.buscarCursos(); this.buscaIcone = 'search'; } else { this.executarBusca(query); self.buscaIcone = 'clear'; } }

        limparBusca() { this.busca = ''; $('.busca-simples').val(''); this.buscaIcone = 'search'; this.buscarCursos(true); }

        executarBusca(query) {
            this.timeout.cancel(this.delayBusca); var self = this;
            this.delayBusca = this.timeout(() => {
                self.buscaIcone = 'clear';
                if (self.util.isVazio(query)) { query = ''; this.buscaIcone = 'search'; }
                let tamanho = query.length;
                if (tamanho > 3) {
                    self.service.getAll({ nome: query },true).then((cursos) => { self.objetos = cursos; });
                } else {
                    self.util.toast('A busca é ativada com no mínimo 4 caracteres.');
                }
            },800);
        }

        verEtapas() {
            this.sharedService.setCursoEtapa(this.curso.id);
            this.util.redirect(this.erudioConfig.dominio + '/#!/etapas');
        }

        paginar(){ this.pagina++; this.buscarCursos(true); }

        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('cursos/assets/images/cursos.jpg');
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

    CursoController.$inject = ["CursoService","Util","$mdDialog","ErudioConfig","$timeout","Shared"];
    angular.module('CursoController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('CursoController',CursoController);
})();