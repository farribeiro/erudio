(function (){
    var avaliacaoDirectives = angular.module('avaliacaoDirectives', []);
    avaliacaoDirectives.directive('inputModulo', function (){ return {
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'avaliacao.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'avaliacaoForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'avaliacao.nome':
                        $attributes.id = 'avaliacao';
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

    avaliacaoDirectives.directive('selectAvaliacoes', function (){ return {
            restrict: 'E',
            templateUrl: 'apps/templates/partials/select-template.html',
            scope: { type:'=', model:'=', items:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', nomeComum:'@', nomeExibicao:'@', nomeCompleto:'@', simples:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'avaliacao.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'avaliacaoForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('md-select').prop("required",true); }
                switch ($attributes.model) {
                    case 'avaliacao.tipo.id':
                        $attributes.id = 'avaliacao_tipo';
                        $attributes.simples = true;
                        $attributes.label = 'Tipo de Avaliação';
                        $attributes.icone = 'school';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'avaliacao.curso.id':
                        $attributes.id = 'avaliacao_curso';
                        $attributes.simples = true;
                        $attributes.label = 'Curso';
                        $attributes.icone = 'school';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'avaliacao.etapa.id':
                        $attributes.id = 'avaliacao_etapa';
                        $attributes.simples = true;
                        $attributes.label = 'Etapa';
                        $attributes.icone = 'reorder';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'avaliacao.disciplina.id':
                        $attributes.id = 'avaliacao_disciplina';
                        $attributes.simples = true;
                        $attributes.label = 'Disciplina';
                        $attributes.icone = 'chrome_reader_mode';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'avaliacao.turma.id':
                        $attributes.id = 'avaliacao_turma';
                        $attributes.simples = true;
                        $attributes.label = 'Turma';
                        $attributes.icone = 'group';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
})();