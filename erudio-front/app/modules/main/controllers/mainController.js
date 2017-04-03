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

(function () {
    var mainModule = angular.module('mainModule', ['servidorModule', 'elementosModule', 'mainDirectives', 'instituicaoModule', 'instituicaoFormModule', 'cursoModule', 'cursoFormModule', 'etapaModule', 'etapaFormModule','regimeModule', 'moduloModule', 'turnoModule',
        'unidadeModule', 'tipoModule', 'turmaModule', 'turmaPresencasModule', 'turmaQuadroHorarioModule', 'turmaFormModule', 'turmaAlunosModule', 'turmaAlunosEnturmarModule', 'turmaProfessoresModule', 'turmaAlunoPresencaModule', 'calendarioModule', 'eventoModule', 'quadroHorarioModule', 'modeloQuadroHorarioModule', 'dateTimeModule', 'matriculaModule',
        'matriculaFormModule', 'matriculaEtapaModule', 'matriculaEnturmacaoModule', 'pessoaModule', 'funcionarioModule', 'cargosModule', 'diarioFrequenciasModule', 'tiposAvaliacoesModule', 'habilidadeModule', 'avaliacaoModule', 'disciplinaModule', 'makePdfModule',
        'movimentacoesModule','usuarioModule','permissaoModule','vagaModule','historicoEscolarModule','boletimEscolarModule', 'espelhoNotasModule','registroMatriculasModule', 'alunosDefasadosModule',
        'grupoPermissaoModule', 'turmaMistaModule','homeModule','relatorioModule','diarioNotasModule','relatoriosDefasadosModule','testeModule','frequenciaModule']);

    mainModule.controller('MainController', ['$scope', '$timeout', 'Servidor', 'dateTime', 'AvaliacaoService', 'PessoaService', 'FuncionarioService', '$templateCache', function ($scope, $timeout, Servidor, dateTime, AvaliacaoService, PessoaService, FuncionarioService, $templateCache) {
        $templateCache.removeAll();
        
        this.tab = 'home';
        this.tabAtual = "";
        this.leftDropdown = '';
        this.profile = sessionStorage.getItem('profile');
        $scope.avatar = sessionStorage.getItem('avatar');
        this.baseUpload = sessionStorage.getItem('baseUploadUrl');
        $scope.inicializando = false;
        $scope.AvaliacaoService = AvaliacaoService;
        $scope.PessoaService = PessoaService;
        $scope.FuncionarioService = FuncionarioService;
        $scope.isHome = true;
        //CARREGA MÓDULO
        this.carregarConteudo = function (modulo, options) { return "app/modules/" + modulo + "/partials/" + modulo + options + ".html"; };

        //SELECIONA MÓDULO
        this.selecionar = function (modulo, options) {
            if (options === undefined) { options = ""; }
            if (modulo !== this.tab) {
                this.tab = modulo;
                sessionStorage.setItem('module', this.tab);
                sessionStorage.setItem('moduleOptions', options);
                this.tabAtual = this.carregarConteudo(modulo, options);
                Servidor.removeTooltipp();
                $scope.inicializar(); $scope.inicializando = true;
                //url amigavel
                if (modulo !== 'main') {
                    if (!sessionStorage.getItem('alocacao') && !Servidor.verificarPermissoes("SUPER_ADMIN")) { return Servidor.customToast('Selecione uma alocação.'); }
                }
            }
        };

        //SAI DO SISTEMA
        this.logout = function () { sessionStorage.clear(); window.location = 'http://educacao.itajai.sc.gov.br/'; };

        //ABRE MODAL DE FOTO
        this.modalAvatar = function () { $('#modalAvatar').modal(); $scope.labelUpload = 'INSERIR FOTO'; };

        //EVENTO DE BOTAO
        this.uploadAction = function () { $('#upload-file').click(); };

        //ENVIA FOTO
        $scope.sendUpload = function () {
            $('.upload-click').hide(); $('.upload-send').show();
            var formData = new FormData();
            formData.append('file',$('#upload-file')[0].files[0]);
            formData.append('username',sessionStorage.getItem('username'));
            var url = sessionStorage.getItem('baseUrl') + '/assets';
            $.ajax({
                url: url, type: 'POST', data: formData, async: true,
                complete: function (){
                    $('#modalAvatar').closeModal(); $('.upload-click').show(); $('.upload-send').hide(); $scope.searchAvatar();
                }, cache: false, contentType: false, processData: false
            });
        };

        //BUSCA AVATAR
        $scope.searchAvatar = function () {
            var usuario = sessionStorage.getItem('pessoaId');
            var promise = Servidor.buscar('pessoas', {usuario: usuario});
            promise.then(function (response) {
                var avatar = response.data[0].avatar;
                if (avatar !== undefined) {
                    var promise = Servidor.buscarUm('assets', response.data[0].avatar);
                    promise.then(function (response) {
                        sessionStorage.setItem('avatar',response.data.file);
                        $timeout(function() { $scope.avatar = response.data.file; }, 500);
                    });
                }
            });
        };

        /*Acessar modulo via URL*/
        /*var link = window.location.href;
        var url = sessionStorage.getItem('baseFrontUrl');
        this.urlArray = link.split(url+'/');
        var module = this.urlArray[1];
        if (module !== "index.html") {
            this.selecionar(module,'');
        } else {
            if (module === "index.html") {
                this.selecionar('main');
            } else {
                this.selecionar(sessionStorage.getItem('module'), sessionStorage.getItem('moduleOptions'));
            }
        }*/
        //$('.side-link').click(function(ev){ ev.preventDefault(); });

        $scope.verificarPermissoes = function (role){
            return Servidor.verificarPermissoes(role);
        };

        //this.selecionar(sessionStorage.getItem('module'), sessionStorage.getItem('moduleOptions'));

        /*Acessar modulo via URL*/
        /*var url = window.location.href;
        this.urlArray = url.split('/');
        var urlSize = this.urlArray.length;
        if (this.urlArray[urlSize-1] !== "" && this.urlArray[urlSize-1] !== "index.html") {
            this.selecionar(this.urlArray[urlSize-1],'');
        } else {
            this.selecionar(sessionStorage.getItem('module'), sessionStorage.getItem('moduleOptions'));
        }*/
        //$scope.unidadeAtual = sessionStorage.getItem('unidade');
        var sessionId = sessionStorage.getItem('sessionId');
        if (sessionId) { $('body').css('opacity',1); }
        if (sessionStorage.getItem('show_menu')) { $("#slide-out").show(); $('main').css('padding-left',''); $(this).css('left','250px'); }
        else { $("#slide-out").hide(); $('main').css('padding-left','0'); $(this).css('left','10px'); }
        $timeout(function () {
            //$('.modal-trigger').leanModal();
           $('.modal-trigger').modal();
            Servidor.verificarMenu();
            $('.menu_swap').click(function(){
                var visivel = $("#slide-out").css('display');
                if (visivel !== 'none') { $("#slide-out").hide(); $('main').css('padding-left','0'); $(this).css('left','10px'); sessionStorage.setItem('show_menu',false);
                } else { $("#slide-out").show(); $('main').css('padding-left',''); $(this).css('left','250px'); sessionStorage.setItem('show_menu',true); }
            });
        }, 50);
    }]);

})();
