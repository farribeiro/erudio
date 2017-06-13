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

namespace CalendarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use JMS\Serializer\Annotation as JMS;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;

class DiaController extends AbstractEntityController {
    
    function getFacade() {
        return $this->get('facade.calendario.dias');
    }
    
    /**
    *   @ApiDoc()
    *  
    *   @FOS\Get("calendarios/{id}/dias")
    * 
    *   @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    *   @FOS\QueryParam(name = "data", nullable = true) 
    *   @FOS\QueryParam(name = "mes", requirements="\d+", nullable = true)
    *   @FOS\QueryParam(name = "efetivo", nullable = true)
    */
    function getListAction(Request $request, $id, ParamFetcherInterface $paramFetcher) {
        $params = $paramFetcher->all();
        $params['calendario'] = $id;
        return $this->getList($request, $params);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Get("calendarios/{id}/meses/{mes}")
    */
    function getByMesAction(Request $request, $id, $mes) {
        return $this->getList($request, ['calendario' => $id, 'mes' => $mes]);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Put("calendarios/{id}/dias")
    *  @ParamConverter("dias", converter="fos_rest.request_body")
    */
    function putBatchAction(Request $request, $id, DiaCollection $dias, ConstraintViolationListInterface $errors) {
        return $this->putBatch($request, $dias->dias, $errors);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Get("dias/atual")
    */
    function getDataAtualAction() {
        $dateTime = new \DateTime();
        $dataAtual = date_format($dateTime, 'Y-m-d');
        $view = View::create($dataAtual, Response::HTTP_OK);
        return $this->handleView($view);   
    }
    
}

/**  Wrapper class for batch operations*/
class DiaCollection {
    
    /** @JMS\Type("ArrayCollection<CalendarioBundle\Entity\Dia>") */
    public $dias;
    
}
