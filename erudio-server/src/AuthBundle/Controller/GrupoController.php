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

namespace AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use AuthBundle\Entity\Grupo;

/**
 * @FOS\RouteResource("grupos")
 */
class GrupoController extends AbstractEntityController {
    
    public function getFacade() {
        return $this->get('facade.auth.grupos');
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("grupos/{id}")
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Atribuições",
    *   description = "Busca de atribuições",
    *   statusCodes = {
    *       200 = "Retorno dos resultados da busca"
    *   }
    * )
    * 
    * @FOS\Get("grupos")
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    * @FOS\QueryParam(name = "nome", nullable = true)
    * @FOS\QueryParam(name = "peso", nullable = true)
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }
        
    /**
    * @ApiDoc()
    * 
    * @FOS\Post("grupos")
    * @ParamConverter("grupo", converter="fos_rest.request_body")
    */
    function postAction(Request $request, Grupo $grupo, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->post($request, $grupo, $errors);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Put("grupos/{id}")
    * @ParamConverter("grupo", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, Grupo $grupo, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->put($request, $id, $grupo, $errors);
    }
    
    /**
    * @ApiDoc()
    */
    function deleteAction(Request $request, $id) {
        return $this->delete($request, $id);
    }
}
