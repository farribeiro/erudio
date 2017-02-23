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
    var cursoFormModule = angular.module('cursoFormModule', ['cursoDirectives', 'servidorModule', 'erudioConfig']);
    cursoFormModule.controller('CursoFormController', ['$scope', 'Servidor', 'EtapaService', '$templateCache', '$routeParams', '$timeout', 'ErudioConfig', function ($scope, Servidor, EtapaService, $templateCache, $routeParams, $timeout, ErudioConfig) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll();
        $scope.escrita = Servidor.verificaEscrita('CURSO') || Servidor.verificaAdmin();                        
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateForm('cursos');
        //ATRIBUTOS
        $scope.modalidades = []; $scope.modalidadeId = null; $scope.progresso = false;
        $scope.loader = false; $scope.acao = 'Adicionar'; $scope.cortina = false;
        $scope.EtapaService = EtapaService; $scope.statusBotao = true;
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        $scope.mostraLoader = function (cortina) { $scope.loader = true; if (cortina) { $scope.cortina = true; } };
        $scope.fechaLoader = function () { $scope.loader = false; $scope.cortina = false; };
        //ESTRUTURA
        $scope.curso = {nome: null, modalidade: {id: null}};
        //VALIDAR FORM
        $scope.validar = function (id) { return Servidor.validar(id); };
        //VERIFICA SELECT MODALIDADE NO LOAD DA PÁGINA
        $scope.verificaSelectModalidade = function (modalidade) { if (modalidade === $scope.curso.modalidade.id) { return true; } };
        //SELECIONA MODALIDADE DO CURSO
        $scope.selecionaModalidade = function () { $scope.curso.modalidade.id = $scope.modalidadeId; };
        //MODAL DE CERTEZA PARA VOLTAR
        $scope.prepararVoltar = function (objeto) { if (objeto.nome && !objeto.id) { $('#modal-certeza').openModal(); } else { window.location.href = "/#/cursos"; } };
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-curso').openModal(); };
        //INICIALIZAÇÃO BÁSICA
        $scope.inicializar = function () { $('#modal-ajuda-curso').leanModal(); $('.material-tooltip').remove(); };
        
        //BUSCANDO MODALIDADES DE ENSINO
        $scope.buscarModalidades = function (inicializa) {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('modalidades-ensino', null);
            promise.then(function (response) { 
                $scope.modalidades = response.data;
                if (inicializa) { $timeout(function(){ $('.modalidadeCurso').material_select('destroy'); $('.modalidadeCurso').material_select(); },100); }
                $scope.fechaProgresso(); 
            });
        };
        
        //SALVANDO CURSO
        $scope.finalizar = function (nome) {
            $scope.mostraProgresso();
            if (Servidor.validar('validate')) {
                var promise = Servidor.buscar('cursos',{ 'nome': nome });
                promise.then(function(response){
                    if (response.data.length > 0) {
                        for (var i=0; i<response.data.length; i++) {
                            if (response.data[i].nome === nome) { Servidor.customToast("Ja existe um curso com este nome!"); $scope.fechaProgresso(); return true; }
                            if (i === response.data.length-1) {
                                if (!$scope.curso.id) { $scope.pagina = 0; }
                                var promise = Servidor.finalizar($scope.curso, 'cursos', 'Curso');
                                promise.then(function (response) { if (response.status === 200) { $scope.fechaProgresso(); window.location.href = '/#/cursos'; } });
                            }
                        }
                    } else {
                        if (!$scope.curso.id) { $scope.pagina = 0; }
                        var promise = Servidor.finalizar($scope.curso, 'cursos', 'Curso');
                        promise.then(function (response) { if (response.status === 200) { $scope.fechaProgresso(); window.location.href = '/#/cursos'; } });
                    }
                });
            } else { $scope.fechaProgresso(); }
        };
        
        //CARREGANDO CURSO
        $scope.carregar = function (curso) {
            $scope.mostraProgresso(); $('.tooltipped').tooltip('remove');
            if (curso === 'novo') {
                $scope.buscarModalidades(true);
                Servidor.verificaLabels(); $('#nomeCursoFocus').focus(); $('.title-module').html($scope.acao + ' Curso');
            } else {
                $scope.buscarModalidades(false); $scope.acao = "Editar";
                $('.title-module').html($scope.acao + ' Curso'); var promise = Servidor.buscarUm('cursos', curso);
                promise.then(function(response){
                    $scope.curso = response.data; $('#nomeCursoFocus').focus();
                    $timeout(function(){ $('.modalidadeCurso').material_select('destroy'); $('.modalidadeCurso').material_select(); },100);
                });
            }
        };

        $scope.carregar($routeParams.id); $scope.inicializar();
    }]);
})();