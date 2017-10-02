(function (){
    var habilidadeDirectives = angular.module('habilidadeDirectives', []);
    habilidadeDirectives.directive('inputHabilidade', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'habilidade.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'habilidadeForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'habilidade.nome':
                        $attributes.id = 'habilidade_nome';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'habilidade.media':
                        $attributes.id = 'habilidade_media';
                        $attributes.label = 'Média';
                        $attributes.icone = 'filter_2';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
    
    habilidadeDirectives.directive('selectHabilidade', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/select-template.html',
            scope: { type:'=', model:'=', items:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', nomeComum:'@', nomeExibicao:'@', nomeCompleto:'@', simples:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'habilidade.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'habilidadeForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('md-select').prop("required",true); }
                switch ($attributes.model) {
                    case 'habilidade.curso.id':
                        $attributes.id = 'habilidade_curso';
                        $attributes.simples = true;
                        $attributes.label = 'Curso';
                        $attributes.icone = 'school';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'habilidade.etapa.id':
                        $attributes.id = 'habilidade_etapa';
                        $attributes.simples = true;
                        $attributes.label = 'Etapa';
                        $attributes.icone = 'reorder';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'habilidade.disciplina.id':
                        $attributes.id = 'habilidade_disciplina';
                        $attributes.simples = true;
                        $attributes.label = 'Disciplina';
                        $attributes.icone = 'chrome_reader_mode';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'habilidade.sistemaAvaliacao.id':
                        $attributes.id = 'habilidade_sistemaAvaliacao';
                        $attributes.simples = true;
                        $attributes.label = 'Sistema de Avaliação';
                        $attributes.icone = 'receipt';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
})();