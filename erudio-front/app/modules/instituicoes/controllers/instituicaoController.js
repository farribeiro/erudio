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
    var instituicaoModule = angular.module('instituicaoModule', ['servidorModule','instituicaoDirectives','erudioConfig']);
    instituicaoModule.controller('InstituicaoController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', function($scope, Servidor, $timeout, $templateCache, ErudioConfig) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('INSTITUICAO'); $scope.isAdmin = Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('instituicoes'); $scope.lista = true;
        //ATRIBUTOS
        $scope.titulo = "Instituicoes"; $scope.progresso = false; $scope.cortina = false; $scope.instituicoes = []; $scope.instituicaoRemover = null; $scope.nomeInstituicao = '';
        $scope.pagina = 0; $scope.instituicaoObj = { nome:null, sigla:null, cpfCnpj:null, email:null, endereco:null, telefones:[] };
        //ABRE AJUDAeducacao@itajai.sc.gov.br
        $scope.ajuda = function () { $('#modal-ajuda-instituicao').modal('open'); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
         /* Limpar busca */
        $scope.limparBusca = function(){ $scope.nomeInstituicao=''; $('.tooltipped').tooltip({delay: 30}); };
        //LISTENER BUSCA
        $scope.$watch("nomeInstituicao", function(query){ $scope.buscaInstituicao(query); if(!query) {$scope.icone = 'search'; } else { $scope.icone = 'clear'; } });
        //PREPARA REMOCAO
        $scope.prepararRemover = function (instituicao){ $scope.instituicaoRemover = instituicao; $('.opcoesInstituicao' + instituicao.id).hide(); };
        //REMOVE INSTITUICAO
        $scope.remover = function (){ $scope.mostraProgresso(); Servidor.remover($scope.instituicaoRemover, 'Instituição'); $scope.buscarInstituicoes(); $scope.fechaProgresso(); };
        //FECHA INFO
        $scope.fecharInfo = function(instituicao) { $scope.instituicaoObj = { nome:null, sigla:null, cpfCnpj:null, email:null, endereco:null, telefones:[] }; $(".info"+instituicao.id).show(); $(".informacoes"+instituicao.id).hide(); $('.info-icon').show(); $('.info-icon-close').hide(); };
        
        /* BUSCANDO INSTITUICOES */
        $scope.buscarInstituicoes = function() {
            $scope.mostraProgresso(); var promise = Servidor.buscar('instituicoes',{page: $scope.pagina}); $('.modal-trigger').modal(); $('.tooltipped').tooltip('remove');
            promise.then(function (response){
                if (response.data.length > 0) {
                    var instituicoes = response.data; $scope.instituicoes = instituicoes; $timeout(function() { $('.tooltipped').tooltip({delay: 50}); }, 250);
                    $timeout(function (){ $('.modal-trigger').modal({ dismissible: true , in_duration: 100, out_duration: 100 }); $scope.fechaProgresso(); },500);
                } else { if ($scope.pagina !== 0) { $scope.pagina--; } }
            });
        };
        
        //CARREGA INFO
        $scope.carregarInfo = function (instituicao) {
            var promise = Servidor.buscarUm('instituicoes',instituicao.id); promise.then(function (response) { 
                $scope.instituicaoObj = response.data; $(".info"+instituicao.id).hide(); $(".informacoes"+instituicao.id).show(); $('.info-icon').hide(); $('.info-icon-close').show();
            });
            /*$scope.mostraProgresso(); $('#info-modal-instituicao').modal(); var promise = Servidor.buscarUm('instituicoes',instituicao.id);
            promise.then(function (response) { $scope.instituicao = response.data; });
            $timeout(function(){ $scope.fechaProgresso(); $scope.buscaTelefones(); $('#info-modal-instituicao').modal('open'); }, 300);*/
        };
        
        //BUSCA INSTITUICAO POR NOME
        $scope.buscaInstituicao = function (query) {
            $('.modal-trigger').modal(); $('.tooltipped').tooltip({delay: 30}); $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (query === undefined) { query = ''; } var tamanho = query.length;
                if (tamanho > 3) {
                    $scope.mostraProgresso(); var res = Servidor.buscar('instituicoes',{'nome':query});
                    res.then(function(response){ $scope.instituicoes = response.data; $timeout(function (){ $scope.inicializar(); $scope.fechaProgresso(); }); });
                } else { if (tamanho === 0) { $scope.inicializar(); $scope.buscarInstituicoes(); } }
            }, 1000);
        };
        
        //BUSCA TELEFONES
        $scope.buscaTelefones = function() {
            $('.tooltipped').tooltip({delay: 30}); var promise = Servidor.buscar('telefones',{ pessoa: $scope.instituicaoObj.id });
            promise.then(function(response) {
                $scope.instituicaoObj.telefones = response.data;
                if($scope.instituicaoObj.telefones.length>0) { $scope.listaTelefone = true; } else { $scope.listaTelefone = false; }
            });
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.titulo-typo').html($scope.titulo); $('.material-tooltip').remove();
            $timeout(function () {
                $('.tooltipped').tooltip({delay: 50}); $('ul.tabs').tabs(); $('#modal-ajuda-instituicao').modal();
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
            }, 1000);
        };
        
        //INICIALIZANDO
        $scope.inicializar(); $scope.buscarInstituicoes();
    }]);
})();