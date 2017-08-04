(function (){
    var cursoDirectives = angular.module('cursoDirectives', []);
    cursoDirectives.directive('inputCurso', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'curso.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'cursoForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'curso.nome':
                        $attributes.id = 'curso_nome';
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
    
    cursoDirectives.directive('selectCurso', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/select-template.html',
            scope: { type:'=', model:'=', items:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', nomeComum:'@', nomeExibicao:'@', nomeCompleto:'@', simples:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'curso.'+$attributes.model; $attributes.campoId = $attributes.model;
                if ($attributes.required === "true") { $($element[0].children[0]).find('md-select').prop("required",true); }
                switch ($attributes.model) {
                    case 'curso.modalidade.id':
                        $attributes.id = 'curso_modalidade';
                        $attributes.simples = true;
                        $attributes.label = 'Descrição';
                        $attributes.icone = 'extension';
                    break;
                }
            }
        };
    });
    
    //FALTA CHECKBOXES - alfabetizatorio e especializado
    
})();