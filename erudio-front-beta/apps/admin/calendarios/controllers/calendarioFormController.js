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
     * @ErudioDoc Calendário Form Controller
     * @Module calendarios
     * @Controller CalendarioFormController
     */
    class CalendarioFormController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, unidadeService, sistemaAvaliacaoService){
            this.service = service; this.scope = $scope; this.util = util; this.routeParams = routeParams; this.erudioConfig = erudioConfig;
            this.unidadeService = unidadeService; this.sistemaAvaliacaoService = sistemaAvaliacaoService;
            this.timeout = $timeout; this.scope.calendario = null; this.unidade = null; this.itemBusca = ''; this.novo = true; this.hoje = new Date();
            this.dataInicio = new Date(); this.dataTermino = new Date(); this.iniciar();
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
            /*
            * @attr nomeForm
            * @attrType String
            * @attrDescription Id do formulário
            * @attrExample
            */
            this.nomeForm = "calendarioForm";
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
         * @method preparaForm
         * @methodReturn Void
         * @methodDescription Carrega os templates da página.
         */
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('calendarios','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[
                {label: 'Informações do Calendário', href: this.util.getInputBlockCustom('calendarios','inputs')}
            ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }
        /*
         * @method buscarSistemas
         * @methodReturn Void
         * @methodDescription Busca os sistemas de avaliação cadastrados.
         * @methodExample this.buscarSistemas();
         */
        buscarSistemas() { this.sistemaAvaliacaoService.getAll(null,true).then((sistemas) => this.sistemas = sistemas); }
        /*
         * @method buscarUnidades
         * @methodReturn Void
         * @methodDescription Busca as unidades cadastradas.
         * @methodExample this.buscarUnidades();
         */
        buscarUnidades() { this.unidadeService.getAll(null,true).then((unidades) => this.unidades = unidades); }
        /*
         * @method buscarBases
         * @methodReturn Void
         * @methodDescription Busca os calendários base cadastrados.
         * @methodExample this.buscarBases();
         */
        buscarBases() {
            var attrAtual = JSON.parse(sessionStorage.getItem('atribuicao-ativa'));
            this.service.getAll({instituicao: attrAtual.instituicao.id}).then((bases) => this.bases = bases);
        }
        /*
         * @method filtrar
         * @methodReturn Array
         * @methodParams nome|String
         * @methodDescription Filtra as unidades no autocomplete.
         */
        filtrar (query) { if (query.length > 2) { return this.unidadeService.getAll({nome: query}); } }
        /*
         * @method buscarCalendario
         * @methodReturn Void
         * @methodDescription Busca um calendário.
         * @methodExample this.buscarCalendario();
         */
        buscarCalendario() {
            var self = this;
            this.scope.calendario = this.service.getEstrutura();
            if (!this.util.isNovo(this.routeParams.id)) {
                this.novo = false;
                this.service.get(this.routeParams.id).then((calendario) => {
                    this.scope.calendario = calendario;
                    this.util.aplicarMascaras();
                });
            } else {
                this.novo = true;
                this.timeout(function(){ self.util.aplicarMascaras(); },300);
            }
        }
        /*
         * @method validaCampo
         * @methodReturn Void
         * @methodDescription Validador dos campos em tempo real.
         */
        validaCampo() { this.util.validaCampo(); }
        /*
         * @method validar
         * @methodReturn Void
         * @methodDescription Valida o formulário todo antes de salvar.
         */
        validar() { return this.util.validar(this.nomeForm); }
        /*
         * @method salvar
         * @methodReturn Void
         * @methodDescription Salva o calendário.
         */
        salvar() {
            if (this.validar()) {
                this.scope.calendario.instituicao.id = this.unidade.id;
                var calBase = "";
                this.bases.forEach((base) => {
                    if (base.id === parseInt(this.scope.calendario.calendarioBase.id)) { calBase = base.nome; }
                });
                this.scope.calendario.nome = this.unidade.nome + " - " + calBase;
                this.scope.calendario.dataInicio = this.dataInicio.getYear() + '-' + this.dataInicio.getMonth() + '-' + this.dataInicio.getDay();
                this.scope.calendario.dataTermino = this.dataTermino.getYear() + '-' + this.dataTermino.getMonth() + '-' + this.dataTermino.getDay();
                var resultado = null;
                if (this.util.isNovoObjeto(this.scope.calendario)) {
                    resultado = this.service.salvar(this.scope.calendario);
                } else {
                    resultado = this.service.atualizar(this.scope.calendario);
                }
                resultado.then(() => { this.util.redirect(this.erudioConfig.dominio + this.linkModulo); });
            }
        }
        /*
         * @method iniciar
         * @methodReturn Void
         * @methodDescription Iniciar o controller, verifica permissões e aplica gatilhos e listeners.
         */
        iniciar(){
            let permissao = this.verificarPermissao();
            if (permissao) {
                this.util.comPermissao();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.util.mudarImagemToolbar('calendarios/assets/images/calendarios.jpg');
                this.timeout(()=>{ this.validaCampo(); },500);
                $(".fit-screen").unbind('scroll');
                this.preparaForm();
                if (this.isAdmin) { this.buscarUnidades(); }
                this.buscarSistemas();
                this.buscarBases();
                this.buscarCalendario();
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }

    CalendarioFormController.$inject = ["CalendarioService","Util","ErudioConfig","$routeParams","$timeout","$scope","UnidadeService","SistemaAvaliacaoService"];
    angular.module('CalendarioFormController',['ngMaterial', 'util', 'erudioConfig']).controller('CalendarioFormController',CalendarioFormController);
})();