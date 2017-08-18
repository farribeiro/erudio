(function (){
    var eventoDirectives = angular.module('eventoDirectives', []);
    eventoDirectives.directive('inputEvento', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'evento.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'eventoForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'evento.nome':
                        $attributes.id = 'evento_nome';
                        $attributes.label = 'Título';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'evento.descricao':
                        $attributes.id = 'evento_descricao';
                        $attributes.label = 'Descrição';
                        $attributes.icone = 'view_headline';
                    break;
                }
            }
        };
    });
    
    eventoDirectives.directive('selectEvento', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/select-template.html',
            scope: { type:'=', model:'=', items:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', nomeComum:'@', nomeExibicao:'@', nomeCompleto:'@', simples:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'evento.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'eventoForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('md-select').prop("required",true); }
                switch ($attributes.model) {
                    case 'evento.tipo.id':
                        $attributes.id = 'modulo_tipoEvento';
                        $attributes.simples = true;
                        $attributes.label = 'Tipo de Evento';
                        $attributes.icone = 'insert_invitation';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
})();