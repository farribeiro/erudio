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
    var app = angular.module('erudio', ['ngRoute', 'restangular', 'mainModule']);
    
    app.defaultHeaders = '"Content-type":"application/json"';
    
    app.config(['$routeProvider', 'RestangularProvider', '$httpProvider', '$logProvider', '$locationProvider', function($routeProvider, RestangularProvider, $httpProvider, $logProvider, $locationProvider){
        //$httpProvider.defaults.headers["delete"] = {'Content-Type': 'application/json;charset=utf-8'};
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
        $logProvider.debugEnabled(true);
        
        /*
         * Definindo Rotas
         */
        $routeProvider.when('/',{ templateUrl: 'app/modules/main/partials/main.html', controller: 'MainController'
        }).when('/instituicoes',{ templateUrl: 'app/modules/instituicoes/partials/instituicoes.html', controller: 'InstituicaoController'
        }).when('/tipos-unidade',{ templateUrl: 'app/modules/tipos/partials/tipos.html', controller: 'TipoController'
        }).when('/unidades',{ templateUrl: 'app/modules/unidades/partials/unidades.html', controller: 'UnidadeController'
        }).when('/regimes',{ templateUrl: 'app/modules/regimes/partials/regimes.html', controller: 'RegimeController'
            
        }).when('/cursos',{ templateUrl: 'app/modules/cursos/partials/lista.html', controller: 'CursoController'
        }).when('/cursos/:id',{ templateUrl: 'app/modules/cursos/partials/form.html', controller: 'CursoFormController'
            
        }).when('/etapas',{ templateUrl: 'app/modules/etapas/partials/etapas.html', controller: 'EtapaController'
        }).when('/disciplinas',{ templateUrl: 'app/modules/disciplinas/partials/disciplinas.html', controller: 'DisciplinaController'
        }).when('/modulos',{ templateUrl: 'app/modules/modulos/partials/modulos.html', controller: 'moduloController'
        }).when('/turnos',{ templateUrl: 'app/modules/turnos/partials/turnos.html', controller: 'TurnoController'
        }).when('/modelo-quadro-horarios',{ templateUrl: 'app/modules/modeloQuadroHorarios/partials/modeloQuadroHorarios.html', controller: 'modeloQuadroHorarioController'
        }).when('/quadro-horarios',{ templateUrl: 'app/modules/quadroHorarios/partials/quadroHorarios.html', controller: 'quadroHorarioController'
        }).when('/calendarios',{ templateUrl: 'app/modules/calendarios/partials/calendarios.html', controller: 'calendarioController'
        }).when('/eventos',{ templateUrl: 'app/modules/eventos/partials/eventos.html', controller: 'eventoController'
        }).when('/turmas',{ templateUrl: 'app/modules/turmas/partials/turmas.html', controller: 'TurmaController'
        }).when('/matriculas',{ templateUrl: 'app/modules/matriculas/partials/matriculas.html', controller: 'MatriculaController'
        }).when('/movimentacoes',{ templateUrl: 'app/modules/movimentacoes/partials/movimentacoes.html', controller: 'movimentacoesController'
        }).when('/pessoas',{ templateUrl: 'app/modules/pessoas/partials/pessoas.html', controller: 'pessoaController'
        }).when('/cargos',{ templateUrl: 'app/modules/cargos/partials/cargos.html', controller: 'cargoController'
        }).when('/funcionarios',{ templateUrl: 'app/modules/funcionarios/partials/funcionarios.html', controller: 'funcionarioController'
        }).when('/tipos-avaliacoes',{ templateUrl: 'app/modules/tiposAvaliacoes/partials/tiposAvaliacoes.html', controller: 'tiposAvaliacoesController'
        }).when('/habilidades',{ templateUrl: 'app/modules/habilidades/partials/habilidades.html', controller: 'habilidadeController'
        }).when('/avaliacoes',{ templateUrl: 'app/modules/avaliacoes/partials/avaliacoes.html', controller: 'AvaliacaoController'
        }).when('/boletim-escolar',{ templateUrl: 'app/modules/boletimEscolar/partials/boletimEscolar.html', controller: 'boletimEscolarController'
        }).when('/diario-frequencia',{ templateUrl: 'app/modules/diarioFrequencia/partials/diarioFrequencia.html', controller: 'diarioFrequenciasController'
        }).when('/historico-escolar',{ templateUrl: 'app/modules/historicoEscolar/partials/historicoEscolar.html', controller: 'historicoEscolarController'
        }).when('/vagas',{ templateUrl: 'app/modules/vagas/partials/vagas.html', controller: 'VagaController'
        }).when('/registro-matriculas',{ templateUrl: 'app/modules/registroMatriculas/partials/registroMatriculas.html', controller: 'registroMatriculasController'
        }).when('/alunos-defasados',{ templateUrl: 'app/modules/alunosDefasados/partials/alunosDefasados.html', controller: 'alunosDefasadosController'
        }).when('/espelho-notas',{ templateUrl: 'app/modules/espelhoNotas/partials/espelhoNotas.html', controller: 'espelhoNotasController'
        }).when('/usuarios',{ templateUrl: 'app/modules/usuarios/partials/usuarios.html', controller: 'UsuarioController'
        }).when('/permissoes',{ templateUrl: 'app/modules/permissoes/partials/permissoes.html', controller: 'PermissaoController'
        }).when('/grupo-permissoes',{ templateUrl: 'app/modules/grupoPermissoes/partials/grupoPermissoes.html', controller: 'GrupoPermissaoController'
        });
        RestangularProvider.setBaseUrl('http://10.100.0.11/novoeducar/web/app_dev.php/api');
        //RestangularProvider.setBaseUrl('http://erudioserver.com/web/app.php/api');
        //RestangularProvider.setBaseUrl('http://10.100.7.138/novoeducar/web/app_dev.php/api');
    }]);

    app.controller('AppController',['$timeout','$routeParams', function($timeout){
        sessionStorage.setItem('baseUrl','http://10.100.0.11/novoeducar/web/app_dev.php/api');
        sessionStorage.setItem('baseUploadUrl','http://10.100.0.11/novoeducar/web/bundles/assets/uploads/');
        var sessionId = sessionStorage.getItem('sessionId');
        var username = sessionStorage.getItem('username');
        var nome = sessionStorage.getItem('nome');
        var key = sessionStorage.getItem('key');
        
        if (!sessionId) {
            window.location = 'login.html';
        } else {
            $timeout(function(){
               $('.username').html(nome);
            },500);
        }
        
        var width = $(window).width();
        if (width < 992) {
            $('head').append('<script type="text/javascript" src="lib/js/jquery/jquery.mobile.min.js"></script>');
        }
        
        /*$(window).resize(function(){
            var width = $(window).width();
            if (width < 992) {
                $('head').append('<script type="text/javascript" src="lib/js/jquery/jquery.mobile.min.js"></script>');
            }
        });*/
    }]);
})();
