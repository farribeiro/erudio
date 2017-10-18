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
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use CalendarioBundle\Entity\DiaEvento;
use CalendarioBundle\Service\DiaEventoFacade;

class DiaEventoController extends AbstractEntityController {
    
    function __construct(DiaEventoFacade $facade) {
        parent::__construct($facade);
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Get("calendarios/{calendario}/dias/{dia}/eventos/{id}")
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
     /**
    *   @ApiDoc()
    * 
    *   @FOS\Get("calendarios/{calendario}/dias/{dia}/eventos")
    */
    function getByDiaAction(Request $request, $dia) {
        return $this->getList($request, ['dia' => $dia]);
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Post("calendarios/{calendario}/dias/{dia}/eventos")
    *   @ParamConverter("evento", converter="fos_rest.request_body")
    */
    function postAction(Request $request, DiaEvento $evento, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->create($evento);
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Put("calendarios/{calendario}/dias/{dia}/eventos/{id}")
    *   @ParamConverter("evento", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, DiaEvento $evento, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->update($id, $evento);
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Delete("calendarios/{calendario}/dias/{dia}/eventos/{id}")
    */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }

}
