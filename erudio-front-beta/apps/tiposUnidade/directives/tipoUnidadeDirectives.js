(function (){
    var tipoUnidadeDirectives = angular.module('tipoUnidadeDirectives', []);
    tipoUnidadeDirectives.directive('inputTipoUnidade', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'tipoUnidade.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'tipoUnidadeForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'instituicao.nome':
                        $attributes.id = 'tipoUnidade_nome';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'instituicao.sigla':
                        $attributes.id = 'tipoUnidade_sigla';
                        $attributes.label = 'Sigla';
                        $attributes.icone = 'text_format';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
})();