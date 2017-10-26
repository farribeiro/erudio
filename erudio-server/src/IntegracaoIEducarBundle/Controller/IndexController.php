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

namespace IntegracaoIEducarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class IndexController {
    
    const URL_SERVICO = 'https://intranet.itajai.sc.gov.br/educar_relatorio_historico_escolar_service.php';
    
    function __construct() {
        
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Integração I-Educar",
    *   description = "Histórico consolidado do I-Educar",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("historicos-legados", defaults={ "_format" = "pdf" })
    */
    function getHistoricoConsolidadoAction(Request $request) {
        try {
            $unidadesEnsino = $request->query->getInt('unidadeEnsino');
            $aluno = $request->query->getInt('aluno');
            $options = ['http' => [
                'method' => 'GET',
                'header' => 'Accept: application/pdf'
            ]];
            $context = stream_context_create($options);
            $historico = file_get_contents(
                self::URL_SERVICO . "?ws=his&alu={$aluno}&esc={$unidadesEnsino}",
                false,
                $context
            );
            return new Response($historico);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
}
