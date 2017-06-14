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

namespace VinculoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use VinculoBundle\Entity\Vinculo;
use VinculoBundle\Service\VinculoFacade;

/**
 * @FOS\NamePrefix("vinculos")
 */
class VinculoController extends AbstractEntityController {
    
    private $vinculoFacade;
    
    function __construct(VinculoFacade $vinculoFacade) {
        $this->vinculoFacade = $vinculoFacade;
    }
    
    function getFacade() {
        return $this->vinculoFacade;
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("vinculos/{id}")
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("vinculos")
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    * @FOS\QueryParam(name = "cargo", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "professor", nullable = true) 
    * @FOS\QueryParam(name = "status", nullable = true) 
    * @FOS\QueryParam(name = "funcionario", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "funcionario_nome", nullable = true) 
    * @FOS\QueryParam(name = "funcionario_cpf", nullable = true)
    * @FOS\QueryParam(name = "codigo", nullable = true) 
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Post("vinculos")
    *  @ParamConverter("vinculo", converter="fos_rest.request_body")
    */
    function postAction(Request $request, Vinculo $vinculo, ConstraintViolationListInterface $errors) {
        return $this->post($request, $vinculo, $errors);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Put("vinculos/{id}")
    *  @ParamConverter("vinculo", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, Vinculo $vinculo, ConstraintViolationListInterface $errors) {
        return $this->put($request, $id, $vinculo, $errors);
    }

}
