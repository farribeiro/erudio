<?php

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

namespace IntegracaoRHBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Psr\Log\LoggerInterface;
use VinculoBundle\Service\CargoFacade;
use VinculoBundle\Service\VinculoFacade;
use VinculoBundle\Service\AlocacaoFacade;

class IndexController extends Controller {
    
    private $cargoFacade;
    private $vinculoFacade;
    private $alocacaoFacade;
    private $logger;
    
    public function __construct(CargoFacade $cargoFacade, VinculoFacade $vinculoFacade, 
            AlocacaoFacade $alocacaoFacade, LoggerInterface $logger) {
        $this->cargoFacade = $cargoFacade;
        $this->vinculoFacade = $vinculoFacade;
        $this->alocacaoFacade = $alocacaoFacade;
        $this->logger = $logger;
    }

    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Integração RH",
    *   description = "Sincronização de funcionários com sistema de RH",
    *   statusCodes = {
    *       204 = "Processo ocorreu com êxito"
    *   }
    * )
    * 
    * @Route("sincronizacao", defaults={ "_format" = "json" })
    * @Method({"POST","HEAD"})
    */
    function postSincronizacao(Request $request) {
        
        return new Response('', 204);
    }
    
}
