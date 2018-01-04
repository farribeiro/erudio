(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class ProfessorController {
        constructor (util, $mdDialog, erudioConfig, $timeout, baseService, enturmacaoService, shared) {
            this.util = util; this.shared = shared;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.baseService = baseService;
            this.enturmacaoService = enturmacaoService;
            this.timeout = $timeout;
            this.permissaoLabel = "HOME_PROFESSOR";
            this.linkModulo = "/#!/";
            this.avaliacoesUrl = erudioConfig.dominio+"/apps/professor/avaliacoes/partials/index.html";
            this.mediasUrl = erudioConfig.dominio+"/apps/professor/medias/partials/index.html";
            this.eventosUrl = erudioConfig.dominio+"/apps/professor/eventos/partials/index.html";
            this.aulasTemplate = erudioConfig.dominio+"/apps/professor/aulas/partials/index.html";
            this.eventosTemplate = null;
            this.mediasTemplate = null;
            this.avaliacoesTemplate = null;
            this.buscaIcone = 'search';
            this.turmaSelecionada = null;
            this.possuiEnturmacoes = null;
            this.buscarDisciplinas();
        }

        refresh() { 
            sessionStorage.setItem('turmaSelecionada',JSON.stringify(this.turmaSelecionada)); 
            this.enturmacaoService.getAll({encerrado: 0, turma: this.turmaSelecionada.turma.id},true).then((enturmacoes) => {
                if (enturmacoes.length > 0) { sessionStorage.setItem('possuiEnturmacoes',1); this.timeout(() => {location.reload();}, 500); } 
                else { sessionStorage.removeItem('possuiEnturmacoes'); sessionStorage.setItem('possuiEnturmacoes',0); this.timeout(() => {location.reload();}, 500); }
            });
        }

        buscarDisciplinas() {
            this.isQualitativa = false;
            this.baseService.buscar('professor/disciplinas',null,false).then((disciplinas) => {
                this.disciplinasLecionadas = disciplinas;
                disciplinas.forEach((disciplina) => {
                    var obj = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
                    if (!this.util.isVazio(obj) && disciplina.id === parseInt(obj.id)){
                        this.turmaSelecionada = disciplina;
                    }
                });
                if (disciplinas.length === 1) { this.turmaSelecionada = disciplinas[0]; }
            });
        }

        inserirConteudo(tab) {
            switch (tab) {
                case 'aulas':
                    this.shared.setAbaHome('aulas'); $('.btn-home').hide();
                break;
                case 'avaliacoes':
                    this.shared.setAbaHome('calendarios'); $('.btn-home').hide();
                    if (this.util.isVazio(this.avaliacoesTemplate)) { this.avaliacoesTemplate = this.avaliacoesUrl; }
                break;
                case 'medias':
                    this.shared.setAbaHome('medias'); $('.btn-home').hide();
                    if (this.util.isVazio(this.mediasTemplate)) { this.mediasTemplate = this.mediasUrl; }
                break;
                case 'eventos':
                    this.shared.setAbaHome('eventos'); $('.btn-home').hide();
                    if (this.util.isVazio(this.eventosTemplate)) { this.eventosTemplate = this.eventosUrl; }
                break;
            }
        }
    }
    
    ProfessorController.$inject = ["Util","$mdDialog","ErudioConfig","$timeout","BaseService","EnturmacaoService","Shared"];
    angular.module('ProfessorController',['ngMaterial', 'util', 'erudioConfig']).controller('ProfessorController',ProfessorController);
})();