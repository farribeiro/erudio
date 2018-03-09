(function (){
    var modelosGradeHorarioDirectives = angular.module('modelosGradeHorarioDirectives', []);
    modelosGradeHorarioDirectives.directive('inputModeloGradeHorario', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'modelo.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'modeloForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'modelo.nome':
                        $attributes.id = 'modelo_nome';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'modelo.quantidadeAulas':
                        $attributes.id = 'modelo_quantidadeAulas';
                        $attributes.label = 'Quantidade de aulas(turno)';
                        $attributes.icone = 'filter_1';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'modelo.duracao':
                        $attributes.id = 'modelo_duracao';
                        $attributes.label = 'Duração da aula(minutos)';
                        $attributes.icone = 'access_alarm';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'modelo.duracaoIntervalo':
                        $attributes.id = 'modelo_duracaoIntervalo';
                        $attributes.label = 'Duração do intervalo(minutos)';
                        $attributes.icone = 'access_alarm';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'modelo.aulasAntes':
                        $attributes.id = 'modelo_aulasAntes';
                        $attributes.label = 'Aulas antes do intervalo(qtde)';
                        $attributes.icone = 'filter_3';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
    
    modelosGradeHorarioDirectives.directive('selectModeloGradeHorario', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/select-template.html',
            scope: { type:'=', model:'=', items:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', nomeComum:'@', nomeExibicao:'@', nomeCompleto:'@', simples:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'modelo.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'modeloForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('md-select').prop("required",true); }
                switch ($attributes.model) {
                    case 'modelo.curso.id':
                        $attributes.id = 'modelo_curso';
                        $attributes.simples = true;
                        $attributes.label = 'Curso';
                        $attributes.icone = 'school';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
})();