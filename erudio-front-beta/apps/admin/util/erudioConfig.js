(function (){
    /*
     * @Module erudioConfig
     * @Service ErudioConfig
     */
    var erudioConfig = angular.module('erudioConfig',[]);

    erudioConfig.service('ErudioConfig', [function () {
        /*
         * @attr dominio
         * @attrType string
         * @attrDescription O endereço acessado no navegador.
         * @attrExample http://erudio.itajai.sc.gov.br
         */
        this.dominio = 'http://10.100.0.143/erudio/erudio/erudio-front-beta';
        
        /*
         * @attr urlTemplates
         * @attrType string
         * @attrDescription O endereço interno dos arquivos base.
         * @attrExample /apps/admin/templates/partials/
         */
        this.urlTemplates = '/apps/admin/templates/partials/';
        
        /*
         * @attr extraUrl
         * @attrType string
         * @attrDescription Endereço utilizado para carregar alguns templates específicos.
         * @attrExample /erudio/erudio-front-beta
         */
        this.extraUrl = '/erudio/erudio-front-beta';
        
        /*
         * @attr urlServidor
         * @attrType string
         * @attrDescription Endereço do servidor REST para obtenção de dados.
         * @attrExample http://erudio.itajai.sc.gov.br/servicos/web/api
         */
        //this.urlServidor = 'http://10.1.11.7/erudio/erudio-server/web/app_dev.php/api';
        //this.urlServidor = 'http://10.1.11.7/app_dev.php/api';
        this.urlServidor = 'http://10.100.1.134/erudio/erudio-server/web/app_dev.php/api';
        
        /*
         * @attr urlRelatorios
         * @attrType string
         * @attrDescription Endereço do servidor REST para geração de relatórios.
         * @attrExample http://erudio.itajai.sc.gov.br/servicos/web/api/reports
         */
        //this.urlRelatorios = 'http://10.1.11.7/erudio/erudio-server/web/app_dev.php/api/report';
        //this.urlRelatorios = 'http://10.1.11.7/app_dev.php/api/report';
        this.urlRelatorios = 'http://10.100.1.134/erudio/erudio-server/web/app_dev.php/api/report';
        
        /*
         * @attr urlUpload
         * @attrType string
         * @attrDescription Endereço do servidor REST para uploado de arquivos.
         * @attrExample http://erudio.itajai.sc.gov.br/servicos/web/bundles/assets/uploads
         */
        //this.urlUpload = 'http://10.1.11.7/erudio/erudio-server/web/bundles/assets/uploads/';
        //this.urlUpload = 'http://10.1.11.7/web/bundles/assets/uploads/';
        this.urlUpload = 'http://10.100.1.134/erudio/erudio-server/web/bundles/assets/uploads/';
    }]);

})();
