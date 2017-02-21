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

namespace MatriculaBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityResource;
use MatriculaBundle\Entity\Uniforme;

class UniformeController extends AbstractEntityResource {
    
    function getEntityClass() {
        return 'MatriculaBundle:Uniforme';
    }
    
    function queryAlias() {
        return 'u';
    }
    
    function parameterMap() {
        return array (
            'matricula' => function(QueryBuilder $qb, $value) {
                $qb->join('u.matricula', 'matricula')
                   ->andWhere('matricula.id = :matricula')->setParameter('matricula', $value);
            },
            'uniformeNumero' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('u.uniformeNumero = :uniformeNumero')->setParameter('uniformeNumero', $value);
            },
            'calcadoNumero' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('u.calcadoNumero = :calcadoNumero')->setParameter('calcadoNumero', $value);
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
        *   @QueryParam(name = "matricula", requirements="\d+", nullable = true) 
        *   @QueryParam(name = "uniformeNumero", nullable = true) 
        *   @QueryParam(name = "calcadoNumero", nullable = true) 
        */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
    }
    
    /**
        *  @ApiDoc()
        * '
        *  @Post("uniformes")
        *  @ParamConverter("uniforme", converter="fos_rest.request_body")
        */
    function postAction(Request $request, Uniforme $uniforme, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->create($uniforme);
    }
    
    /**
        *  @ApiDoc()
        * 
        *  @Put("uniformes/{id}")
        *  @ParamConverter("uniforme", converter="fos_rest.request_body")
        */
    function putAction(Request $request, $id, Uniforme $uniforme, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->update($id, $uniforme);
    }
    
    /**
        *   @ApiDoc()
        */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }

}
