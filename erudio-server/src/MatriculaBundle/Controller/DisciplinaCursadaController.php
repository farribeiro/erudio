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
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOS;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\REST\AbstractEntityController;
use MatriculaBundle\Entity\DisciplinaCursada;

/**
 * @FOS\RouteResource("disciplinas-cursadas")
 */
class DisciplinaCursadaController extends AbstractEntityController {

    public function getFacade() {
        return $this->get('facade.matricula.disciplinas_cursadas');
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("disciplinas-cursadas/{id}", requirements = {"id": "\d+"})
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("disciplinas-cursadas")
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    * @FOS\QueryParam(name = "dataCadastro", nullable = true) 
    * @FOS\QueryParam(name = "status", nullable = true) 
    * @FOS\QueryParam(name = "enturmacao", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "turma", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "matricula", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "disciplina", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "etapa", requirements="\d+", nullable = true)
    * 
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("matriculas/{id}/disciplinas-cursadas", requirements = {"id": "\d+"})
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    * @FOS\QueryParam(name = "status", nullable = true) 
    * @FOS\QueryParam(name = "enturmacao", requirements="\d+", nullable = true) 
    * @FOS\QueryParam(name = "disciplina", requirements="\d+", nullable = true) 
    * @FOS\QueryParam(name = "etapa", requirements="\d+", nullable = true)
    * 
    */
    function getByMatriculaAction(Request $request, $id, ParamFetcherInterface $paramFetcher) {
        $params = $paramFetcher->all();
        $params['matricula'] = $id; 
        return $this->getList($request, $params);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Post("disciplinas-cursadas")
    * @ParamConverter("disciplina", converter="fos_rest.request_body")
    */
    function postAction(Request $request, DisciplinaCursada $disciplina, ConstraintViolationListInterface $errors) {
        return $this->post($request, $disciplina, $errors);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Post("disciplinas-cursadas/{id}/media-final", requirements = {"id": "\d+"})
    */
    function postMediaFinalAction($id) {
        try {
            $disciplina = $this->getFacade()->find($id);
            $this->getFacade()->encerrar($disciplina);
            $view = View::create($disciplina);
        } catch (Exception $ex) {
            $view = View::create($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->handleView($view);
    }
    
    /**
    * @ApiDoc()
    * 
    * 
    * @FOS\Post("disciplinas-ofertadas/{id}/media-final", requirements = {"id": "\d+"})
    */
    function postMediasFinaisAction($id) {
        $disciplinas = $this->getFacade()->findAll(['disciplinaOfertada' => $id]);
        try {
            foreach($disciplinas as $disciplina) {
                $this->getFacade()->encerrar($disciplina);
            }
            $view = View::create(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $ex) {
            $view = View::create($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->handleView($view);
    }

    /**
    * @ApiDoc()
    * 
    * @FOS\Put("disciplinas-cursadas")
    * @ParamConverter("disciplinas", converter="fos_rest.request_body")
    */
    function putBatchAction(Request $request, DisciplinaCursadaCollection $disciplinas, ConstraintViolationListInterface $errors) {
        return $this->putBatch($request, $disciplinas->disciplinas, $errors);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Put("disciplinas-cursadas/{id}", requirements = {"id": "\d+"})
    * @ParamConverter("disciplina", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, DisciplinaCursada $disciplina, ConstraintViolationListInterface $errors) {
        return $this->put($request, $id, $disciplina, $errors);
    }
    
}

/**  Wrapper class for batch operations*/
class DisciplinaCursadaCollection {
    
    /** @JMS\Type("ArrayCollection<MatriculaBundle\Entity\DisciplinaCursada>") */
    public $disciplinas;
    
}
