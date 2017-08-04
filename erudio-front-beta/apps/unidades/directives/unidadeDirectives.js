(function (){
    var unidadeDirectives = angular.module('unidadeDirectives', []);
    unidadeDirectives.directive('inputUnidade', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'unidade.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'unidadeForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'unidade.nome':
                        $attributes.id = 'unidade';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'unidade.sigla':
                        $attributes.id = 'unidade';
                        $attributes.label = 'Sigla';
                        $attributes.icone = 'text_format';
                    break;
                    case 'unidade.cpfCnpj':
                        $attributes.id = 'unidade';
                        $attributes.label = 'CNPJ';
                        $attributes.icone = 'contacts';
                        $attributes.dica = 'Apenas Números';
                        $($element[0].children[0]).find('input').addClass('maskCNPJ');
                        $($element[0].children[0]).find('input').attr('md-maxlength',14);
                    break;
                    case 'unidade.email':
                        $attributes.id = 'unidade';
                        $attributes.label = 'E-Mail';
                        $attributes.icone = 'email';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
    
    unidadeDirectives.directive('inputUnidadeTelefone', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'telefone.'+$attributes.model; $attributes.campoId = $attributes.model;
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'telefone.numero':
                        $attributes.id = 'telefone_numero';
                        $attributes.label = 'Número com DDD';
                        $attributes.icone = 'phone';
                        $attributes.dica = 'Apenas Números';
                        $($element[0].children[0]).find('input').addClass('maskTelefone');
                    break;
                }
            }
        };
    });
    
    unidadeDirectives.directive('selectUnidadeTelefone', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/select-template.html',
            scope: { type:'=', model:'=', items:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', nomeComum:'@', nomeExibicao:'@', nomeCompleto:'@', simples:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'telefone.'+$attributes.model; $attributes.campoId = $attributes.model;
                if ($attributes.required === "true") { $($element[0].children[0]).find('md-select').prop("required",true); }
                switch ($attributes.model) {
                    case 'telefone.descricao':
                        $attributes.id = 'telefone_descricao';
                        $attributes.simples = true;
                        $attributes.label = 'Descrição';
                        $attributes.icone = 'contact_phone';
                    break;
                }
            }
        };
    });
    
    unidadeDirectives.directive('inputUnidadeEndereco', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'unidade.endereco.'+$attributes.model; $attributes.campoId = $attributes.model;
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'unidade.endereco.cep':
                        $attributes.id = 'endereco_cep';
                        $attributes.label = 'CEP';
                        $attributes.icone = 'home';
                        $attributes.dica = 'Apenas Números';
                        $($element[0].children[0]).find('input').attr('md-maxlength',8);
                    break;
                    case 'unidade.endereco.logradouro':
                        $attributes.id = 'endereco_logradouro';
                        $attributes.label = 'Logradouro';
                        $attributes.icone = 'location_on';
                    break;
                    case 'unidade.endereco.numero':
                        $attributes.id = 'endereco_numero';
                        $attributes.label = 'Número';
                        $attributes.icone = 'looks_one';
                        $attributes.dica = 'Apenas Números';
                        $($element[0].children[0]).find('input').addClass('maskNumeroCasa');
                    break;
                    case 'unidade.endereco.complemento':
                        $attributes.id = 'endereco_complemento';
                        $attributes.label = 'Complemento';
                        $attributes.icone = 'change_history';
                    break;
                    case 'unidade.endereco.bairro':
                        $attributes.id = 'endereco_bairro';
                        $attributes.label = 'Bairro';
                        $attributes.icone = 'card_travel';
                    break;
                }
            }
        };
    });
    
    unidadeDirectives.directive('selectUnidadeEndereco', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/select-template.html',
            scope: { type:'=', model:'=', items:'=', troca:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', nomeComum:'@', nomeExibicao:'@', nomeCompleto:'@', simples:'@', id:'@' },
            compile: function ($element, $attributes) {
                $attributes.model = 'unidade.endereco.'+$attributes.model; $attributes.campoId = $attributes.model;
                if ($attributes.required === "true") { $($element[0].children[0]).find('md-select').prop("required",true); }
                switch ($attributes.model) {
                    case 'unidade.endereco.cidade.estado.id':
                        $attributes.id = 'endereco_estado';
                        $attributes.nomeComum = true;
                        $attributes.label = 'Estado';
                        $attributes.icone = 'my_location';
                        $($element[0].children[0]).find('md-select').attr('ng-change','buscarCidades(instituicao.endereco.cidade.estado.id)');
                    break;
                    case 'unidade.endereco.cidade.id':
                        $attributes.id = 'endereco_cidade';
                        $attributes.nomeComum = true;
                        $attributes.label = 'Cidade';
                        $attributes.icone = 'location_city';
                    break;
                }
            }
        };
    });
})();