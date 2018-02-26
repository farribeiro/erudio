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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use CoreBundle\ORM\Exception\IllegalOperationException;
use MatriculaBundle\Entity\Enturmacao;
use MatriculaBundle\Service\EnturmacaoFacade;
use MatriculaBundle\Model\ImportacaoEnturmacoes;

/**
 * @FOS\NamePrefix("enturmacoes")
 */
class EnturmacaoController extends AbstractEntityController {
    
    function __construct(EnturmacaoFacade $facade) {
        parent::__construct($facade);
    }
    
    /**
    * @ApiDoc()
    * @FOS\Get("enturmacoes/{id}", requirements = {"id": "\d+"})
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    *   @ApiDoc()
    * 
    * @FOS\Get("enturmacoes")
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    * @FOS\QueryParam(name = "matricula", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "matricula_enturmado", nullable = true)
    * @FOS\QueryParam(name = "turma", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "turma_unidadeEnsino", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "aprovado", nullable = true)
    * @FOS\QueryParam(name = "emAndamento", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "encerrado", default = false)
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Post("enturmacoes")
    * @ParamConverter("enturmacao", converter="fos_rest.request_body")
    */
    function postAction(Request $request, Enturmacao $enturmacao, ConstraintViolationListInterface $errors) {
        return $this->post($request, $enturmacao, $errors);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Post("enturmacoes/importacao")
    * @ParamConverter("importacao", converter="fos_rest.request_body")
    */
    function importarAction(Request $request, ImportacaoEnturmacoes $importacao) {
        try {
            $this->getFacade()->importar(
                    $importacao->getTurmaOrigem(), $importacao->getTurmaDestino(), $importacao->getListaExclusoes());
            $view = View::create(null, Response::HTTP_NO_CONTENT);
        } catch (IllegalOperationException $ex) {
            $view = View::create($ex->getMessage(), Response::HTTP_BAD_REQUEST);
        } 
        return $this->handleView($view);
    }

    /**
    * @ApiDoc()
    * @FOS\Delete("enturmacoes/{id}")
    */
    function deleteAction(Request $request, $id) {
        return $this->delete($request, $id);
    }

}
