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

namespace QuadroHorarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use QuadroHorarioBundle\Entity\QuadroHorario;
use QuadroHorarioBundle\Service\QuadroHorarioFacade;

/**
 * @FOS\NamePrefix("quadros-horarios")
 */
class QuadroHorarioController extends AbstractEntityController {
    
    function __construct(QuadroHorarioFacade $facade) {
        parent::__construct($facade);
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Get("quadros-horarios/{id}")
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    *   @ApiDoc()
    *  
    *   @FOS\Get("quadros-horarios")
    *   @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    *   @FOS\QueryParam(name = "nome", nullable = true)
    *   @FOS\QueryParam(name = "unidadeEnsino", requirements="\d+", nullable = true)
    *   @FOS\QueryParam(name = "turno", requirements="\d+", nullable = true) 
    *   @FOS\QueryParam(name = "modelo_curso", requirements="\d+", nullable = true)
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Post("quadros-horarios")
    * @ParamConverter("quadroHorario", converter="fos_rest.request_body")
    */
    function postAction(Request $request, QuadroHorario $quadroHorario, ConstraintViolationListInterface $errors) {
        return $this->post($request, $quadroHorario, $errors);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Put("quadros-horarios/{id}")
    * @ParamConverter("quadroHorario", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, QuadroHorario $quadroHorario, ConstraintViolationListInterface $errors) {
        return $this->put($request, $id, $quadroHorario, $errors);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Delete("quadros-horarios/{id}")
    */
    function deleteAction(Request $request, $id) {
        return $this->delete($request, $id);
    }

}