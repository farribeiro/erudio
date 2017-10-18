(function (){
    var turmaDirectives = angular.module('turmaDirectives', []);
    turmaDirectives.directive('inputTurma', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'turma.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'turmaForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'turma.nome':
                        $attributes.id = 'turma_nome';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'turma.limiteAlunos':
                        $attributes.id = 'turma_limiteAlunos';
                        $attributes.label = 'Limite de alunos';
                        $attributes.icone = 'crop_free';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'turma.apelido':
                        $attributes.id = 'turma_apelido';
                        $attributes.label = 'Apelido';
                        $attributes.icone = 'account_box';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
    
    turmaDirectives.directive('selectTurma', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/select-template.html',
            scope: { type:'=', model:'=', items:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', nomeComum:'@', nomeExibicao:'@', nomeCompleto:'@', simples:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'turma.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'turmaForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('md-select').prop("required",true); }
                switch ($attributes.model) {
                    case 'turma.curso.id':
                        $attributes.id = 'turma_curso';
                        $attributes.simples = true;
                        $attributes.label = 'Curso';
                        $attributes.icone = 'school';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'turma.etapa.id':
                        $attributes.id = 'turma_etapa';
                        $attributes.simples = true;
                        $attributes.label = 'Etapa';
                        $attributes.icone = 'reorder';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'turma.turno.id':
                        $attributes.id = 'turma_turno';
                        $attributes.simples = true;
                        $attributes.label = 'Turno';
                        $attributes.icone = 'brightness_medium';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'turma.calendario.id':
                        $attributes.id = 'turma_calendario';
                        $attributes.simples = true;
                        $attributes.label = 'Calendário';
                        $attributes.icone = 'event';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'turma.quadroHorario.id':
                        $attributes.id = 'turma_quadroHorario';
                        $attributes.simples = true;
                        $attributes.label = 'Quadro de Horário';
                        $attributes.icone = 'view_quilt';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
    
    //falta autocomplete - unidade
})();