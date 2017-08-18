(function (){
    var quadroHorarioDirectives = angular.module('quadroHorarioDirectives', []);
    quadroHorarioDirectives.directive('inputQuadroHorario', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'quadro.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'quadro.Form';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'quadro.nome':
                        $attributes.id = 'quadro_nome';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'quadro.inicio':
                        $attributes.id = 'quadro_inicio';
                        $attributes.label = 'Horário de Início';
                        $attributes.icone = 'access_time';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
  1              }
            }
        };
    });
    
    quadroHorarioDirectives.directive('selectQuadroHorario', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/select-template.html',
            scope: { type:'=', model:'=', items:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', nomeComum:'@', nomeExibicao:'@', nomeCompleto:'@', simples:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'quadro.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'quadroForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('md-select').prop("required",true); }
                switch ($attributes.model) {
                    case 'modulo.modeloQuadro.id':
                        $attributes.id = 'modulo_modeloQuadro';
                        $attributes.simples = true;
                        $attributes.label = 'Modelo de grade de hoŕario';
                        $attributes.icone = 'view_quilt';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'modulo.turno.id':
                        $attributes.id = 'modulo_turno';
                        $attributes.simples = true;
                        $attributes.label = 'Turno';
                        $attributes.icone = 'brightness_medium';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
    
    //autcomplete de unidade
})();