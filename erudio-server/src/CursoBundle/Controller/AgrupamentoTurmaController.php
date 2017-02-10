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

namespace CursoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityResource;
use CursoBundle\Entity\AgrupamentoTurma;

/**
 * @RouteResource("turmas/agrupamentos")
 */
class AgrupamentoTurmaController extends AbstractEntityResource {
    
    function getEntityClass() {
        return 'CursoBundle:AgrupamentoTurma';
    }
    
    function queryAlias() {
        return 'a';
    }
    
    function parameterMap() {
        return array (
            'unidadeEnsino' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('a.unidadeEnsino = :unidadeEnsino')->setParameter('unidadeEnsino', $value);
            }
        );
    }
    
    /**
        *   @ApiDoc()
        */
    function getAction(Request $request, $id) {
        return $this->getOne($id);
    }
    
    /**
        *   @ApiDoc()
        * 
        *   @QueryParam(name = "page", requirements="\d+", default = null) 
        *   @QueryParam(name = "unidadeEnsino", requirements="\d+", nullable = true) 
        */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @Post("turmas/agrupamentos")
    *  @ParamConverter("agrupamento", converter="fos_rest.request_body")
    */
    function postAction(Request $request, AgrupamentoTurma $agrupamento, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->create($agrupamento);
    }
    
    /**
        *  @ApiDoc()
        * 
        *  @Put("turmas/agrupamentos/{id}")
        *  @ParamConverter("agrupamento", converter="fos_rest.request_body")
        */
    function putAction(Request $request, $id, AgrupamentoTurma $agrupamento, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->update($id, $agrupamento);
    }
    
    /**
        *   @ApiDoc()
        */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }

}
