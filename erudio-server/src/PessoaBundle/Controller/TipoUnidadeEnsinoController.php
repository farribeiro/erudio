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

namespace PessoaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use PessoaBundle\Entity\TipoUnidadeEnsino;
use PessoaBundle\Service\TipoUnidadeEnsinoFacade;

/**
 * @FOS\NamePrefix("tipos-unidade-ensino")
 */
class TipoUnidadeEnsinoController extends AbstractEntityController {
    
    function __construct(TipoUnidadeEnsinoFacade $facade) {
        parent::__construct($facade);
    } 
    
    /**
    *   @ApiDoc(
    *       section = "Módulo Pessoa",
    *       description = "Busca um tipo de unidade por seu id",
    *       parameters={
    *           {"name" = "id", "dataType"="integer", "required"=true, "description"="id do objeto"}
    *       },
    *       statusCodes = {
    *           200 = "Retorno do objeto",
    *           404 = "Objeto não encontrado"
    *       }
    *   )
    * 
    *   @FOS\Get("tipos-unidade-ensino/{id}")
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    *   @ApiDoc(
    *       resource = true,
    *       section = "Módulo Pessoa",
    *       description = "Busca de tipos de unidade de ensino",
    *       statusCodes = {
    *           200 = "Retorno dos resultados da busca"
    *       }
    *   ) 
    * 
    *   @FOS\Get("tipos-unidade-ensino")
    *   @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    *   @FOS\QueryParam(name = "nome", nullable = true) 
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Post("tipos-unidade-ensino")
    *   @ParamConverter("tipo", converter="fos_rest.request_body")
    */
    function postAction(Request $request, TipoUnidadeEnsino $tipo, ConstraintViolationListInterface $errors) {
        return $this->post($request, $tipo, $errors);
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Put("tipos-unidade-ensino/{id}")
    *   @ParamConverter("tipo", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, TipoUnidadeEnsino $tipo, ConstraintViolationListInterface $errors) {
        return $this->put($request, $id, $tipo, $errors);
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Delete("tipos-unidade-ensino/{id}")
    */
    function deleteAction(Request $request, $id) {
        return $this->delete($request, $id);
    }

}
