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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\Serializer\Annotation as JMS;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use CursoBundle\Entity\DisciplinaOfertada;
use CursoBundle\Service\DisciplinaOfertadaFacade;
use CursoBundle\Service\TurmaFacade;

/**
 * @FOS\NamePrefix("disciplinas-ofertadas")
 */
class DisciplinaOfertadaController extends AbstractEntityController {
    
    private $turmaFacade;
    
    function __construct(DisciplinaOfertadaFacade $facade, TurmaFacade $turmaFacade) {
        parent::__construct($facade);
        $this->turmaFacade = $turmaFacade;
    }

    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("disciplinas-ofertadas/{id}", requirements = {"id": "\d+"})
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("disciplinas-ofertadas")
    * 
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    * @FOS\QueryParam(name = "turma", requirements="\d+", nullable = true) 
    * @FOS\QueryParam(name = "disciplina", requirements="\d+", nullable = true) 
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("turmas/{id}/disciplinas-ofertadas", requirements = {"id": "\d+"})
    * 
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    * @FOS\QueryParam(name = "disciplina", requirements="\d+", nullable = true) 
    * 
    */
    function getByTurmaAction(Request $request, $id, ParamFetcherInterface $paramFetcher) {
        $params = $paramFetcher->all();
        $params['turma'] = $id;
        return $this->getList($request, $params);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Post("disciplinas-ofertadas")
    *  @ParamConverter("disciplinaOfertada", converter="fos_rest.request_body")
    */
    function postAction(Request $request, DisciplinaOfertada $disciplinaOfertada, ConstraintViolationListInterface $errors) {
        return $this->post($request, $disciplinaOfertada, $errors);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Post("turmas/{turma}/disciplinas-ofertadas")
    *  @ParamConverter("batch", converter="fos_rest.request_body")
    */
    function postBatchAction(Request $request, $turma, DisciplinaOfertadaCollection $batch, ConstraintViolationListInterface $errors) {
        $turmaDisciplina = $this->turmaFacade->find($turma);
        foreach ($batch->disciplinasOfertadas as $d) {
            $d->setTurma($turmaDisciplina);
        }
        return $this->postBatch($request, $batch->disciplinasOfertadas, $errors);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Put("disciplinas-ofertadas/{id}", requirements = {"id": "\d+"})
    *  @ParamConverter("disciplinaOfertada", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, DisciplinaOfertada $disciplinaOfertada, ConstraintViolationListInterface $errors) {
        return $this->put($request, $id, $disciplinaOfertada, $errors);
    }
    
    /**
    * @ApiDoc()
    *   
    * @FOS\Delete("disciplinas-ofertadas/{id}", requirements = {"id": "\d+"})
    */
    function deleteAction(Request $request, $id) {
        return $this->delete($request, $id);
    }

}

/**  Wrapper class for batch operations*/
class DisciplinaOfertadaCollection {
    
    /** @JMS\Type("ArrayCollection<CursoBundle\Entity\DisciplinaOfertada>") */
    public $disciplinasOfertadas;
    
}
