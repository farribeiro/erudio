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
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use CursoBundle\Entity\Etapa;

/**
 * @FOS\RouteResource("etapas")
 */
class EtapaController extends AbstractEntityController {
  
    function getFacade() {
        return $this->get('facade.curso.etapas');
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Get("etapas/{id}") 
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Get("etapas") 
    *   @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    *   @FOS\QueryParam(name = "nome", nullable = true) 
    *   @FOS\QueryParam(name = "curso", requirements="\d+", nullable = true) 
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Post("etapas")
    *  @ParamConverter("etapa", converter="fos_rest.request_body")
    */
    function postAction(Request $request, Etapa $etapa, ConstraintViolationListInterface $errors) {
        return $this->post($request, $etapa, $errors);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Put("etapas/{id}")
    *  @ParamConverter("etapa", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, Etapa $etapa, ConstraintViolationListInterface $errors) {
        return $this->put($request, $id, $etapa, $errors);
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Delete("etapas/{id}") 
    */
    function deleteAction(Request $request, $id) {
        return $this->delete($request, $id);
    }

    /**
    *   @ApiDoc()
    * 
    *   @FOS\Get("etapas-ofertadas")
    *   @FOS\QueryParam(name = "unidadeEnsino", requirements="\d+", nullable = false)
    */
    function getOfertadasAction(Request $request, ParamFetcherInterface $paramFetcher) {
        $unidadeEnsino = $paramFetcher->get('unidadeEnsino', true);
        $turmas = new ArrayCollection($this->get('facade.curso.turmas')->findAll(
            ['unidadeEnsino' => $unidadeEnsino, 'encerrado' => false]
        ));
        $resultados = array_unique($turmas->map(function($t) { return $t->getEtapa(); })->toArray());
        $view = View::create(array_values($resultados), Codes::HTTP_OK);
        $view->getSerializationContext()->enableMaxDepthChecks();
        $view->getSerializationContext()->setGroups(array(self::SERIALIZER_GROUP_LIST));
        return $this->handleView($view);
    }
    
}
