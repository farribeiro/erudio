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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\Annotation as JMS;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use CoreBundle\ORM\Exception\IllegalUpdateException;
use MatriculaBundle\Model\RegistroFaltas;
use MatriculaBundle\Entity\Media;

/**
 * @FOS\RouteResource("medias")
 */
class MediaController extends AbstractEntityController {
    
    public function getFacade() {
        return $this->get('facade.matricula.medias');
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("medias/{id}", requirements = {"id": "\d+"})
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("medias")
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null)
    * @FOS\QueryParam(name = "numero", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "disciplinaCursada", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "disciplinaOfertada", requirements="\d+", nullable = true)
    * @FOS\QueryParam(name = "enturmacao", requirements="\d+", nullable = true)
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }

    /**
    * @ApiDoc()
    * 
    * @FOS\Put("medias/{id}", requirements = {"id": "\d+"})
    * @ParamConverter("media", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, Media $media, ConstraintViolationListInterface $errors) {
        return $this->put($request, $id, $media, $errors);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Put("medias")
    * @ParamConverter("medias", converter="fos_rest.request_body")
    */
    function putBatchAction(Request $request, MediaCollection $medias, ConstraintViolationListInterface $errors) {
        return $this->putBatch($request, $medias->medias, $errors);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("medias/faltas")
    * @FOS\QueryParam(name = "numero", requirements="\d+", nullable = false, strict = true)
    * @FOS\QueryParam(name = "turma", requirements="\d+", nullable = false, strict = true) 
    */
    function getFaltasAction(Request $request, ParamFetcherInterface $params) {
        try {
            $turma = $this->get('facade.curso.turmas')->find($params->get('turma'));
            $faltas = $turma->getEnturmacoes()->map(function($e) use ($params) {
                $primeiraDisciplina = $e->getDisciplinasCursadas()->first();
                if (!$primeiraDisciplina) {
                    throw new \Exception("O aluno com a matrícula {$e->getMatricula()->getCodigo()} não possui disciplinas em sua enturmação");
                }
                $media = $this->getFacade()->findAll([
                    'numero' => $params->get('numero'), 
                    'disciplinaCursada' => $primeiraDisciplina->getId()
                ])[0];
                return new RegistroFaltas($e, $media->getNumero(), $media->getFaltas());
            }); 
            $view = View::create($faltas, Codes::HTTP_OK);
            $view->getSerializationContext()->setGroups(array(self::SERIALIZER_GROUP_LIST));
            $view->getSerializationContext()->enableMaxDepthChecks();
        } catch (IllegalUpdateException $ex) {
            $view = View::create($ex->getMessage(), Codes::HTTP_BAD_REQUEST);
        } 
        return $this->handleView($view);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Post("medias/faltas")
    * @ParamConverter("faltas", converter="fos_rest.request_body")
    */
    function postFaltasAction(Request $request, FaltasCollection $faltas, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        try {
            foreach ($faltas->faltas as $registroFaltas) {
                $this->getFacade()->inserirFaltasPorMedia(
                        $registroFaltas->faltas, $registroFaltas->media, $registroFaltas->enturmacao);
            }
            $view = View::create(null, Codes::HTTP_NO_CONTENT);
        } catch (IllegalUpdateException $ex) {
            $view = View::create($ex->getMessage(), Codes::HTTP_BAD_REQUEST);
        } 
        return $this->handleView($view);
    }
    
}

class MediaCollection {
    
    /** @JMS\Type("ArrayCollection<MatriculaBundle\Entity\Media>") */
    public $medias;
    
}

class FaltasCollection {
    
     /** @JMS\Type("ArrayCollection<MatriculaBundle\Model\RegistroFaltas>") */
    public $faltas;
    
}


