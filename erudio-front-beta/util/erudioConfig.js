(function (){
    var erudioConfig = angular.module('erudioConfig',[]);

    erudioConfig.service('ErudioConfig', [function () {
        this.dominio = 'http://10.100.0.90/erudio/erudio-front-beta';
        this.urlTemplates = '/apps/templates/partials/';
        this.extraUrl = '/erudio-material';
        
        this.urlServidor = 'http://10.1.3.68/erudio/Erudio/erudio-server/web/app_dev.php/api';
        this.urlRelatorios = 'http://10.1.3.68/erudio/Erudio/erudio-server/web/app_dev.php/api/report';
        this.urlUpload = 'http://10.1.3.68/erudio/Erudio/erudio-server/web/bundles/assets/uploads/';
        
        //this.getTemplateLista = function (modulo) { return this.extraUrl + this.urlTemplateInicio + modulo + this.urlTemplateFinal + "lista.html"; };
        //this.getTemplateForm = function (modulo) { return this.extraUrl + this.urlTemplateInicio + modulo + this.urlTemplateFinal + "form.html"; };
        //this.getTemplateCustom = function (modulo,arquivo) { return this.extraUrl + this.urlTemplateInicio + modulo + this.urlTemplateFinal + arquivo + ".html"; };
    }]);

})();
