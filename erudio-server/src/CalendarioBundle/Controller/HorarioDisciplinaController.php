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
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\REST\AbstractEntityController;
use CalendarioBundle\Entity\HorarioDisciplina;

/**
 * @FOS\RouteResource("horarios-disciplinas")
 */
class HorarioDisciplinaController extends AbstractEntityController {
    
    function getFacade() {
        return $this->get('facade.calendario.horarios_disciplinas');
    }
    
    /**
    *   @ApiDoc()
    * 
    *   @FOS\Get("horarios-disciplinas")
    *   @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    *   @FOS\QueryParam(name = "disciplina", requirements="\d+", nullable = true) 
    *   @FOS\QueryParam(name = "horario", requirements="\d+", nullable = true)
    *   @FOS\QueryParam(name = "turma", requirements="\d+", nullable = true) 
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher);
    }

    /**
    *  @ApiDoc()
    * 
    *  @FOS\Post("horarios-disciplinas")
    *  @ParamConverter("horario", converter="fos_rest.request_body")
    */
    function postAction(Request $request, HorarioDisciplina $horario, ConstraintViolationListInterface $errors) {
        return $this->post($request, $horario, $errors);
    }
    
    /**
    *  @ApiDoc()
    * 
    *  @FOS\Put("horarios-disciplinas/{id}/troca")
    *  @ParamConverter("horarioTroca", converter="fos_rest.request_body")
    */
    function swapAction(Request $request, $id, HorarioDisciplina $horarioTroca) {
        try {
            $dataInicio = $request->query->has('dataInicio')
                    ? \DateTime::createFromFormat('Y-m-d', $request->query->get('dataInicio'))
                    : new \DateTime();
            $horario = $this->getFacade()->find($id);
            $this->getFacade()->trocar($horario, $horarioTroca, $dataInicio);
            $view = View::create(null, Codes::HTTP_NO_CONTENT);
        } catch(NoResultException $ex) {
            $view = FOS\View::create(null, Codes::HTTP_NOT_FOUND);
        } catch(\Exception $ex) {
            $view = View::create($ex->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->handleView($view);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Delete("horarios-disciplinas/{id}")
    */
    function deleteAction(Request $request, $id) {
        return $this->delete($request, $id);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Delete("horarios-disciplinas")
    */
    function deleteBatchAction(Request $request) {
        $ids = $request->get('ids');
        foreach ($ids as $id) { 
            $this->delete($request, $id);
        }
        return new Response('removido');
    }
}

/**  Wrapper class for batch operations*/
class HorarioCollection {
    
    /** @JMS\Type("ArrayCollection<CalendarioBundle\Entity\HorarioDisciplina>") */
    public $horarios;
    
}