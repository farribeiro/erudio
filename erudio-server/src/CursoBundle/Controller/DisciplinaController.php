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
use CursoBundle\Entity\Disciplina;
use FOS\RestBundle\Controller\Annotations as FOS;
use CursoBundle\Entity\DisciplinaOfertada;
use Symfony\Component\HttpFoundation\Response;

/**
* @RouteResource("disciplinas")
*/
class DisciplinaController extends AbstractEntityResource {
    
    function getEntityClass() {
        return 'CursoBundle:Disciplina';
    }
    
    function queryAlias() {
        return 'd';
    }
    
    function parameterMap() {
        return array (
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('d.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'curso' => function(QueryBuilder $qb, $value) {
                $qb->join('d.curso', 'curso')
                   ->andWhere('curso.id = :curso')->setParameter('curso', $value);
            },
            'etapa' => function(QueryBuilder $qb, $value) {
                $qb->join('d.etapa', 'etapa')
                   ->andWhere('etapa.id = :etapa')->setParameter('etapa', $value);
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
        *   @QueryParam(name = "nome", nullable = true) 
        *   @QueryParam(name = "curso", requirements="\d+", nullable = true) 
        *   @QueryParam(name = "etapa", requirements="\d+", nullable = true) 
        */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
    }
    
    /**
        *  @ApiDoc()
        * 
        *  @Post("disciplinas")
        *  @ParamConverter("disciplina", converter="fos_rest.request_body")
        */
    function postAction(Request $request, Disciplina $disciplina, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->create($disciplina);
    }
    
    /**
        *  @ApiDoc()
        * 
        *  @Put("disciplinas/{id}")
        *  @ParamConverter("disciplina", converter="fos_rest.request_body")
        */
    function putAction(Request $request, $id, Disciplina $disciplina, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->update($id, $disciplina);
    }
    
    /**
        *   @ApiDoc()
        */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("turmaDisciplina/{id}")
    */
    /*
    function criarTurmaDisciplinaAction(Request $request, $id) {
        $turmas = $this->getDoctrine()->getRepository('CursoBundle:Turma')->createQueryBuilder('t')
            ->join('t.etapa', 'etapa')                   
            ->join('etapa.curso', 'curso')                   
            ->andWhere('curso.id = :curso')
            ->setParameter('curso', $id)
            ->getQuery()->getResult();
        foreach ($turmas as $turma) {
            $turmaDisciplina = $turma->getDisciplinas();
            if(count($turmaDisciplina) == 0) {
                $disciplinas = $turma->getEtapa()->getDisciplinas();
                foreach($disciplinas as $disciplina) {
                    $disciplinaOfertada = new DisciplinaOfertada($turma, $disciplina);
                    $turma->getDisciplinas()->add($disciplinaOfertada);
                    $this->getDoctrine()->getManager()->merge($turma);
                }
            }            
        }        
        $this->getDoctrine()->getManager()->flush();
        return new Response('Disciplinas ofertadas criadas!');
    }
*/
}
