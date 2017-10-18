(function (){
    var disciplinaDirectives = angular.module('disciplinaDirectives', []);
    disciplinaDirectives.directive('inputDisciplina', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'etapa.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'disciplinaForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'disciplina.nome':
                        $attributes.id = 'disciplina_nome';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'disciplina.nomeExibicao':
                        $attributes.id = 'disciplina_nomeExibicao';
                        $attributes.label = 'Nome de Exibição';
                        $attributes.icone = 'assignment_ind';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'disciplina.cargaHoraria':
                        $attributes.id = 'disciplina_cargaHoraria';
                        $attributes.label = 'Ordem';
                        $attributes.icone = 'list';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
    
    //FALTA CHECKBOXES - disciplina opcional
    
})();