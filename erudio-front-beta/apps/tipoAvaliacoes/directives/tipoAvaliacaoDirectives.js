(function (){
    var tipoAvaliacaoDirectives = angular.module('tipoAvaliacaoDirectives', []);
    tipoAvaliacaoDirectives.directive('inputTipoAvaliacao', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'tipoAvaliacao.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'tipoAvaliacaoForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'tipoAvaliacao.nome':
                        $attributes.id = 'tipoAvaliacao_nome';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
})();