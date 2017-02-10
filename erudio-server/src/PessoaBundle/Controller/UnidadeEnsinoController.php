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
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityResource;
use PessoaBundle\Entity\UnidadeEnsino;

/**
 * @RouteResource("unidades-ensino")
 */
class UnidadeEnsinoController extends AbstractEntityResource {
    
    function getEntityClass() {
        return 'PessoaBundle:UnidadeEnsino';
    }
    
    function queryAlias() {
        return 'u';
    }
    
    function parameterMap() {
        return array (
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('u.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'tipo' => function(QueryBuilder $qb, $value) {
                $qb->join('u.tipo', 't')->andWhere('t.id LIKE :tipo')->setParameter('tipo', '%' . $value . '%');
            }
        );
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
        * @Get("unidades-ensino/{id}", requirements={"id" = "\d+"}) 
        */
    function getAction(Request $request, $id) {
        return $this->getOne($id);
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
        * @Get("unidades-ensino") 
        * @QueryParam(name = "page", requirements="\d+", default = null) 
        * @QueryParam(name = "nome", nullable = true) 
        * @QueryParam(name = "tipo", requirements="\d+", nullable = true) 
        */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
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
        * @Post("unidades-ensino")
        * @ParamConverter("unidade", converter="fos_rest.request_body")
        */
    function postAction(Request $request, UnidadeEnsino $unidade, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->create($unidade);
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
        * @Put("unidades-ensino/{id}")
        * @ParamConverter("unidade", converter="fos_rest.request_body")
        */
    function putAction(Request $request, $id, UnidadeEnsino $unidade, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->update($id, $unidade);
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
        * @Delete("unidades-ensino/{id}")
        */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }

}
