(function (){
    var erudio = angular.module('Erudio',['ngMaterial', 'restangular', 'erudioConfig', 'ngRoute', 'appDirectives', 'modules', 'util', 'validator', 'pascalprecht.translate']);
    erudio.config(['$mdThemingProvider', '$routeProvider', 'RestangularProvider', 'ErudioConfigProvider', '$translateProvider', function ($mdThemingProvider, $routeProvider, RestangularProvider, ErudioConfigProvider, $translateProvider) {
        
        //DEFININDO TEMA - CORES PRIMARIAS E SECUNDARIAS
        var tema = $mdThemingProvider.theme('default'); 
        tema.primaryPalette("blue",{ 'default': '800', 'hue-1': '700', 'hue-2': '500' }); tema.accentPalette('pink');
        
        //CRIANDO ROTAS
        $routeProvider
            .when('/',{ templateUrl: 'apps/home/partials/template.html', controller: 'HomeController' })
            .when('/instituicoes',{ templateUrl: 'apps/instituicoes/partials/index.html', controller: 'InstituicaoController' })
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
    
    erudio.controller('AppController',['$scope', '$mdSidenav', '$mdDialog', 'Util', '$translate', '$timeout', function($scope, $mdSidenav, $mdDialog, Util, $translate, $timeout){
        
        //TITULO DA TOOLBAR
        $scope.titulo = 'Erudio';
                
        //CRIANDO LABELS DOS MENUS
        var menuLabels = ['INSTITUICOES_UNIDADES','INSTITUICOES','TIPOS_UNIDADE','UNIDADES_ENSINO'];
        $timeout(function(){
            $translate(menuLabels).then(function(traducoes){
                //CRIANDO MENUS
                $scope.menus = [
                    { 
                        categoria: traducoes.INSTITUICOES_UNIDADES, temSubmenu : true,
                        links: [ 
                            { label: traducoes.INSTITUICOES, href: "#!/instituicoes" },
                            { label: traducoes.TIPOS_UNIDADE, href: "#!/tipos-unidade" },
                            { label: traducoes.UNIDADES_ENSINO, href: "#!/unidades" }
                        ]
                    },
                    { 
                        categoria: "Gestão", temSubmenu : true,
                        links: [ 
                            { label: "Regimes de Ensino", href: "#!/regimes" },
                            { label: "Cursos", href: "#!/cursos" },
                            { label: "Etapas", href: "#!/etapas" },
                            { label: "Disciplinas", href: "#!/disciplinas" },
                            { label: "Módulos", href: "#!/modulos" },
                            { label: "Turnos", href: "#!/turnos" },
                            { label: "Modelos de Horário", href: "#!/modelos-horario" },
                            { label: "Quadros de Horário", href: "#!/quadros-horario" },
                            { label: "Calendários Escolares", href: "#!/calendarios" },
                            { label: "Eventos", href: "#!/eventos" }
                        ]
                    },
                    { 
                        categoria: "Vida Escolar", temSubmenu : true,
                        links: [ 
                            { label: "Turmas", href: "#!/turmas" },
                            { label: "Matrículas e Enturmações", href: "#!/matriculas" },
                            { label: "Movimentações", href: "#!/movimentacoes" },
                            { label: "Notas e Faltas", href: "#!/disciplinas" }
                        ]
                    },
                    { 
                        categoria: "Pessoas", temSubmenu : true,
                        links: [ 
                            { label: "Pessoas", href: "#!/pessoas" },
                            { label: "Cargos", href: "#!/cargos" },
                            { label: "Funcionários", href: "#!/funcionarios" }
                        ]
                    },
                    { 
                        categoria: "Avaliações", temSubmenu : true,
                        links: [ 
                            { label: "Tipos de Avaliação", href: "#!/tipos-avaliacao" },
                            { label: "Habilidades", href: "#!/habilidades" },
                            { label: "Avaliações", href: "#!/avaliacoes" }
                        ]
                    },
                    { 
                        categoria: "Documentos", temSubmenu : true,
                        links: [ 
                            { label: "Boletim Escolar", href: "#!/boletins" },
                            { label: "Diário de Frequência", href: "#!/diario-frequencias" },
                            { label: "Diário de Notas", href: "#!/diario-notas" }
                        ]
                    },
                    { 
                        categoria: "Relatórios", temSubmenu : true,
                        links: [ 
                            { label: "Alunos Defasados", href: "#!/alunos-defasados" },
                            { label: "Alunos Enturmados", href: "#!/alunos-enturmados" },
                            { label: "Espelho de Notas", href: "#!/espelho-notas" }
                        ]
                    },
                    { 
                        categoria: "Administrador", temSubmenu : true,
                        links: [ 
                            { label: "Usuário", href: "#!/usuarios" },
                            { label: "Permissão", href: "#!/permissoes" },
                            { label: "Grupo de Permissões", href: "#!/grupos-permissoes" }
                        ]
                    }
                ];
            });
        },200);
            
            
        //CONTROLE DE MENU
        $scope.abrirMenu = function () { $mdSidenav('left').toggle(); };
        $scope.menuToggle = function (index) { if ($('.submenu'+index).is(':visible')) { $('.submenu').hide(); } else { $('.submenu').hide(); $('.submenu'+index).show(); } };
        
        //ABRINDO MODAL O QUE MUDOU
        $scope.oQueMudou = function (event) { Util.modal(event, 'apps/templates/partials/oQueMudou.html', $mdDialog); };
    }]);
})();