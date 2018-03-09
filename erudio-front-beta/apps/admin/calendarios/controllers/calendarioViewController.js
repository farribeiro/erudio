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
     * @ErudioDoc Calendário View Controller
     * @Module calendarios
     * @Controller CalendarioViewController
     */
    class CalendarioViewController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $mdDialog, $mdMenu){
            this.service = service; this.util = util; this.routeParams = routeParams; this.erudioConfig = erudioConfig; this.timeout = $timeout;
            this.mdDialog = $mdDialog; this.mdMenu = $mdMenu; this.calendario = null; this.mesCalendario = []; this.semanaCalendario = []; this.nomeForm = "calendarioForm";
            this.mes = null; this.ano = null; this.iniciar();
            /*
            * @attr permissaoLabel
            * @attrType String
            * @attrDescription Nome da permissão do módulo.
            * @attrExample 
            */
            this.permissaoLabel = "CALENDARIO";
            /*
            * @attr titulo
            * @attrType String
            * @attrDescription Título da página.
            * @attrExample 
            */
            this.titulo = "Calendários";
            /*
            * @attr linkModulo
            * @attrType Int
            * @attrDescription Link raiz do módulo.
            * @attrExample 
            */
            this.linkModulo = "/#!/calendarios/";
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
         * @method resetCalendario
         * @methodReturn Void
         * @methodDescription Reinicia o template de calendário.
         */
        resetCalendario() { this.mesCalendario = []; this.semanaCalendario = []; }
        /*
         * @method preparaCalendario
         * @methodReturn Void
         * @methodParams mes|Int,ano|Int
         * @methodDescription Carrega os templates da página.
         */
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
        /*
         * @method buscarCalendario
         * @methodReturn Void
         * @methodDescription Busca o calendário.
         */
        buscarCalendario() {
            var self = this;
            this.service.get(this.routeParams.id).then((calendario) => { this.calendario = calendario; this.preparaCalendario(); });
        }
        /*
         * @method abrirDia
         * @methodReturn Void
         * @methodDescription Abrir modal com o dia selecionado.
         */
        abrirDia(dia) {
            this.dia = dia; var self = this;
            this.mdDialog.show({locals: {dia: {dia: dia, config: this.erudioConfig} }, controller: this.modalControl, templateUrl: this.erudioConfig.dominio+'/apps/admin/calendarios/partials/dia.html', parent: angular.element(document.body), targetEvent: event, clickOutsideToClose: true});
            this.timeout(function(){
                $('.dia-item').click(function(){
                    var val = $(this).attr('data-value');
                    let obj = null;
                    switch (val) {
                        case 'E': 
                            self.dia.letivo = true; self.dia.efetivo = true; obj = self.dia.plain();
                            self.service.salvarDias({dias:[obj]},self.calendario);
                            break;
                        case 'L': 
                            self.dia.letivo = true; self.dia.efetivo = false; obj = self.dia.plain();
                            self.service.salvarDias({dias:[obj]},self.calendario);
                            break;
                        default: 
                            self.dia.letivo = false; self.dia.efetivo = false; obj = self.dia.plain();
                            self.service.salvarDias({dias:[obj]},self.calendario);
                            break;
                    }
                });
            },500);
        }
        /*
         * @method modalControl
         * @methodReturn Void
         * @methodDescription Controla modal do dia.
         */
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
        /*
         * @method buscarEventos
         * @methodReturn Void
         * @methodDescription Buscar eventos cadastrados no dia.
         */
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
        /*
         * @method linkPaginacao
         * @methodReturn Void
         * @methodDescription Gera os links de paginação do calendário.
         */
        linkPaginacao() {
            this.proximoMes = new Date(this.ano,this.mes,1); this.proximoMes.setMonth(this.proximoMes.getMonth()+1);
            this.mesAnterior = new Date(this.ano,this.mes,1); this.mesAnterior.setMonth(this.mesAnterior.getMonth()-1);
        }
        /*
         * @method classeTipoDia
         * @methodReturn Void
         * @methodDescription Gerencia as classes css do dia no calendário.
         */
        classeTipoDia(dia){ if (!this.util.isVazio(dia)) { if (dia.efetivo) { return 'calendario-dia-efetivo'; } else if (dia.letivo) { return 'calendario-dia-letivo'; } else { return 'calendario-dia-nao-letivo'; } } }
        /*
         * @method paginaProxima
         * @methodReturn Void
         * @methodDescription Carrega pŕoxima página do calendário.
         */
        paginaProxima(){ this.resetCalendario(); this.preparaCalendario(this.proximoMes.getMonth(),this.proximoMes.getFullYear()); }
        /*
         * @method paginaAnterior
         * @methodReturn Void
         * @methodDescription Carrega página anterior do calendário.
         */
        paginaAnterior(){ this.resetCalendario(); this.preparaCalendario(this.mesAnterior.getMonth(),this.mesAnterior.getFullYear()); }
        /*
         * @method iniciar
         * @methodReturn Void
         * @methodDescription Iniciar o controller, verifica permissões e aplica gatilhos e listeners.
         */
        iniciar(){
            let permissao = this.verificarPermissao();
            if (permissao) {
                this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
                this.util.comPermissao();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.util.mudarImagemToolbar('calendarios/assets/images/calendarios.jpg');
                this.buscarCalendario();
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    CalendarioViewController.$inject = ["CalendarioService","Util","ErudioConfig","$routeParams","$timeout","$mdDialog","$mdMenu"];
    angular.module('CalendarioViewController',['ngMaterial', 'util', 'erudioConfig']).controller('CalendarioViewController',CalendarioViewController);
})();