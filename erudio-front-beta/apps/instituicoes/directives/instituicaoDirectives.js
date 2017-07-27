(function (){
    var instituicaoDirectives = angular.module('instituicaoDirectives', []);
    instituicaoDirectives.directive('inputInstituicao', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'instituicao.'+$attributes.model; $attributes.campoId = $attributes.model;
                var formId = 'instituicoesForm';
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'instituicao.nome':
                        $attributes.id = 'instituicao_nome';
                        $attributes.label = 'Nome';
                        $attributes.icone = 'account_circle';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $attributes.errors = [{name:'required', message: 'Este campo é obrigatório.'}];
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                    case 'instituicao.sigla':
                        $attributes.id = 'instituicao_sigla';
                        $attributes.label = 'Sigla';
                        $attributes.icone = 'text_format';
                    break;
                    case 'instituicao.cpfCnpj':
                        $attributes.id = 'instituicao_cnpj';
                        $attributes.label = 'CNPJ';
                        $attributes.icone = 'contacts';
                        $attributes.dica = 'Apenas Números';
                        $($element[0].children[0]).find('input').addClass('maskCNPJ');
                        $($element[0].children[0]).find('input').attr('md-maxlength',14);
                    break;
                    case 'instituicao.email':
                        $attributes.id = 'instituicao_email';
                        $attributes.label = 'E-Mail';
                        $attributes.icone = 'email';
                        $attributes.formError = formId + '.' + $attributes.model + '.$error';
                        $($element[0].children[0]).find('.errors-wrapper').append('<div ng-message="required">Este campo é obrigatório.</div>');
                    break;
                }
            }
        };
    });
    
    instituicaoDirectives.directive('inputInstituicaoTelefone', function (){ return { 
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
    
    instituicaoDirectives.directive('selectInstituicaoTelefone', function (){ return { 
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
    
    instituicaoDirectives.directive('inputInstituicaoEndereco', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/input-template.html',
            scope: { type:'=', model:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', id:'@' },
            compile: function ($element, $attributes){
                $attributes.model = 'instituicao.endereco.'+$attributes.model; $attributes.campoId = $attributes.model;
                if ($attributes.required === "true") { $($element[0].children[0]).find('input').prop("required",true); }
                switch ($attributes.model) {
                    case 'instituicao.endereco.cep':
                        $attributes.id = 'endereco_cep';
                        $attributes.label = 'CEP';
                        $attributes.icone = 'home';
                        $attributes.dica = 'Apenas Números';
                        $($element[0].children[0]).find('input').attr('md-maxlength',8);
                    break;
                    case 'instituicao.endereco.logradouro':
                        $attributes.id = 'endereco_logradouro';
                        $attributes.label = 'Logradouro';
                        $attributes.icone = 'location_on';
                    break;
                    case 'instituicao.endereco.numero':
                        $attributes.id = 'endereco_numero';
                        $attributes.label = 'Número';
                        $attributes.icone = 'looks_one';
                        $attributes.dica = 'Apenas Números';
                        $($element[0].children[0]).find('input').addClass('maskNumeroCasa');
                    break;
                    case 'instituicao.endereco.complemento':
                        $attributes.id = 'endereco_complemento';
                        $attributes.label = 'Complemento';
                        $attributes.icone = 'change_history';
                    break;
                    case 'instituicao.endereco.bairro':
                        $attributes.id = 'endereco_bairro';
                        $attributes.label = 'Bairro';
                        $attributes.icone = 'card_travel';
                    break;
                }
            }
        };
    });
    
    instituicaoDirectives.directive('selectInstituicaoEndereco', function (){ return { 
            restrict: 'E',
            templateUrl: 'apps/templates/partials/select-template.html',
            scope: { type:'=', model:'=', items:'=', troca:'=', icone:'@', campoId:'@', label:'@', required:'@', dica:'@', formError:'@', errors:'@', pattern:'@', classe:'@', nomeComum:'@', nomeExibicao:'@', nomeCompleto:'@', simples:'@', id:'@' },
            compile: function ($element, $attributes) {
                $attributes.model = 'instituicao.endereco.'+$attributes.model; $attributes.campoId = $attributes.model;
                if ($attributes.required === "true") { $($element[0].children[0]).find('md-select').prop("required",true); }
                switch ($attributes.model) {
                    case 'instituicao.endereco.cidade.estado.id':
                        $attributes.id = 'endereco_estado';
                        $attributes.nomeComum = true;
                        $attributes.label = 'Estado';
                        $attributes.icone = 'my_location';
                        $($element[0].children[0]).find('md-select').attr('ng-change','buscarCidades(instituicao.endereco.cidade.estado.id)');
                    break;
                    case 'instituicao.endereco.cidade.id':
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