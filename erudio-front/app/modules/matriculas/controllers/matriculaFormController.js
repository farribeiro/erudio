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
    var matriculaFormModule = angular.module('matriculaFormModule', ['matriculaDirectives', 'servidorModule', 'erudioConfig']);
    matriculaFormModule.controller('MatriculaFormController', ['$scope', '$filter', 'Servidor', 'Restangular', '$timeout', '$templateCache', 'PessoaService', 'MatriculaService', '$compile', 'dateTime', 'makePdf', 'ErudioConfig', '$routeParams', function ($scope, $filter, Servidor, Restangular, $timeout, $templateCache, PessoaService, MatriculaService, $compile, dateTime, makePdf, ErudioConfig, $routeParams) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig;
        $scope.escrita = Servidor.verificaEscrita('MATRICULA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        if ($routeParams.id === 'novo') { $scope.tela = ErudioConfig.getTemplateForm('matriculas'); $scope.lista = false; } else { $scope.tela = ErudioConfig.getTemplateCustom('matriculas','informacoes'); $scope.lista = false; }
        //ATRIBUTOS
        if ($routeParams.id === 'novo') { $scope.titulo = 'Matricular'; $scope.matriculando = true; } else { $scope.titulo = "Matrícula - Informações"; $scope.matriculando = false; } $scope.buscaAvancada = false; $scope.frequentaStatus = 'frequenta'; $scope.etapa = {'id': null};
        $scope.nomePessoa = ''; $scope.nomeUnidadeMatricula = ''; $scope.unidades = [];
        $scope.matriculaBusca = { 'aluno': null, 'status': null, 'codigo': null, 'curso': null, 'unidade': null }; 
        $scope.matricula = {'codigo': null, 'aluno': {'id': null}, 'unidadeEnsino': {'id': null}, 'curso': {'id': null}};
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-turma').modal('open'); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //PREPARA VOLTAR
        $scope.prepararVoltar = function (objeto) { window.location = $scope.config.dominio+'/#/matriculas'; };
        //MOSTRA LABELS MENU FAB
        $scope.mostrarLabels = function () { $('.toolchip').fadeToggle(250); };
        //DESABILITAR CAMPO
        $scope.desabilitarCampo = function () { if ($scope.matricula.uniforme.id === null || $scope.matricula.uniforme.id === undefined || $scope.matricula.uniforme.id === '') { $("#uniformeNumero, #calcadoNumero").removeAttr('disabled'); } else { $("#uniformeNumero, #calcadoNumero").attr('disabled','disabled'); } };
        //LIMPAR DISCIPLINA
        $scope.limparDisciplinaCursada = function() { $scope.disciplinaCursada = { 'disciplina': {id: null}, 'matricula': {id: null} }; };
        
        //BUSCA UNIFORMES
        $scope.buscarUniforme = function(matricula) {
            var promise = Servidor.buscar('uniformes', {matricula: matricula.id});
            promise.then(function(response) { $scope.matricula.uniforme = response.data[0]; });
        };
        
        //SALVAR UNIFORME
        $scope.salvarUniforme = function (uniforme) {
            if (uniforme.uniformeNumero && uniforme.calcadoNumero) {
                uniforme.matricula = {id:$scope.matricula.id};
                var promise = Servidor.finalizar(uniforme, 'uniformes', 'Uniforme');
                promise.then(function (response) { $scope.matricula.uniforme = response.data; $scope.desabilitarCampo(); });
            } else { Servidor.customToast('Preencha os campos obrigatórios.'); }
        };

        //REMOVER UNIFORME
        $scope.removerUniforme = function (uniforme) {
            var promise = Servidor.buscarUm('uniformes', uniforme.id);
            promise.then(function (response) { Servidor.remover(response.data, 'Uniforme'); $scope.matricula.uniforme = {}; $scope.desabilitarCampo(); });
        };
        
        //AJUSTA BOTAO MOBILE
        $scope.mobileAjusteBotao = function () {
            Materialize.scrollFire([{selector: '.btnUniforme', offset: 0, callback: function () { $("#acoesMatricula").css('right','20%'); }}]);
        };
        
        //BUSCAR PESSOAS
        $scope.buscarPessoas = function(nomePessoa) {
            $scope.nomePessoa = nomePessoa;
            if (nomePessoa && nomePessoa !== undefined && nomePessoa.length > 3) {
                var params = { 'nome': null, 'cpf': null };
                if (parseInt(nomePessoa)) { params.cpf = nomePessoa; } else { params.nome = nomePessoa; }
                var promise = Servidor.buscar('pessoas', params);
                promise.then(function(response) { if (response.data.length) { $scope.pessoas = response.data; } else { $scope.pessoas = []; } });
            } else { $scope.pessoas = []; }
        };
        
        //CARREGAR PESSOA
        $scope.carregarPessoa = function(pessoa) {
            var promise = Servidor.buscarUm('pessoas', pessoa.id); promise.then(function(response){ $scope.matricula.aluno = response.data; });
            $scope.nomePessoa = pessoa.nome; $('#dropdown-pessoas').hide(); //$scope.getCursos();
            //if ($scope.unidades.length === 1) { $scope.matricula.unidadeEnsino.id = parseInt($scope.unidades[0].id); }
            $timeout(function() {
                $('select').material_select();
                $('#unidadeMatriculaFormAutoComplete').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
            }, 50);
        };
        
        //VAI PARA CADASTRO DE PESSOA
        $scope.intraForms = function () {
            PessoaService.aluno = true;
            PessoaService.abreForm();
            MatriculaService.matricula = $scope.matricula;
            $('.tooltipped').tooltip('remove');
        };
        
        //BUSCAR UNIDADES
        $scope.buscarUnidades = function (nomeUnidade) {
            var params = {nome: null}; var permissao = true; $scope.nomeUnidadeMatricula = nomeUnidade;
            if (nomeUnidade !== undefined && nomeUnidade) { params.nome = nomeUnidade; if (nomeUnidade.length > 4) { permissao = true; } else { permissao = false; } }
            if(permissao) {
                var promise = null; if ($scope.escrita) { promise = Servidor.buscar('unidades-ensino', params); } else { promise = Servidor.buscarUm('unidades-ensino', $scope.unidadeAlocacao); }
                promise.then(function (response) {
                    if ($scope.escrita) { $scope.unidades = response.data; } else { $scope.unidades.push(response.data); $scope.matriculaBusca.unidade = response.data.id; }
                    $timeout(function () { $('select').material_select('destroy'); $('select').material_select(); }, 250);
                });
            }
        };
        
        //SELECIONA UNIDADES
        $scope.selecionaUnidade = function (unidade) {
            $scope.nomeUnidadeMatricula = unidade.nomeCompleto; $('#unidadeMatriculaFormAutoComplete').val(unidade.nomeCompleto);
            if (unidade) { if ($scope.matriculando) { $scope.matricula.unidadeEnsino = unidade; $scope.getCursos(); } else { $scope.matriculaBusca.unidade = unidade.id; }
            } else {
                var unidade = null;
                for (var i = 0; i < $scope.unidades.length; i++) { if ($scope.unidades[i].id === parseInt($scope.matriculaBusca.unidade)) { unidade = $scope.unidades[i]; } }
                $scope.matriculaBusca.unidade = unidade.id;
            }
        };
        
        //BUSCA CURSOS
        $scope.getCursos = function () {
            $scope.mostraProgresso(); var promise = Servidor.buscar('cursos-ofertados',{unidadeEnsino: $scope.matricula.unidadeEnsino.id});
            promise.then(function (response) {
                $scope.cursos = response.data;
                $timeout(function () { $('#cursoMatricula, #unidadeBusca').material_select('destroy'); $('#cursoMatricula, #unidadeBusca').material_select(); $scope.fechaProgresso(); }, 500);
            });
        };
        
        //SALVA MATRICULA
        $scope.finalizarMatricula = function () {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('matriculas', {'aluno': $scope.matricula.aluno.id, 'curso': $scope.matricula.curso.id});
            promise.then(function (response) {
                if (response.data.length) {
                    Materialize.toast($scope.matricula.aluno.nome.toUpperCase() + ' já está matriculado(a) nesse curso na unidade ' + response.data[0].unidadeEnsino.nomeCompleto + '.', 7000);
                    $scope.fechaProgresso();
                } else if ($scope.validar()) {
                    $scope.matricula.codigo = null;
                    var matricula = { aluno: {id: $scope.matricula.aluno.id }, curso: {id: $scope.matricula.curso.id }, unidadeEnsino: {id: $scope.matricula.unidadeEnsino.id }, codigo: null };
                    var promise = Servidor.finalizar(matricula, 'matriculas', 'Matricula');
                    promise.then(function (responseMatricula) { $scope.fechaProgresso(); window.location.href=ErudioConfig.dominio+'/#/matriculas/'+responseMatricula.data.id+'/enturmacoes'; }); $scope.fechaProgresso();
                } else { $scope.fechaProgresso(); }
            });
        };
        
        //VALIDAR
        $scope.validar = function () {
            var auxiliar = 0; console.log($scope.matricula);
            if ($scope.matricula.unidadeEnsino.id === null || $scope.matricula.curso.id === null) { auxiliar++; Servidor.customToast('Campos obrigatórios não preenchidos.');
            } else if ($scope.matricula.unidadeEnsino.id === null) { auxiliar++; Servidor.customToast('Selecione uma Unidade de Ensino'); }
            if (auxiliar === 0) { return true; } else { return false; }
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            //$scope.facilAcesso = status; $scope.mostraProgresso();
            $scope.mostraProgresso(); $('.title-module').html($scope.titulo); $('#modal-ajuda-matricula').modal(); $('.material-tooltip').remove(); $scope.mobileAjusteBotao();
            if ($routeParams.id === 'novo') {
                $('select').material_select('destroy'); $('select').material_select(); $('.tooltipped').tooltip({delay: 50});
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                $('.dropdown-button').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                Servidor.entradaPagina(); $scope.fechaProgresso(); Servidor.verificaLabels(); $scope.buscarUnidades();
            } else {
                var promise = Servidor.buscarUm('matriculas', $routeParams.id);
                promise.then(function (response){
                    $scope.matricula = response.data;
                    if(!$scope.escrita && $scope.matricula.unidadeEnsino.id !== $scope.unidadeAlocacao) { $scope.fechaProgresso(); return Servidor.customToast('Este aluno não está matriculado em sua unidade.'); }
                    else {
                        $scope.buscarUniforme($scope.matricula);
                        if($scope.matricula.status === 'FALECIDO' || $scope.matricula.status === 'TRANCADO' || $scope.matricula.status === 'ABANDONO'){ $scope.frequentaStatus = 'Não Frequenta'; }
                        var promiseContato = Servidor.buscar('telefones', {pessoa: $scope.matricula.unidadeEnsino.id});
                        promiseContato.then(function(response){ $scope.matricula.unidadeEnsino.telefones = response.data; });
                        var promisePessoa = Servidor.buscarUm('pessoas', $scope.matricula.aluno.id);
                        promisePessoa.then(function(response){
                            $scope.matricula.aluno = response.data; $scope.aluno = response.data;
                            if($scope.aluno.genero === 'f' || $scope.aluno.genero === 'F'){ $scope.genero = 'a aluna'; } else { $scope.genero = 'o aluno'; };
                            if ($scope.matricula.enturmacoesAtivas.length) {
                                var promise = Servidor.buscarUm('turmas', $scope.matricula.enturmacoesAtivas[0].turma.id);
                                promise.then(function(response){ $scope.matricula.turmaAtual = response.data; $scope.matricula.etapaAtual = response.data.etapa; });
                                var promise = Servidor.buscar('etapas', {'curso': $scope.matricula.curso.id});
                                promise.then(function (response) {
                                    $scope.etapas = response.data; $scope.disciplinasCursadas = []; $scope.etapa.id = null;
                                    $timeout(function () { $('#etapaDisciplina').material_select(); }, 50); $scope.fechaProgresso(); $scope.desabilitarCampo(); Servidor.verificaLabels();
                                });
                            } else {
                                $('select').material_select('destroy'); $('select').material_select(); $('.tooltipped').tooltip({delay: 50});
                                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                                $('.dropdown-button').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                                Servidor.entradaPagina(); $scope.fechaProgresso(); Servidor.verificaLabels();
                            }
                        });
                    }
                });
            }
        };
        $scope.inicializar();
    }]);
})();