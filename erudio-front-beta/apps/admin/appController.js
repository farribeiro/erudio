
(function (){
    var erudio = angular.module('Erudio',['ngMaterial', 'restangular', 'erudioConfig', 'ngRoute', 'appDirectives', 'modules', 'util', 'validator', 'pascalprecht.translate']);
    erudio.config(['$mdThemingProvider', '$routeProvider', 'RestangularProvider', 'ErudioConfigProvider', '$translateProvider','$mdDateLocaleProvider', function ($mdThemingProvider, $routeProvider, RestangularProvider, ErudioConfigProvider, $translateProvider,$mdDateLocaleProvider) {
        
        //DEFININDO TEMA - CORES PRIMARIAS E SECUNDARIAS
        var tema = $mdThemingProvider.theme('default'); 
        tema.primaryPalette("blue",{ 'default': '800', 'hue-1': '700', 'hue-2': '500' }); tema.accentPalette('pink');
        
        //DEFININDO FORMATO DE DATA
        $mdDateLocaleProvider.formatDate = function (date) {
            return moment(date).format('DD/MM/YYYY');
        };
        $mdDateLocaleProvider.months = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
        $mdDateLocaleProvider.shortMonths = ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"];
        $mdDateLocaleProvider.days = ["Segunda","Terça","Quarta","Quinta","Sexta","Sábado","Domingo"];
        $mdDateLocaleProvider.shortDays = ["Seg","Ter","Qua","Qui","Sex","Sáb","Dom"];
        $mdDateLocaleProvider.weekNumberFormatter = function(weekNumber) { return 'Semana ' + weekNumber; };
        $mdDateLocaleProvider.msgCalendar = 'Calendário';
        $mdDateLocaleProvider.msgOpenCalendar = 'Abrir o calendário';

        //CRIANDO ROTAS
        $routeProvider
            .when('/',{ templateUrl: 'apps/admin/home/partials/template.html', controller: 'HomeController' })            
            .when('/instituicoes',{ templateUrl: 'apps/admin/instituicoes/partials/index.html', controller: 'InstituicaoController', controllerAs: 'ctrl' })
            .when('/instituicoes/:id',{ templateUrl: 'apps/admin/instituicoes/partials/form.html', controller: 'InstituicaoFormController', controllerAs: 'ctrl' })
            .when('/tipos-unidade',{ templateUrl: 'apps/admin/tiposUnidade/partials/index.html', controller: 'TipoUnidadeController', controllerAs: 'ctrl' })
            .when('/tipos-unidade/:id',{ templateUrl: 'apps/admin/tiposUnidade/partials/form.html', controller: 'TipoUnidadeFormController', controllerAs: 'ctrl' })
            .when('/unidades',{ templateUrl: 'apps/admin/unidades/partials/index.html', controller: 'UnidadeController', controllerAs: 'ctrl' })
            .when('/unidades/:id',{ templateUrl: 'apps/admin/unidades/partials/form.html', controller: 'UnidadeFormController', controllerAs: 'ctrl' })
            .when('/regimes',{ templateUrl: 'apps/admin/unidades/partials/index.html', controller: 'RegimeController', controllerAs: 'ctrl' })
            .when('/regimes/:id',{ templateUrl: 'apps/admin/unidades/partials/form.html', controller: 'RegimeFormController', controllerAs: 'ctrl' })
            .when('/cursos',{ templateUrl: 'apps/admin/cursos/partials/index.html', controller: 'CursoController', controllerAs: 'ctrl' })
            .when('/cursos/:id',{ templateUrl: 'apps/admin/cursos/partials/form.html', controller: 'CursoFormController', controllerAs: 'ctrl' })
            .when('/etapas',{ templateUrl: 'apps/admin/etapas/partials/index.html', controller: 'EtapaController', controllerAs: 'ctrl' })
            .when('/etapas/:id',{ templateUrl: 'apps/admin/etapas/partials/form.html', controller: 'EtapaFormController', controllerAs: 'ctrl' })
            .when('/disciplinas',{ templateUrl: 'apps/admin/disciplinas/partials/index.html', controller: 'DisciplinaController', controllerAs: 'ctrl' })
            .when('/disciplinas/:id',{ templateUrl: 'apps/admin/disciplinas/partials/form.html', controller: 'DisciplinaFormController', controllerAs: 'ctrl' })
            .when('/modulos',{ templateUrl: 'apps/admin/modulos/partials/index.html', controller: 'ModuloController', controllerAs: 'ctrl' })
            .when('/modulos/:id',{ templateUrl: 'apps/admin/modulos/partials/form.html', controller: 'ModuloFormController', controllerAs: 'ctrl' })
            .when('/turnos',{ templateUrl: 'apps/admin/turnos/partials/index.html', controller: 'TurnoController', controllerAs: 'ctrl' })
            .when('/turnos/:id',{ templateUrl: 'apps/admin/turnos/partials/form.html', controller: 'TurnoFormController', controllerAs: 'ctrl' })
            .when('/modelos-horario',{ templateUrl: 'apps/admin/modelosGradeHorario/partials/index.html', controller: 'ModeloGradeHorarioController', controllerAs: 'ctrl' })
            .when('/modelos-horario/:id',{ templateUrl: 'apps/admin/modelosGradeHorario/partials/form.html', controller: 'ModeloGradeHorarioFormController', controllerAs: 'ctrl' })
            .when('/quadros-horario',{ templateUrl: 'apps/admin/quadroHorarios/partials/index.html', controller: 'QuadroHorarioController', controllerAs: 'ctrl' })
            .when('/quadros-horario/:id',{ templateUrl: 'apps/admin/quadroHorarios/partials/form.html', controller: 'QuadroHorarioFormController', controllerAs: 'ctrl' })
            .when('/calendarios',{ templateUrl: 'apps/admin/calendarios/partials/index.html', controller: 'CalendarioController', controllerAs: 'ctrl' })
            .when('/calendarios/:id',{ templateUrl: 'apps/admin/calendarios/partials/form.html', controller: 'CalendarioFormController', controllerAs: 'ctrl' })
            .when('/calendarios/view/:id',{ templateUrl: 'apps/admin/calendarios/partials/view.html', controller: 'CalendarioViewController', controllerAs: 'ctrl' })
            .when('/eventos',{ templateUrl: 'apps/admin/eventos/partials/index.html', controller: 'EventoController', controllerAs: 'ctrl' })
            .when('/eventos/:id',{ templateUrl: 'apps/admin/eventos/partials/form.html', controller: 'EventoFormController', controllerAs: 'ctrl' })
            .when('/historicos',{ templateUrl: 'apps/admin/historicos/partials/index.html', controller: 'HistoricoController', controllerAs: 'ctrl' })
            .when('/historicos/:id',{ templateUrl: 'apps/admin/historicos/partials/view.html', controller: 'HistoricoViewController', controllerAs: 'ctrl' })
            .when('/historicos/:id/disciplinas',{ templateUrl: 'apps/admin/historicos/partials/adicionar-disciplina.html', controller: 'HistoricoDisciplinaController', controllerAs: 'ctrl' })
            .when('/historicos/:id/dados-conclusao',{ templateUrl: 'apps/admin/historicos/partials/dados-conclusao.html', controller: 'HistoricoConclusaoController', controllerAs: 'ctrl' })
            .when('/historicos/:id/observacoes',{ templateUrl: 'apps/admin/historicos/partials/observacoes.html', controller: 'HistoricoObservacaoController', controllerAs: 'ctrl' })
            .when('/pessoas',{ templateUrl: 'apps/admin/pessoas/partials/index.html', controller: 'PessoaController', controllerAs: 'ctrl' })
            .when('/pessoas/:id',{ templateUrl: 'apps/admin/pessoas/partials/stepper.html', controller: 'PessoaFormController', controllerAs: 'ctrl' })
            .when('/boletins',{ templateUrl: 'apps/admin/boletins/partials/index.html', controller: 'BoletimController', controllerAs: 'ctrl' })
            .when('/ata-final',{ templateUrl: 'apps/admin/ataFinal/partials/index.html', controller: 'AtaController', controllerAs: 'ctrl' })
            .when('/certificados',{ templateUrl: 'apps/admin/certificados/partials/index.html', controller: 'CertificadoController', controllerAs: 'ctrl' })
            .when('/diario-frequencias',{ templateUrl: 'apps/admin/diariosFrequencia/partials/index.html', controller: 'DiarioFrequenciaController', controllerAs: 'ctrl' })
            .when('/diario-notas',{ templateUrl: 'apps/admin/diariosNotas/partials/index.html', controller: 'DiarioNotasController', controllerAs: 'ctrl' })
            .when('/rematricula',{ templateUrl: 'apps/admin/fichasRematricula/partials/index.html', controller: 'FichaRematriculaController', controllerAs: 'ctrl' })
            .when('/ieducar',{ templateUrl: 'apps/admin/ieducar/partials/index.html', controller: 'IeducarController', controllerAs: 'ctrl' })
            .when('/alunos-defasados',{ templateUrl: 'apps/admin/ieducar/partials/index.html', controller: 'AlunosDefasadosController', controllerAs: 'ctrl' })
            .when('/alunos-enturmados',{ templateUrl: 'apps/admin/alunosEnturmados/partials/index.html', controller: 'AlunosEnturmadosController', controllerAs: 'ctrl' })
            .when('/alunos-anee',{ templateUrl: 'apps/admin/alunosANEE/partials/index.html', controller: 'AlunosANEEController', controllerAs: 'ctrl' })
            .when('/perfil',{ templateUrl: 'apps/professor/perfil/partials/index.html', controller: 'PerfilController', controllerAs: 'ctrl' })
            .when('/teste',{ templateUrl: 'apps/admin/teste/partials/teste.html', controller: 'TESTE', controllerAs: 'ctrl' })
            .otherwise({ redirectTo: '/404' })
        ;
        
        //DEFININDO TRADUÇÕES
        $translateProvider.useStaticFilesLoader({ prefix:ErudioConfigProvider.$get().dominio+'/apps/admin/util/translations/locale-', suffix: '.json' });
        $translateProvider.translations('en'); 
        $translateProvider.translations('ptbr');
        $translateProvider.preferredLanguage('ptbr');
        
        //DEFININDO URL DO SERVIDOR REST
        RestangularProvider.setBaseUrl(ErudioConfigProvider.$get().urlServidor);
    }]);
    
    erudio.controller('AppController',['$scope', '$http', '$mdSidenav', '$mdDialog', 'Util', '$translate', '$timeout', 'ErudioConfig', 'BaseService', function($scope, $http, $mdSidenav, $mdDialog, Util, $translate, $timeout, ErudioConfig, BaseService){
        
        //SE NÃO TEM SESSÃO, REDIRECIONA PRO LOGIN
        if (!sessionStorage.getItem('autenticado')) { window.location = ErudioConfig.dominio + '/login.html'; }
        
        //TITULO DA TOOLBAR
        $scope.titulo = 'Erudio';
        $scope.foto = null;
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

        //BUSCA FOTO
        $scope.carregarFoto = function () {
            var atribuicoes = JSON.parse(sessionStorage.getItem('atribuicoes'));
            var token = "Bearer "+sessionStorage.getItem('token');
            var fileUrl = ErudioConfig.urlServidor+'/pessoas/'+atribuicoes.pessoa.id+'/foto';
            return $http.get(fileUrl,{headers: {"JWT-Authorization":token},responseType: 'arraybuffer'}).then((data) => {
                return new Promise((resolve) => {
                    var file = new Blob([data.data],{type: 'image/jpg'}); resolve(URL.createObjectURL(file));
                });
            }, (error) => { return new Promise((resolve) => { resolve(ErudioConfig.dominio+"/apps/professor/avaliacoes/assets/images/avatar.png"); }) });
        };
        $scope.carregarFoto().then((foto) => { $scope.foto = foto; });
                
        //CARREGAR ABTRIBUICAO
        $scope.carregarAtribuicao = function (id) { 
            $scope.atribuicoes.atribuicoes.forEach(function(attr){ if (attr.id === parseInt(id)) { sessionStorage.setItem('atribuicao-ativa',JSON.stringify(attr)); } });
            $timeout(function(){ location.reload(); },500);
        };
                
        //CRIANDO LABELS DOS MENUS
        var menuLabels = ['INSTITUICOES_UNIDADES','INSTITUICOES','TIPOS_UNIDADE','UNIDADES_ENSINO',"GESTAO","REGIMES_ENSINO","CURSOS","ETAPAS","DISCIPLINAS",
            "MODULOS","TURNOS","MODELOS_HORARIO","QUADROS_HORARIO","CALENDARIOS","EVENTOS","VIDA_ESCOLAR","TURMAS","MATRICULAS","MOVIMENTACOES","NOTAS",
            "PESSOAS_TITULO","PESSOAS","CARGOS","FUNCIONARIOS","AVALIACOES_TITLE","TIPOS_AVALIACAO","HABILIDADES","DOCUMENTOS","BOLETIM",
            "DIARIO_FREQUENCIA","DIARIO_NOTAS","RELATORIOS","ALUNOS_DEFASADOS","ATA_FINAL","CERTIFICADOS","REMATRICULA","HISTORICO","ALUNOS_ENTURMADOS","ESPELHO_NOTA","ADMINISTRADOR", "USUARIOS","PERMISSOES","GRUPOS_PERMISSOES",
            "PERFIL","SOBRE","MUDOU","CONFIG","SAIR","IEDUCAR","ANEE"];
        
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
                        ]
                    },
                    { 
                        categoria: traducoes.DOCUMENTOS, temSubmenu : true,
                        links: [ 
                            { label: traducoes.BOLETIM, href: "#!/boletins", permissao: 'BOLETIM_ESCOLAR' },
                            { label: traducoes.ATA_FINAL, href: "#!/ata-final", permissao: 'RELATORIOS' },
                            { label: traducoes.CERTIFICADOS, href: "#!/certificados", permissao: 'RELATORIOS' },
                            { label: traducoes.DIARIO_FREQUENCIA, href: "#!/diario-frequencias", permissao: 'DIARIO_FREQUENCIA' },
                            { label: traducoes.DIARIO_NOTAS, href: "#!/diario-notas", permissao: 'RELATORIOS' },
                            { label: traducoes.REMATRICULA, href: "#!/rematricula", permissao: 'RELATORIOS' },
                            { label: traducoes.HISTORICO, href: "#!/historicos", permissao: 'RELATORIOS' },
                            { label: traducoes.IEDUCAR, href: "#!/ieducar", permissao: 'RELATORIOS' },
                        ]
                    },
                    { 
                        categoria: traducoes.RELATORIOS, temSubmenu : true,
                        links: [ 
                            { label: traducoes.ALUNOS_DEFASADOS, href: "#!/alunos-defasados", permissao: 'RELATORIOS' },
                            { label: traducoes.ALUNOS_ENTURMADOS, href: "#!/alunos-enturmados", permissao: 'RELATORIOS' },
                            { label: traducoes.ANEE, href: "#!/alunos-anee", permissao: 'RELATORIOS' },
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

        $scope.redirect = function (url){ window.location = ErudioConfig.dominio + url; };
        
        //VERIFICA PERMISSÃO
        $scope.verificaPermissao = function (role) { return Util.verificaPermissao(role); };

        //CONTROLE DE MENU
        $scope.abrirMenu = function () { $mdSidenav('left').toggle(); }; $scope.menuAberto = null;
        $scope.menuToggle = function (index) { if (index !== $scope.menuAberto) { $('.submenu').hide(); } $scope.menuAberto = index; $(".submenu"+index).slideToggle(150); };
        
        //ABRINDO MODAL O QUE MUDOU
        $scope.oQueMudou = function (event) { Util.modal(event, 'apps/admin/templates/partials/oQueMudou.html', $mdDialog); };
        
        //TELA CHEIA
        $scope.ajustarTelaCheia = function () {
            if ($scope.isProfessor) { return 'tela-cheia'; } else { return ''; }
        };

        $scope.logout = function(){ window.location = ErudioConfig.dominio + '/login.html' };

        //ELIMINANDO MENUS VAZIOS
        angular.element(document).ready(function(){ $timeout(function(){ Util.ajustaMenus(); },500); });

        //PEGANDO DISCIPLINAS LECIONADAS
        if (!Util.isVazio(sessionStorage.getItem('atribuicao-ativa'))) {
            $scope.isProfessor = false;
            var ativa = JSON.parse(sessionStorage.getItem('atribuicao-ativa'));
            if (ativa.grupo.nome === "Professor") { 
                $scope.isProfessor = true;
            }
        }
        
    }]);
})();