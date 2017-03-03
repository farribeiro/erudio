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
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\Annotation as JMS;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityResource;
use CalendarioBundle\Entity\Calendario;
use CalendarioBundle\Entity\Dia;
use CalendarioBundle\Entity\DiaEvento;

/**
 * @FOS\RouteResource("calendarios")
 */
class CalendarioController extends AbstractEntityResource {
    
    function getEntityClass() {
        return 'CalendarioBundle:Calendario';
    }
    
    function queryAlias() {
        return 'c';
    }
    
    function parameterMap() {
        return array (
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('c.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'ano' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('c.dataInicio LIKE :ano')->setParameter('ano', $value . '%');
            },
            'instituicao' => function(QueryBuilder $qb, $value) {
                $qb->join('c.instituicao', 'instituicao')
                   ->andWhere('instituicao.id = :instituicao')->setParameter('instituicao', $value);
            }
        );
    }
    
    /**
        *   @ApiDoc()
        * 
        * @FOS\Get("calendarios/{id}")
        */
    function getAction(Request $request, $id) {
        return $this->getOne($id);
    }
    
    /**
        *   @ApiDoc()
        *  
        *   @FOS\Get("calendarios")
        *   @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
        *   @FOS\QueryParam(name = "nome", nullable = true)
        *   @FOS\QueryParam(name = "instituicao", nullable = false) 
        *   @FOS\QueryParam(name = "ano", nullable = true)
        *   @FOS\QueryParam(name = "calendarioBase", nullable = true)
        */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Post("calendarios")
    *  @ParamConverter("calendario", converter="fos_rest.request_body")
    */
    function postAction(Request $request, Calendario $calendario, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->create($calendario);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Put("calendarios/{id}")
    *  @ParamConverter("calendario", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, Calendario $calendario, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->update($id, $calendario);
    }
    
    /**
    * @ApiDoc()
    */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }

}
