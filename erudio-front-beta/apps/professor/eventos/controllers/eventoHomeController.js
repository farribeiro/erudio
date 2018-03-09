(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class EventoHomeController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $mdDialog, $mdMenu, turmaService){
            this.service = service;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.turmaService = turmaService;
            this.mdDialog = $mdDialog;
            this.mdMenu = $mdMenu;
            this.calendario = null;
            this.mesCalendario = [];
            this.semanaCalendario = [];
            this.permissaoLabel = "HOME_PROFESSOR";
            this.linkModulo = "/#!/calendarios/";
            this.nomeForm = "calendarioForm";
            this.mes = null;
            this.ano = null;
            this.disciplinaEscolhida = false;
            this.possuiEnturmacoes = parseInt(sessionStorage.getItem('possuiEnturmacoes'));
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        resetCalendario() { this.mesCalendario = []; this.semanaCalendario = []; }

        preparaCalendario(mes,ano) {
            var self = this;
            if (this.util.isVazio(mes) && this.util.isVazio(ano)) {
                var dateBase = new Date(); this.mes = dateBase.getMonth(); this.ano = dateBase.getFullYear();
                this.preparaCalendario(this.mes, this.ano);
            } else {
                this.diaS = 1; this.mes = mes; this.ano = ano; this.diaSemana = new Date(this.ano,this.mes,this.diaS).getDay();
                this.counterCalendario = this.diaSemana; this.gapInicio = this.diaSemana;
                this.diasMes = this.util.diasNoMes(this.mes,this.ano); this.semanaCalendario = new Array(this.gapInicio); this.nomeMes = this.util.nomeMes(this.mes);
                this.timeout(function(){ self.linkPaginacao(); },500); self.buscarEventos();
            }
        };

        buscarCalendario() {
            if (!this.util.isVazio(sessionStorage.getItem('turmaSelecionada'))) {
                this.disciplinaEscolhida = true;
                var self = this; var obj = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
                this.turmaService.get(obj.turma.id,true).then((turma) => {
                    this.calendario = turma.calendario; this.preparaCalendario();
                });
            }
        }

        abrirDia(dia) {
            this.dia = dia; var self = this;
            this.mdDialog.show({locals: {dia: {dia: dia, config: this.erudioConfig} }, controller: this.modalControl, templateUrl: this.erudioConfig.dominio+'/apps/professor/eventos/partials/dia.html', parent: angular.element(document.body), targetEvent: event, clickOutsideToClose: true});
        }
        
        modalControl($scope, dia) { 
            $scope.dia = dia.dia; $scope.config = dia.config;
            $scope.dia.eventos.forEach((evento) => {
                let ini = evento.inicio.split(":");
                evento.inicio = ini[0]+":"+ini[1];
                let termino = evento.termino.split(":");
                evento.termino = termino[0]+":"+termino[1];
            });
            $scope.abreMenu = function ($mdMenu, ev) { var origemEv = ev; $mdMenu.open(ev); };
        }
        
        buscarEventos() {
            this.service.getDiasPorMes(this.calendario,this.mes+1,true).then((dias) => {
                this.dias = dias;
                for (var i=0; i<this.diasMes; i++) {                        
                    this.counterCalendario++; this.semanaCalendario.push(this.dias[i]);
                    if (this.counterCalendario === 7) { this.mesCalendario.push(this.semanaCalendario); this.counterCalendario = 0; this.semanaCalendario = []; }
                    if (i === this.diasMes-1) {
                        var dataFinal = new Date(this.ano,this.mes,i+1); this.gapFinal = 6 - dataFinal.getDay();
                        for (var j=0; j<this.gapFinal; j++) { this.semanaCalendario.push(null); if (j === this.gapFinal-1) { this.mesCalendario.push(this.semanaCalendario); } }
                    }
                }
            });
        }
        
        linkPaginacao() {
            this.proximoMes = new Date(this.ano,this.mes,1); this.proximoMes.setMonth(this.proximoMes.getMonth()+1);
            this.mesAnterior = new Date(this.ano,this.mes,1); this.mesAnterior.setMonth(this.mesAnterior.getMonth()-1);
        }
        
        classeTipoDia(dia){ if (!this.util.isVazio(dia)) { if (dia.efetivo) { return 'calendario-dia-efetivo'; } else if (dia.letivo) { return 'calendario-dia-letivo'; } else { return 'calendario-dia-nao-letivo'; } } }
        
        paginaProxima(){ this.resetCalendario(); this.preparaCalendario(this.proximoMes.getMonth(),this.proximoMes.getFullYear()); }
        paginaAnterior(){ this.resetCalendario(); this.preparaCalendario(this.mesAnterior.getMonth(),this.mesAnterior.getFullYear()); }

        iniciar(){
            let permissao = this.verificarPermissao();
            if (permissao) {
                this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
                this.util.comPermissao();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.buscarCalendario();
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    EventoHomeController.$inject = ["CalendarioService","Util","ErudioConfig","$routeParams","$timeout","$mdDialog","$mdMenu","TurmaService"];
    angular.module('EventoHomeController',['ngMaterial', 'util', 'erudioConfig']).controller('EventoHomeController',EventoHomeController);
})();