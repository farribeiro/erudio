(function (){
    var turnoDirectives = angular.module('turnoDirectives', []);
    turnoDirectives.directive('inputTurno', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'turno.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'turnoForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'turno.nome':
                        $attributes.id = 'turno_nome';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'turno.inicio':
                        $attributes.id = 'turno_inicio';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'access_time';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'turno.termino':
                        $attributes.id = 'turno_termino';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'access_time';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
})();