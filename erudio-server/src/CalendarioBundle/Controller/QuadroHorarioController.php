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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityResource;
use CalendarioBundle\Entity\QuadroHorario;

/**
 * @FOS\RouteResource("quadro-horarios")
 */
class QuadroHorarioController extends AbstractEntityResource {
    
    function getEntityClass() {
        return 'CalendarioBundle:QuadroHorario';
    }
    
    function queryAlias() {
        return 'q';
    }
    
    function parameterMap() {
        return ['nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('q.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
                'unidadeEnsino' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('q.unidadeEnsino = :ue')->setParameter('ue', $value);
            }];
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
        *   @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
        *   @FOS\QueryParam(name = "nome", nullable = true)
        *   @FOS\QueryParam(name = "unidadeEnsino", nullable = true) 
        */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
    }
    
    /**
        *  @ApiDoc()
        * 
        *  @FOS\Post("quadro-horarios")
        *  @ParamConverter("horario", converter="fos_rest.request_body")
        */
    function postAction(Request $request, QuadroHorario $horario, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->create($horario);
    }
    
    /**
        *  @ApiDoc()
        * 
        *  @FOS\Put("quadro-horarios/{id}")
        *  @ParamConverter("horario", converter="fos_rest.request_body")
        */
    function putAction(Request $request, $id, QuadroHorario $horario, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->update($id, $horario);
    }
    
    /**
        *  @FOS\Delete("quadro-horarios/{id}")
        *   @ApiDoc()
        */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }
    
    protected function beforeUpdate($quadroHorario) {
        foreach($quadroHorario->getDiasSemana() as $dia) {
            $dia->setQuadroHorario($quadroHorario);
        }
        foreach($quadroHorario->getHorarios() as $horario) {
            $horario->setQuadroHorario($quadroHorario);
        }
    }

}