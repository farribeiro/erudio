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

namespace AvaliacaoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use AvaliacaoBundle\Entity\AvaliacaoQualitativa;

/**
 * @FOS\RouteResource("avaliacoes-qualitativas")
 */
class AvaliacaoQualitativaController extends AbstractEntityController {
    
    public function getFacade() {
        return $this->get('facade.avaliacao.avaliacoes_qualitativas');
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("avaliacoes-qualitativas/{id}")
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("avaliacoes-qualitativas")
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null)
    * @FOS\QueryParam(name = "nome", nullable = true)
    * @FOS\QueryParam(name = "turma", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "disciplina", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "dia", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "media", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "fechamentoMedia", nullable = true) 
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Post("avaliacoes-qualitativas")
    *  @ParamConverter("avaliacao", converter="fos_rest.request_body")
    */
    function postAction(Request $request, AvaliacaoQualitativa $avaliacao, ConstraintViolationListInterface $errors) {
        return $this->post($request, $avaliacao, $errors);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Put("avaliacoes-qualitativas/{id}")
    *  @ParamConverter("avaliacao", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, AvaliacaoQualitativa $avaliacao, ConstraintViolationListInterface $errors) {
        return $this->put($request, $id, $avaliacao, $errors);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Delete("avaliacoes-qualitativas/{id}")
    */
    function deleteAction(Request $request, $id) {
        return $this->delete($request, $id);
    }

}
