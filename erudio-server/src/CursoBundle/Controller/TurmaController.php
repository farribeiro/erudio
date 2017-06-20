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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use CursoBundle\Entity\Turma;
use CursoBundle\Service\TurmaFacade;

/**
 * @FOS\NamePrefix("turmas")
 */
class TurmaController extends AbstractEntityController {
    
    function __construct(TurmaFacade $facade) {
        parent::__construct($facade);
    }
        
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("turmas/{id}", requirements={"id" = "\d+"})
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    *  @ApiDoc()
    * 
    * @FOS\Get("turmas")
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    * @FOS\QueryParam(name = "nome", nullable = true) 
    * @FOS\QueryParam(name = "apelido", nullable = true)  
    * @FOS\QueryParam(name = "curso", requirements="\d+", nullable = true) 
    * @FOS\QueryParam(name = "etapa", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "etapa_ordem", requirements="\d+", nullable = true) 
    * @FOS\QueryParam(name = "agrupamento", requirements="\d+", nullable = true) 
    * @FOS\QueryParam(name = "quadroHorario", requirements="\d+", nullable = true) 
    * @FOS\QueryParam(name = "unidadeEnsino", requirements="\d+", nullable = true) 
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Post("turmas")
    * @ParamConverter("turma", converter="fos_rest.request_body")
    */
    function postAction(Request $request, Turma $turma, ConstraintViolationListInterface $errors) {
        return $this->post($request, $turma, $errors);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Put("turmas/{id}", requirements={"id" = "\d+"})
    * @ParamConverter("turma", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, Turma $turma, ConstraintViolationListInterface $errors) {
        return $this->put($request, $id, $turma, $errors);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Delete("turmas/{id}", requirements={"id" = "\d+"}) 
    */
    function deleteAction(Request $request, $id) {
        return $this->delete($request, $id);
    } 
    
   /**
    * @ApiDoc()
    * 
    * @FOS\Delete("turmas/{id}/agrupamento", requirements={"id" = "\d+"})
    */
    function removeAgrupamentoAction($id) {
        try {
            $turma = $this->getFacade()->loadEntity($id);
            $this->getFacade()->removerAgrupamento($turma);
            $view = View::create(null, Response::HTTP_NO_CONTENT);
        } catch(NoResultException $ex) {
            $view = View::create(null, Response::HTTP_NOT_FOUND);
        } catch(\Exception $ex) {
            $view = View::create($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->handleView($view);
    } 
    
}

