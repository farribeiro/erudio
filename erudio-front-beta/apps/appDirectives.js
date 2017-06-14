(function (){
    var appDirectives = angular.module('appDirectives', []);
    
    appDirectives.directive('botaoAdicionar', function (){ return { restrict: 'E', templateUrl: 'apps/templates/partials/botaoAdicionar.html' }; });
    appDirectives.directive('botaoVoltar', function (){ return { restrict: 'E', templateUrl: 'apps/templates/partials/botaoVoltar.html' }; });
    appDirectives.directive('paginador', function (){ return { restrict: 'E', templateUrl: 'apps/templates/partials/paginador.html' }; });
    appDirectives.directive('opcaoLista', function (){ return { restrict: 'E', templateUrl: 'apps/templates/partials/opcaoLista.html' }; });
    appDirectives.directive('autocomplete', function (){ return { restrict: 'E', templateUrl: 'apps/templates/partials/autocomplete.html' }; });
    
    appDirectives.directive('formInput', function ($compile){ return { 
            restrict: 'E', 
            scope: {
                grid: '=grid', label: '=label', tipo: '=tipo', nome: '=nome', obrigatorio: '=obrigatorio', colunas: '=colunas', dica: '=dica', model: '=', campo: '@', mensagem: '=mensagem', troca: '=',
                padrao: '=padrao', minimo: '=minimo', maximo: '=maximo', identidade: '=identidade', desabilitado: '=desabilitado', icone: '=icone', classe: '=classe', erro: '=erro'
            },
            compile: function ($element, $attributes) {
                function isVazio (value) { if (value === null || value === undefined || value === "") { return true; } else { return false; } };
                var tipo = $attributes.tipo.replace(/'/g,"");
                //ID
                $element[0].children[0].id = $attributes.identidade;
                //LAYOUT
                if (!isVazio($attributes.grid)) { $('#'+$attributes.identidade).attr($attributes.grid,""); }
                //REQUIRED
                if ($attributes.obrigatorio === "required") { $('#'+$attributes.identidade).find('input').prop("required",true); }
                //DISABLE
                if (!isVazio($attributes.desabilitado)) { $('#'+$attributes.identidade).find('input').prop("disabled",true); }
                //MIN-LENGTH
                if (!isVazio($attributes.minimo)) { $('#'+$attributes.identidade).find('input').attr("md-minlength",$attributes.minimo); }
                //MAX_LENGTH
                if (!isVazio($attributes.maximo)) { $('#'+$attributes.identidade).find('input').attr("md-maxlength",$attributes.maximo); }
                //ICON
                if (!isVazio($attributes.icone)) { $('#'+$attributes.identidade).addClass("md-icon-float").addClass('md-icon-left'); }
                //CLASSE
                if (!isVazio($attributes.classe)) { var classe = $attributes.classe.replace(/'/g,""); $('#'+$attributes.identidade).find('input').attr('class',classe); }
                //PADRAO
                if (!isVazio($attributes.padrao)) { var padrao = $attributes.padrao.replace(/'/g,""); $('#'+$attributes.identidade).find('input').attr('ng-pattern',padrao); }
                //TROCA
                if (!isVazio($attributes.troca)) { $('#'+$attributes.identidade).find('input').attr('ng-change',$attributes.troca); }
                //ERROR
                if (!isVazio($attributes.erro)) { 
                    var erro = $attributes.erro.replace(/'/g,""); var elem = $('<div></div>');
                    elem.attr('ng-messages',erro); elem.addClass('messages-container'); $('#'+$attributes.identidade).find('.messages-wrapper').append(elem);
                }
                //MENSAGENS DE ERRO
                if (!isVazio($attributes.mensagem)){
                    var msgs = $attributes.mensagem.replace(/'/g,""); var arrayMsgs = msgs.split("|");
                    for (var i=0; i<arrayMsgs.length; i++) {
                        var msg = arrayMsgs[i].split(","); var elem = $("<div></div>"); var message = 'ng-message'; if (msg[0] === "pattern") { message = 'ng-message-exp'; }
                        elem.attr(message,msg[0]); elem.addClass('message-list'); elem.html(msg[1]); $('#'+$attributes.identidade).find('.messages-container').append(elem);
                    }
                }
                
                if (tipo === 'text') {
                    //PÀTTERN
                    //if (!isVazio($attributes.padrao)) { var exp = $attributes.padrao.replace(/'/g,""); $('#'+$attributes.identidade).find(input).attr("ng-pattern",exp); }
                } else if (tipo === 'textarea') {
                    //ROWS
                    if (!isVazio($attributes.colunas)) { $('#'+$attributes.identidade).find("textarea").attr("rows",$attributes.colunas); }
                }
            },
            templateUrl: 'apps/templates/partials/input.html' }; 
    });
    
    appDirectives.directive('formSelect', function ($compile){ return {
            restrict: 'E', 
            scope: {
                grid: '=grid', label: '=label', nome: '=nome', identidade: '=identidade', desabilitado: '=desabilitado', dica: '=dica', icone: '=icone'
            },
            transclude: true,
            compile: function ($element, $attributes) {
                function isVazio (value) { if (value === null || value === undefined || value === "") { return true; } else { return false; } };
                //ID
                $element[0].children[0].id = $attributes.identidade;
                //LAYOUT
                if (!isVazio($attributes.grid)) { $('#'+$attributes.identidade).attr($attributes.grid,""); }
                //DISABLE
                if (!isVazio($attributes.desabilitado)) { $('#'+$attributes.identidade).find("md-select").prop("disabled",true); }
                //ICON
                if (!isVazio($attributes.icone)) { $('#'+$attributes.identidade).addClass("md-icon-float").addClass('md-icon-left'); }
                //ERROR
                if (!isVazio($attributes.erro)) { 
                    var erro = $attributes.erro.replace(/'/g,""); var elem = $('<div></div>');
                    elem.attr('ng-messages',erro); elem.addClass('messages-container'); $('#'+$attributes.identidade).find('.messages-wrapper').append(elem);
                }
                //MENSAGENS DE ERRO
                if (!isVazio($attributes.mensagem)){
                    var msgs = $attributes.mensagem.replace(/'/g,""); var arrayMsgs = msgs.split("|");
                    for (var i=0; i<arrayMsgs.length; i++) {
                        var msg = arrayMsgs[i].split(","); var elem = $("<div></div>"); var message = 'ng-message'; if (msg[0] === "pattern") { message = 'ng-message-exp'; }
                        elem.attr(message,msg[0]); elem.addClass('message-list'); elem.html(msg[1]); $('#'+$attributes.identidade).find('.messages-container').append(elem);
                    }
                }
            },
            templateUrl: 'apps/templates/partials/select.html' }; 
    });
    
    appDirectives.directive('formDatepicker', function ($compile){ return { 
            restrict: 'E', 
            scope: {
                grid: '=grid', label: '=label', nome: '=nome', obrigatorio: '=obrigatorio', identidade: '=identidade', desabilitado: '=desabilitado', dica: '=dica', icone: '=icone'
            },
            transclude: true,
            compile: function ($element, $attributes) {
                function isVazio (value) { if (value === null || value === undefined || value === "") { return true; } else { return false; } };
                //ID
                $element[0].children[0].id = $attributes.identidade;
                //LAYOUT
                if (!isVazio($attributes.grid)) { $('#'+$attributes.identidade).attr($attributes.grid,""); }
                //REQUIRED
                if ($attributes.obrigatorio === "required") { $('#'+$attributes.identidade).find("md-checkbox").prop("required",true); }
                //DISABLE
                if (!isVazio($attributes.desabilitado)) { $('#'+$attributes.identidade).find("md-checkbox").prop("disabled",true); }
                //ICON
                if (!isVazio($attributes.icone)) { $('#'+$attributes.identidade).addClass("md-icon-float").addClass('md-icon-left'); }
            },
            templateUrl: 'apps/templates/partials/datepicker.html' }; 
    });
})();