(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class QuadroHorarioController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, unidadeService, $scope) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.unidadeService = unidadeService;
            this.scope = $scope;
            this.permissaoLabel = "QUADROS_HORARIO";
            this.titulo = "Grades de Horário";
            this.linkModulo = "/#!/quadros-horario/";
            this.grade = null;
            this.nomeUnidade = null;
            this.unidade = null;
            this.pagina = 0;
            this.finalLista = false;
            this.itemBusca = '';
            this.buscaIcone = 'account_balance';
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome do Grade' }];
            this.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Grade de Horário', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('quadroHorarios');
        }
        
        preparaBusca(){
            this.busca = '';
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/quadroHorarios/partials');
        }

        ajustaHora() {
            this.objetos.forEach(function(objeto, i) {
                var inicioArr = objeto.inicio.split(":"); var terminoArr = objeto.termino.split(":");
                objeto.inicio = inicioArr[0]+":"+inicioArr[1]; objeto.termino = terminoArr[0]+":"+terminoArr[1];
            });
        }
        
        buscarQuadrosHorarios(loader) {
            if (!this.util.isVazio(this.unidade)) {
                this.service.getAll({page: this.pagina, unidadeEnsino: this.unidade.id},loader).then((grades) => {
                    if (this.pagina === 0) { this.objetos = grades; this.ajustaHora(); } else { 
                        if (grades.length !== 0) { this.objetos = this.objetos.concat(grades); this.ajustaHora(); } else { this.finalLista = true; this.pagina--; }
                    }
                });
            }
        }
        
        executarOpcao(event,opcao,objeto) {
            this.grade = objeto; this.modalExclusao(event);
        }
        
        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Grade de Horário", "Deseja remover esta grade de horário?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.grade.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.grade, "Grade de Horário","f");
                    self.objetos.splice(index,1);
                }
            });
        }

        limparBusca() { this.nomeUnidade = ''; this.buscaIcone = 'account_balance';  this.objetos = []; }

        paginar(){ this.pagina++; this.buscarQuadrosHorarios(true); }

        buscarUnidades (query) { 
            this.buscaIcone = 'clear';
            if (query.length > 2) {
                return this.unidadeService.getAll({nome: query},true);
            } else { return []; }
        }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('quadroHorarios/assets/images/quadroHorarios.jpg');
                this.preparaLista();
                this.preparaBusca();
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
    
    QuadroHorarioController.$inject = ["QuadroHorarioService","Util","$mdDialog","ErudioConfig","$timeout","UnidadeService","$scope"];
    angular.module('QuadroHorarioController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('QuadroHorarioController',QuadroHorarioController);
})();