/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

(function (){
    var erudioConfig = angular.module('erudioConfig',[]);

    erudioConfig.service('ErudioConfig', [function () {
        this.dominio = 'http://10.100.0.143/erudio/erudio/erudio-front'; this.extraUrl = '/erudio/erudio/erudio-front';
        //this.dominio = 'http://erudio.itajai.sc.gov.br'; this.extraUrl = '';
        
        //this.urlServidor = 'http://novo.erudio.itajai.sc.gov.br/servicos/web/app_dev.php/api';
        //this.urlRelatorios = 'http://novo.erudio.itajai.sc.gov.br/servicos/web/app_dev.php/api/report';
        //this.urlUpload = 'http://novo.erudio.itajai.sc.gov.br/servicos/web/bundles/assets/uploads/';
        
        //this.urlServidor = 'http://10.100.1.134/erudio/erudio-server/web/app_dev.php/api';
        this.urlServidor = 'http://10.100.0.208/erudio/erudio-server/web/app_dev.php/api';
        //this.urlRelatorios = 'http://10.100.1.134/erudio/erudio-server/web/app_dev.php/api/report';
        this.urlRelatorios = 'http://10.100.0.208/erudio/erudio-server/web/app_dev.php/api/report';
        //this.urlUpload = 'http://10.100.1.134/erudio/erudio-server/web/bundles/assets/uploads/';
        this.urlUpload = 'http://10.100.0.208/erudio/erudio-server/web/bundles/assets/uploads/';
        
        this.urlTemplateInicio = "/app/modules/";
        this.urlTemplateFinal = "/partials/";
        
        this.getTemplateLista = function (modulo) { return this.extraUrl + this.urlTemplateInicio + modulo + this.urlTemplateFinal + "lista.html"; };
        this.getTemplateForm = function (modulo) { return this.extraUrl + this.urlTemplateInicio + modulo + this.urlTemplateFinal + "form.html"; };
        this.getTemplateCustom = function (modulo,arquivo) { return this.extraUrl + this.urlTemplateInicio + modulo + this.urlTemplateFinal + arquivo + ".html"; };
    }]);

})();
