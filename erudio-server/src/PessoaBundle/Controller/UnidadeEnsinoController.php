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
use PessoaBundle\Entity\UnidadeEnsino;
use PessoaBundle\Service\UnidadeEnsinoFacade;

/**
 * @FOS\RouteResource("unidades-ensino")
 */
class UnidadeEnsinoController extends AbstractEntityController {
    
    function __construct(UnidadeEnsinoFacade $facade) {
        parent::__construct($facade);
    } 
    
    /**
    * @ApiDoc(
    *   section = "Módulo Pessoa",
    *   description = "Busca uma unidade de ensino por seu id",
    *   parameters={
    *      {"name" = "id", "dataType"="integer", "required"=true, "description"="id do objeto"}
    *   },
    *   statusCodes = {
    *       200 = "Retorno do objeto",
    *       404 = "Objeto não encontrado"
    *   }
    * )
    * 
    * @FOS\Get("unidades-ensino/{id}", requirements={"id" = "\d+"})
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Pessoa",
    *   description = "Busca de unidades de ensino",
    *   statusCodes = {
    *       200 = "Retorno dos resultados da busca"
    *   }
    * ) 
    * 
    * @FOS\Get("unidades-ensino") 
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    * @FOS\QueryParam(name = "nome", nullable = true) 
    * @FOS\QueryParam(name = "tipo", requirements="\d+", nullable = true) 
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }
    
    /**
    * @ApiDoc(
    *   section = "Módulo Pessoa",
    *   description = "Cadastra uma nova unidade de ensino",
    *   input = "PessoaBundle\Entity\UnidadeEnsino",
    *   statusCodes = {
    *       200 = "Objeto criado contendo seu id"
    *   }
    * ) 
    * 
    * @FOS\Post("unidades-ensino")
    * @ParamConverter("unidade", converter="fos_rest.request_body")
    */
    function postAction(Request $request, UnidadeEnsino $unidade, ConstraintViolationListInterface $errors) {
        return $this->post($request, $unidade, $errors);
    }
    
    /**
    * @ApiDoc(
    *   section = "Módulo Pessoa",
    *   description = "Atualiza uma unidade de ensino por seu id",
    *   parameters={
    *      {"name" = "id", "dataType"="integer", "required"=true, "description"="id do objeto"}
    *   },
    *   statusCodes = {
    *       200 = "Objeto com atualizações aplicadas",
    *       403 = "Operação não permitida sobre este objeto",
    *       404 = "Objeto não encontrado"
    *   }
    * )
    * 
    * @FOS\Put("unidades-ensino/{id}")
    * @ParamConverter("unidade", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, UnidadeEnsino $unidade, ConstraintViolationListInterface $errors) {
        return $this->put($request, $id, $unidade, $errors);
    }
    
    /**
    * @ApiDoc(
    *   section = "Módulo Pessoa",
    *   description = "Exclui uma unidade de ensino por seu id",
    *   parameters={
    *      {"name" = "id", "dataType"="integer", "required"=true, "description"="id do objeto"}
    *   },
    *   statusCodes = {
    *       204 = "Exclusão realizada",
    *       404 = "Objeto não encontrado"
    *   }
    * )
    * 
    * @FOS\Delete("unidades-ensino/{id}")
    */
    function deleteAction(Request $request, $id) {
        return $this->delete($request, $id);
    }

}
