(function (){
    var erudio = angular.module('Erudio',['ngMaterial', 'restangular', 'erudioConfig', 'ngRoute', 'appDirectives', 'modules', 'util', 'validator', 'pascalprecht.translate']);
    erudio.config(['$mdThemingProvider', '$routeProvider', 'RestangularProvider', 'ErudioConfigProvider', '$translateProvider', function ($mdThemingProvider, $routeProvider, RestangularProvider, ErudioConfigProvider, $translateProvider) {
        
        //DEFININDO TEMA - CORES PRIMARIAS E SECUNDARIAS
        var tema = $mdThemingProvider.theme('default'); 
        tema.primaryPalette("blue",{ 'default': '800', 'hue-1': '700', 'hue-2': '500' }); tema.accentPalette('pink');
        
        //CRIANDO ROTAS
        $routeProvider
            .when('/',{ templateUrl: 'apps/home/partials/template.html', controller: 'HomeController' })
            .when('/instituicoes',{ templateUrl: 'apps/instituicoes/partials/index.html', controller: 'InstituicaoController', controllerAs: 'ctrl' })
            .when('/instituicoes/:id',{ templateUrl: 'apps/instituicoes/partials/form.html', controller: 'InstituicaoFormController' })
            .when('/tipos-unidade',{ templateUrl: 'apps/tiposUnidade/partials/index.html', controller: 'TipoUnidadeController' })
            .when('/tipos-unidade/:id',{ templateUrl: 'apps/tiposUnidade/partials/form.html', controller: 'TipoUnidadeFormController' })
            .when('/unidades',{ templateUrl: 'apps/unidades/partials/index.html', controller: 'UnidadeController' })
            .when('/unidades/:id',{ templateUrl: 'apps/unidades/partials/form.html', controller: 'UnidadeFormController' })
            .when('/regimes',{ templateUrl: 'apps/unidades/partials/index.html', controller: 'RegimeController' })
            .when('/regimes/:id',{ templateUrl: 'apps/unidades/partials/form.html', controller: 'RegimeFormController' })
            .when('/cursos',{ templateUrl: 'apps/cursos/partials/index.html', controller: 'CursoController' })
            .when('/cursos/:id',{ templateUrl: 'apps/cursos/partials/form.html', controller: 'CursoFormController' })
            .when('/etapas',{ templateUrl: 'apps/etapas/partials/index.html', controller: 'EtapaController' })
            .when('/etapas/:id',{ templateUrl: 'apps/etapas/partials/form.html', controller: 'EtapaFormController' })
            .when('/disciplinas',{ templateUrl: 'apps/disciplinas/partials/index.html', controller: 'DisciplinaController' })
            .when('/disciplinas/:id',{ templateUrl: 'apps/disciplinas/partials/form.html', controller: 'DisciplinaFormController' })
            .when('/modulos',{ templateUrl: 'apps/modulos/partials/index.html', controller: 'ModuloController' })
            .when('/modulos/:id',{ templateUrl: 'apps/modulos/partials/form.html', controller: 'ModuloFormController' })
            .when('/turnos',{ templateUrl: 'apps/turnos/partials/index.html', controller: 'TurnoController' })
            .when('/turnos/:id',{ templateUrl: 'apps/turnos/partials/form.html', controller: 'TurnoFormController' })
            .when('/modelos-horario',{ templateUrl: 'apps/modelosGradeHorario/partials/index.html', controller: 'ModeloGradeHorarioController' })
            .when('/modelos-horario/:id',{ templateUrl: 'apps/modelosGradeHorario/partials/form.html', controller: 'ModeloGradeHorarioFormController' })
            .when('/quadros-horario',{ templateUrl: 'apps/quadroHorarios/partials/index.html', controller: 'QuadroHorarioController' })
            .when('/quadros-horario/:id',{ templateUrl: 'apps/quadroHorarios/partials/form.html', controller: 'QuadroHorarioFormController' })
            .when('/calendarios',{ templateUrl: 'apps/calendarios/partials/index.html', controller: 'CalendarioController' })
            .when('/calendarios/:id',{ templateUrl: 'apps/calendarios/partials/form.html', controller: 'CalendarioFormController' })
            .when('/calendarios/view/:id',{ templateUrl: 'apps/calendarios/partials/view.html', controller: 'CalendarioViewController' })
            .when('/teste',{ templateUrl: 'apps/teste/partials/teste.html', controller: 'TESTE', controllerAs: 'ctrl' })
            .otherwise({ redirectTo: '/404' })
        ;
        
        //DEFININDO TRADUÇÕES
        $translateProvider.useStaticFilesLoader({ prefix:ErudioConfigProvider.$get().dominio+'/util/translations/locale-', suffix: '.json' });
        $translateProvider.translations('en'); 
        $translateProvider.translations('ptbr');
        $translateProvider.preferredLanguage('ptbr');
        
        //DEFININDO URL DO SERVIDOR REST
        RestangularProvider.setBaseUrl(ErudioConfigProvider.$get().urlServidor);
    }]);
    
    erudio.controller('AppController',['$scope', '$mdSidenav', '$mdDialog', 'Util', '$translate', '$timeout', 'ErudioConfig', function($scope, $mdSidenav, $mdDialog, Util, $translate, $timeout, ErudioConfig){
        
        //SE NÃO TEM SESSÃO, REDIRECIONA PRO LOGIN
        if (!sessionStorage.getItem('autenticado')) { window.location = ErudioConfig.dominio + '/login.html'; }
        
        //TITULO DA TOOLBAR
        $scope.titulo = 'Erudio';
        $scope.atribuicoes = [];
        $scope.cortinaAlocacao = true;
        $('.loader').hide();
        
        //ATRIBUTOS
        $scope.attrAtual = { attr:{id:null}, alocacao:{id:null} };
        
        //BUSCAR USUARIO
        $scope.buscarUsuario = function () {
            $scope.atribuicoes = JSON.parse(sessionStorage.getItem('atribuicoes'));
            if ($scope.atribuicoes.atribuicoes.length === 0) { $('#menu-lateral').remove(); $('.conteudo').remove(); $(".forbidden").show(); }
            if ($scope.atribuicoes.atribuicoes.length === 1){ $scope.cortinaAlocacao = false; $scope.attrAtual.attr.id = $scope.atribuicoes.atribuicoes[0].id; sessionStorage.setItem('atribuicao-ativa',JSON.stringify($scope.atribuicoes.atribuicoes[0])); }
            $scope.userAtivo = $scope.atribuicoes.nomeExibicao;
            if (!Util.isVazio(sessionStorage.getItem("atribuicao-ativa"))) { $scope.cortinaAlocacao = false; var attrAtiva = JSON.parse(sessionStorage.getItem("atribuicao-ativa")); $scope.attrAtual.attr.id = attrAtiva.id; }
        };
        $scope.buscarUsuario();
                
        //CARREGAR ABTRIBUICAO
        $scope.carregarAtribuicao = function (id) { 
            $scope.atribuicoes.atribuicoes.forEach(function(attr){ if (attr.id === parseInt(id)) { sessionStorage.setItem('atribuicao-ativa',JSON.stringify(attr)); } });
            $timeout(function(){ location.reload(); },500);
        };
                
        //CRIANDO LABELS DOS MENUS
        var menuLabels = ['INSTITUICOES_UNIDADES','INSTITUICOES','TIPOS_UNIDADE','UNIDADES_ENSINO',"GESTAO","REGIMES_ENSINO","CURSOS","ETAPAS","DISCIPLINAS",
            "MODULOS","TURNOS","MODELOS_HORARIO","QUADROS_HORARIO","CALENDARIOS","EVENTOS","VIDA_ESCOLAR","TURMAS","MATRICULAS","MOVIMENTACOES","NOTAS",
            "PESSOAS_TITULO","PESSOAS","CARGOS","FUNCIONARIOS","AVALIACOES_TITLE","TIPOS_AVALIACAO","HABILIDADES","AVALIACOES","DOCUMENTOS","BOLETIM",
            "DIARIO_FREQUENCIA","DIARIO_NOTAS","RELATORIOS","ALUNOS_DEFASADOS","ALUNOS_ENTURMADOS","ESPELHO_NOTA","ADMINISTRADOR", "USUARIOS","PERMISSOES","GRUPOS_PERMISSOES",
            "PERFIL","SOBRE","MUDOU","CONFIG","SAIR"];
        
        $timeout(function(){
            $translate(menuLabels).then(function(traducoes){
                //CRIANDO MENUS
                $scope.perfil = traducoes.PERFIL;
                $scope.sobre = traducoes.SOBRE;
                $scope.mudou = traducoes.MUDOU;
                $scope.config = traducoes.CONFIG;
                $scope.sair = traducoes.SAIR;
                
                $scope.menus = [
                    { 
                        categoria: traducoes.INSTITUICOES_UNIDADES, temSubmenu : true,
                        links: [ 
                            { label: traducoes.INSTITUICOES, href: "#!/instituicoes", permissao: 'INSTITUICOES' },
                            { label: traducoes.TIPOS_UNIDADE, href: "#!/tipos-unidade", permissao: 'TIPO_UNIDADE' },
                            { label: traducoes.UNIDADES_ENSINO, href: "#!/unidades", permissao: 'UNIDADE_ENSINO' }
                        ]
                    },
                    { 
                        categoria: traducoes.GESTAO, temSubmenu : true,
                        links: [ 
                            { label: traducoes.REGIMES_ENSINO, href: "#!/regimes", permissao: 'REGIME_ENSINO' },
                            { label: traducoes.CURSOS, href: "#!/cursos", permissao: 'CURSO' },
                            { label: traducoes.ETAPAS, href: "#!/etapas", permissao: 'ETAPA' },
                            { label: traducoes.DISCIPLINAS, href: "#!/disciplinas", permissao: 'DISCIPLINA' },
                            { label: traducoes.MODULOS, href: "#!/modulos", permissao: 'MODULO' },
                            { label: traducoes.TURNOS, href: "#!/turnos", permissao: 'TURNO' },
                            { label: traducoes.MODELOS_HORARIO, href: "#!/modelos-horario", permissao: 'MODELO_QUADRO' },
                            { label: traducoes.QUADROS_HORARIO, href: "#!/quadros-horario", permissao: 'QUADRO_HORARIO' },
                            { label: traducoes.CALENDARIOS, href: "#!/calendarios", permissao: 'CALENDARIO' },
                            { label: traducoes.EVENTOS, href: "#!/eventos", permissao: 'EVENTOS' }
                        ]
                    },
                    { 
                        categoria: traducoes.VIDA_ESCOLAR, temSubmenu : true,
                        links: [ 
                            { label: traducoes.TURMAS, href: "#!/turmas", permissao: 'TURMA' },
                            { label: traducoes.MATRICULAS, href: "#!/matriculas", permissao: 'MATRICULA' },
                            { label: traducoes.MOVIMENTACOES, href: "#!/movimentacoes", permissao: 'MOVIMENTACAO' },
                            { label: traducoes.NOTAS, href: "#!/disciplinas", permissao: 'MEDIAS' }
                        ]
                    },
                    { 
                        categoria: traducoes.PESSOAS_TITULO, temSubmenu : true,
                        links: [ 
                            { label: traducoes.PESSOAS, href: "#!/pessoas", permissao: 'PESSOA' },
                            { label: traducoes.CARGOS, href: "#!/cargos", permissao: 'CARGO' },
                            { label: traducoes.FUNCIONARIOS, href: "#!/funcionarios", permissao: 'FUNCIONARIO' }
                        ]
                    },
                    { 
                        categoria: traducoes.AVALIACOES_TITLE, temSubmenu : true,
                        links: [ 
                            { label: traducoes.TIPOS_AVALIACAO, href: "#!/tipos-avaliacao", permissao: 'TIPOS_AVALIACAO' },
                            { label: traducoes.HABILIDADES, href: "#!/habilidades", permissao: 'HABILIDADES' },
                            { label: traducoes.AVALIACOES, href: "#!/avaliacoes", permissao: 'AVALIACAO' }
                        ]
                    },
                    { 
                        categoria: traducoes.DOCUMENTOS, temSubmenu : true,
                        links: [ 
                            { label: traducoes.BOLETIM, href: "#!/boletins", permissao: 'BOLETIM_ESCOLAR' },
                            { label: traducoes.DIARIO_FREQUENCIA, href: "#!/diario-frequencias", permissao: 'DIARIO_FREQUENCIA' },
                            { label: traducoes.DIARIO_NOTAS, href: "#!/diario-notas", permissao: 'RELATORIOS' }
                        ]
                    },
                    { 
                        categoria: traducoes.RELATORIOS, temSubmenu : true,
                        links: [ 
                            { label: traducoes.ALUNOS_DEFASADOS, href: "#!/alunos-defasados", permissao: 'RELATORIOS' },
                            { label: traducoes.ALUNOS_ENTURMADOS, href: "#!/alunos-enturmados", permissao: 'RELATORIOS' },
                            { label: traducoes.ESPELHO_NOTA, href: "#!/espelho-notas", permissao: 'RELATORIOS' }
                        ]
                    },
                    { 
                        categoria: traducoes.ADMINISTRADOR, temSubmenu : true,
                        links: [ 
                            { label: traducoes.USUARIOS, href: "#!/usuarios", permissao: 'USUARIOS' },
                            { label: traducoes.PERMISSOES, href: "#!/permissoes", permissao: 'PERMISSOES' },
                            { label: traducoes.GRUPOS_PERMISSOES, href: "#!/grupos-permissoes", permissao: 'GRUPO_PERMISSOES' }
                        ]
                    }
                ];
            });
        },200);
        
        //VERIFICA PERMISSÃO
        $scope.verificaPermissao = function (role) { return Util.verificaPermissao(role); };

        //CONTROLE DE MENU
        $scope.abrirMenu = function () { $mdSidenav('left').toggle(); }; $scope.menuAberto = null;
        $scope.menuToggle = function (index) { if (index !== $scope.menuAberto) { $('.submenu').hide(); } $scope.menuAberto = index; $(".submenu"+index).slideToggle(150); };
        
        //ABRINDO MODAL O QUE MUDOU
        $scope.oQueMudou = function (event) { Util.modal(event, 'apps/templates/partials/oQueMudou.html', $mdDialog); };
        
        //ELIMINANDO MENUS VAZIOS
        angular.element(document).ready(function(){ $timeout(function(){ Util.ajustaMenus(); },500); });
    }]);
})();