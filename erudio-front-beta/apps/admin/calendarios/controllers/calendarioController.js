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
     * @ErudioDoc Calendário Controller
     * @Module calendarios
     * @Controller CalendarioController
     */
    class CalendarioController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout) {
            this.service = service; this.util = util; this.mdDialog = $mdDialog; this.erudioConfig = erudioConfig; this.timeout = $timeout;
            this.calendario = null; this.pagina = 0; this.finalLista = false; this.buscaIcone = 'search'; this.isAdmin = false;
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
            * @attrType int
            * @attrDescription Link raiz do módulo.
            * @attrExample
            */
            this.linkModulo = "/#!/calendarios/";
            this.iniciar();
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
         * @method preparaLista
         * @methodReturn Void
         * @methodDescription Carrega os templates da página.
         */
        preparaLista(){
            this.subheaders = [{ label: 'Nome do Calendário' }];
            this.opcoes = [{tooltip: 'Ver Calendário', icone: 'event', opcao: 'calendario', validarEscrita: true}, {tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Calendário', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('calendarios');
        }
        /*
         * @method preparaBusca
         * @methodReturn Void
         * @methodDescription Carrega os templates da busca.
         */
        preparaBusca(){
            this.busca = '';
            this.buscaSimples = this.util.getTemplateBuscaSimples();
        }
        /*
         * @method buscarCalendarios
         * @methodReturn Void
         * @methodDescription Busca a lista de calendários cadastrados.
         * @methodExample this.buscarCalendarios();
         */
        buscarCalendarios(loader) {
            var options = null;
            if (!this.isAdmin) {
                let unidade = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao.id;
                options = {page: this.pagina, instituicao: unidade};
            } else { options = {page: this.pagina}; };
            if (this.util.isAdmin()) { options = {page: this.pagina}; } else {
                let unidade = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao.id;
                options = {page: this.pagina, instituicao: unidade };
            }
            this.service.getAll(options,loader).then((calendarios) => {
                if (this.pagina === 0) { this.objetos = calendarios; } else {
                    if (calendarios.length !== 0) { this.objetos = this.objetos.concat(calendarios); } else { this.finalLista = true; this.pagina--; }
                }
            });
        }
        /*
         * @method executarOpcao
         * @methodReturn Void
         * @methodDescription Chama evento do botão.
         */
        executarOpcao(event,opcao,objeto) {
            this.calendario = objeto;
            switch (opcao.opcao) {
                case 'remover': this.modalExclusao(event); break;
                case 'calendario': this.verCalendario(); break;
                default: return false; break;
            }
        }
        /*
         * @method verCalendario
         * @methodReturn Void
         * @methodDescription Chama a tela de visualização de calendário.
         */
        verCalendario() { this.util.redirect(this.erudioConfig.dominio + this.linkModulo + 'view/' + this.calendario.id); }
        /*
         * @method modalExclusao
         * @methodReturn Void
         * @methodDescription Abre o modal para remover item.
         */
        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Calendário", "Deseja remover este calendário?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.calendario.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.calendario, "Calendário","m");
                    self.objetos.splice(index,1);
                }
            });
        }
        /*
         * @method verificaBusca
         * @methodReturn Void
         * @methodDescription Muda o ícone de busca.
         */
        verificaBusca(query) { if (this.util.isVazio(query)) { this.buscarCalendarios(); this.buscaIcone = 'search'; } else { this.executarBusca(query); this.buscaIcone = 'clear'; } }
        /*
         * @method limparBusca
         * @methodReturn Void
         * @methodDescription Limpa os campos de busca.
         * @methodExample this.limparBusca();
         */
        limparBusca() { this.busca = ''; $('.busca-simples').val(''); this.buscaIcone = 'search'; this.buscarCalendarios(true); }
        /*
         * @method executarBusca
         * @methodReturn Void
         * @methodDescription Executa a busca de itens.
         */
        executarBusca(query) {
            this.timeout.cancel(this.delayBusca); var self = this;
            this.delayBusca = this.timeout(() => {
                self.buscaIcone = 'clear';
                if (self.util.isVazio(query)) { query = ''; self.buscaIcone = 'search'; }
                let tamanho = query.length;
                if (tamanho > 3) {
                    let options = null;
                    if (!self.isAdmin) {
                        let unidade = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao.id;
                        options = {nome: query, instituicao: unidade};
                    } else { options = {nome: query}; };
                    self.service.getAll(options,true).then((calendarios) => { self.objetos = calendarios; });
                } else {
                    self.util.toast('A busca é ativada com no mínimo 4 caracteres.');
                }
            },800);
        }
        /*
         * @method paginar
         * @methodReturn Void
         * @methodDescription Carrega pŕoxima página de itens.
         */
        paginar(){ this.pagina++; this.buscarCalendarios(true); }
        /*
         * @method iniciar
         * @methodReturn Void
         * @methodDescription Iniciar o controller, verifica permissões e aplica gatilhos e listeners.
         */
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('calendarios/assets/images/calendarios.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarCalendarios();
                if (this.util.isAdmin()) { this.isAdmin = true; } else { this.isAdmin = false; }
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

    CalendarioController.$inject = ["CalendarioService","Util","$mdDialog","ErudioConfig","$timeout"];
    angular.module('CalendarioController',['ngMaterial', 'util', 'erudioConfig']).controller('CalendarioController',CalendarioController);
})();