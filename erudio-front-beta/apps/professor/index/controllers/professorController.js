(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class ProfessorController {
        constructor (util, $mdDialog, erudioConfig, $timeout, baseService) {
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.baseService = baseService;
            this.timeout = $timeout;
            this.permissaoLabel = "HOME_PROFESSOR";
            this.linkModulo = "/#!/";
            this.avaliacoesTemplate = erudioConfig.dominio+"/apps/professor/avaliacoes/partials/index.html";
            this.aulasUrl = erudioConfig.dominio+"/apps/professor/aulas/partials/index.html";
            this.chamadasUrl = erudioConfig.dominio+"/apps/professor/chamadas/partials/index.html";
            this.aulasTemplate = null;
            this.chamadasTemplate = null;
            this.buscaIcone = 'search';
            this.turmaSelecionada = null;
            this.buscarDisciplinas();
        }

        refresh() { sessionStorage.setItem('turmaSelecionada',JSON.stringify(this.turmaSelecionada)); this.timeout(() => {location.reload();}, 500); }

        buscarDisciplinas() {
            this.isQualitativa = false;
            this.baseService.buscar('professor/disciplinas',null,false).then((disciplinas) => {
                this.disciplinasLecionadas = disciplinas;
                disciplinas.forEach((disciplina) => {
                    var obj = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
                    if (disciplina.id === parseInt(obj.id)){
                        this.turmaSelecionada = disciplina;
                    }
                });
                if (disciplinas.length === 1) { this.turmaSelecionada = disciplinas[0]; }
            });
        }

        inserirConteudo(tab) {
            switch (tab) {
                case 'aulas':
                    if (this.util.isVazio(this.aulasTemplate)) { this.aulasTemplate = this.aulasUrl; }
                break;
                case 'chamadas':
                    if (this.util.isVazio(this.chamadasTemplate)) { this.chamadasTemplate = this.chamadasUrl; }
                break;
            }
        }
    }
    
    ProfessorController.$inject = ["Util","$mdDialog","ErudioConfig","$timeout","BaseService"];
    angular.module('ProfessorController',['ngMaterial', 'util', 'erudioConfig']).controller('ProfessorController',ProfessorController);
})();