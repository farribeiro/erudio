(function (){
    var etapaDirectives = angular.module('etapaDirectives', []);
    etapaDirectives.directive('inputEtapa', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'etapa.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'etapaForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'etapa.nome':
                        $attributes.id = 'etapa_nome';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'etapa.nomeExibicao':
                        $attributes.id = 'etapa_nomeExibicao';
                        $attributes.label = 'Nome de Exibição';
                        $attributes.icone = 'assignment_ind';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'etapa.ordem':
                        $attributes.id = 'etapa_ordem';
                        $attributes.label = 'Ordem';
                        $attributes.icone = 'list';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'etapa.limiteAlunos':
                        $attributes.id = 'etapa_limiteAlunos';
                        $attributes.label = 'Limite de Alunos';
                        $attributes.icone = 'crop_free';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
    
    etapaDirectives.directive('selectEtapa', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/select-template.html',
            scope: { type:'=', model:'=', items:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', nomeComum:'@', nomeExibicao:'@', nomeCompleto:'@', simples:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'etapa.'+$attributes.model; $attributes.campoId = $attributes.model;
                if ($attributes.required === "true") { $($element[0].children[0]).find('md-select').prop("required",true); }
                switch ($attributes.model) {
                    case 'etapa.modulo.id':
                        $attributes.id = 'curso_modulo';
                        $attributes.simples = true;
                        $attributes.label = 'Módulo';
                        $attributes.icone = 'extension';
                    break;
                    case 'etapa.sistemaAvaliacao.id':
                        $attributes.id = 'curso_sistemaAvaliacao';
                        $attributes.simples = true;
                        $attributes.label = 'Sistema de Avaliação';
                        $attributes.icone = 'receipt';
                    break;
                    case 'etapa.modeloQuadroHorario.id':
                        $attributes.id = 'curso_modeloQuadroHorario';
                        $attributes.simples = true;
                        $attributes.label = 'Modelo de Quadro de Horário';
                        $attributes.icone = 'view_quilt';
                    break;
                }
            }
        };
    });
    
    //FALTA CHECKBOXES - etapa integral
    
})();