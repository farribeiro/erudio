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
     * @ErudioDoc Alunos Defasados Controller
     * @Module alunosDefasados
     * @Controller AlunosDefasadosController
     */
    class AlunosDefasadosController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, $scope, cursoOfertadoService, unidadeService) {
            this.service = service; this.util = util; this.mdDialog = $mdDialog; this.aluno = null; this.unidadeService = unidadeService;
            this.erudioConfig = erudioConfig; this.timeout = $timeout; this.scope = $scope; this.cursoOfertadoService = cursoOfertadoService;
            this.scope.busca = {unidadeEnsino:null, cursoOfertado:null}; this.objetos = []; this.isAdmin = false; this.itemBusca = '';
            /*
            * @attr permissaoLabel
            * @attrType String
            * @attrDescription Nome da permissão do módulo.
            * @attrExample 
            */
            this.permissaoLabel = "RELATORIOS";
            /*
            * @attr titulo
            * @attrType String
            * @attrDescription Título da página.
            * @attrExample 
            */
            this.titulo = "Alunos Defasados";
            /*
            * @attr linkModulo
            * @attrType int
            * @attrDescription Link raiz do módulo.
            * @attrExample 
            */
            this.linkModulo = "/#!/alunos-defasados/";
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
            this.subheaders = [{ label: 'Nome do Aluno' }];
            this.opcoes = [{tooltip: 'Imprimir', icone: 'print', opcao: 'imprimir', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Etapa', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('alunosDefasados');
        }
        /*
         * @method preparaBusca
         * @methodReturn Void
         * @methodDescription Carrega os templates da busca.
         */
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/alunosDefasados/partials');
        }
        /*
         * @method limparBusca
         * @methodReturn Void
         * @methodDescription Limpa os campos de busca.
         */
        limparBusca() { this.busca = {nome:null, dataNascimento:null}; this.itemBusca = ''; this.cursos = []; }
        /*
         * @method filtrar
         * @methodReturn Array
         * @methodParams nome|String
         * @methodDescription Filtra as unidades de ensino do autocomplete.
         */
        filtrar (query) { 
            if (query.length > 2) {
                return this.unidadeService.getAll({nome: query},true);
            } else { return []; }
        }
        /*
         * @method buscarCursos
         * @methodReturn Void
         * @methodDescription Busca a lista de cursos cadastrados.
         * @methodExample this.buscarCursos();
         */
        buscarCursos() {
            this.cursoOfertadoService.getAll({unidadeEnsino: this.scope.busca.unidadeEnsino.id},true).then((cursos) => {
                this.cursos = cursos;
            });
        }        
        /*
         * @method imprimir
         * @methodReturn Void
         * @methodDescription Imprime o relatório em nova janela.
         * @methodExample this.imprimir();
         */
        imprimir() {
            var url = this.service.getURL(this.scope.busca.cursoOfertado);
            this.util.getPDF(url,"application/pdf",'_blank');
        }
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
                if (this.util.isAdmin()) { this.isAdmin = true;} else { 
                    this.isAdmin = false;
                    this.scope.busca.unidadeEnsino = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao;
                    this.buscarCursos();
                }
                this.util.mudarImagemToolbar('alunosDefasados/assets/images/alunosDefasados.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.timeout(() => { 
                    $('.empty-state').hide(); $('botao-adicionar').hide();
                    this.util.aplicarMascaras();
                    this.scope.$watch("busca.unidadeEnsino", (query) => {
                        if (!this.util.isVazio(this.scope.busca.unidadeEnsino)) { this.buscarCursos(); }
                    }); 
                },500);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    AlunosDefasadosController.$inject = ["AlunosDefasadosService","Util","$mdDialog","ErudioConfig","$timeout","$scope","CursoOfertadoService","UnidadeService"];
    angular.module('AlunosDefasadosController',['ngMaterial', 'util', 'erudioConfig']).controller('AlunosDefasadosController',AlunosDefasadosController);
})();