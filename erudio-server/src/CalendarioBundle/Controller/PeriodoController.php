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
use AvaliacaoBundle\Entity\SistemaAvaliacao;
use CalendarioBundle\Entity\Periodo;

/**
 * @FOS\RouteResource("periodos")
 */
class PeriodoController extends AbstractEntityResource {
    
    function getEntityClass() {
        return 'CalendarioBundle:Periodo';
    }
    
    function queryAlias() {
        return 'p';
    }
    
    function parameterMap() {
        return array (
            'media' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('p.media = :media')->setParameter('media', $value);
            },
            'calendario' => function(QueryBuilder $qb, $value) {
                $qb->join('p.calendario', 'calendario')
                   ->andWhere('calendario.id = :calendario')->setParameter('calendario', $value);
            },
            'sistemaAvaliacao' => function(QueryBuilder $qb, $value) {
                $qb->join('p.sistemaAvaliacao', 'sistemaAvaliacao')
                   ->andWhere('sistemaAvaliacao.id = :sistemaAvaliacao')->setParameter('sistemaAvaliacao', $value);
            }
        );
    }
    
    /**
        *   @ApiDoc()
        * 
        * @FOS\Get("periodos/{id}")
        */
    function getAction(Request $request, $id) {
        return $this->getOne($id);
    }
    
    /**
        *   @ApiDoc()
        *  
        *   @FOS\Get("periodos")
        *   @FOS\QueryParam(name = "media", requirements="\d+", default = null) 
        *   @FOS\QueryParam(name = "calendario", nullable = false) 
        *   @FOS\QueryParam(name = "sistemaAvaliacao", nullable = false)
        */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Post("periodos")
    *  @ParamConverter("periodo", converter="fos_rest.request_body")
    */
    function postAction(Request $request, Periodo $periodo, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->create($periodo);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Put("periodos/{id}")
    *  @ParamConverter("periodo", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, Periodo $periodo, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->update($id, $periodo);
    }
    
    /**
    * @ApiDoc()
    */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }

}
