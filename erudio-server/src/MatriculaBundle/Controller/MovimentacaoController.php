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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use MatriculaBundle\Service\MovimentacaoTurmaFacade;
use MatriculaBundle\Service\TransferenciaFacade;
use MatriculaBundle\Service\DesligamentoFacade;
use MatriculaBundle\Service\ReclassificacaoFacade;
use MatriculaBundle\Service\RetornoFacade;
use MatriculaBundle\Entity\Transferencia;

/**
 * @FOS\RouteResource("matriculas")
 */
class MovimentacaoController extends Controller {
    
    private $viewHandler;
    private $movimentacaoTurmaFacade;
    private $transferenciaFacade;
    private $desligamentoFacade;
    private $reclassificacaoFacade;
    private $retornoFacade;
    
    function __construct(MovimentacaoTurmaFacade $movimentacaoTurmaFacade,
            TransferenciaFacade $transferenciaFacade, DesligamentoFacade $desligamentoFacade,
            ReclassificacaoFacade $reclassificacaoFacade, RetornoFacade $retornoFacade) {
        $this->movimentacaoTurmaFacade = $movimentacaoTurmaFacade;
        $this->transferenciaFacade = $transferenciaFacade;
        $this->desligamentoFacade = $desligamentoFacade;
        $this->reclassificacaoFacade = $reclassificacaoFacade;
        $this->retornoFacade = $retornoFacade;
    }
    
    function setViewHandler(ViewHandlerInterface $viewHandler) {
        $this->viewHandler = $viewHandler;
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Get("matriculas/{id}/movimentacoes")
    */
    function getAllAction($id) 
    {   
        $resultados = [];
        $resultados['transferencias'] = $this->transferenciaFacade->findAll([
            'matricula' => $id, 
            'status' => Transferencia::STATUS_ACEITO
        ]);
        $resultados['movimentacoes-turma'] = $this->movimentacaoTurmaFacade->findAll(['matricula' => $id]);
        $resultados['desligamentos'] = $this->desligamentoFacade->findAll(['matricula' => $id]);
        $resultados['reclassificacoes'] = $this->reclassificacaoFacade->findAll(['matricula' => $id]);
        $resultados['retornos'] = $this->retornoFacade->findAll(['matricula' => $id]);
        $view = View::create($resultados, 200);
        $this->configureContext($view->getContext());
        return $this->viewHandler->handle($view);
    }
    
    public function configureContext($context) {
        $context->setGroups([AbstractEntityController::SERIALIZER_GROUP_LIST]);
        $context->setMaxDepth(3);
        return $context;
    }

}
