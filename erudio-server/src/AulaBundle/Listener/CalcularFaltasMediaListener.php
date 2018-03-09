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

namespace AulaBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use CoreBundle\Event\EntityEvent;
use MatriculaBundle\Service\MediaFacade;
use CalendarioBundle\Service\PeriodoFacade;
use AulaBundle\Service\FrequenciaFacade;
use AulaBundle\Entity\Frequencia;

class CalcularFaltasMediaListener implements EventSubscriberInterface {
    
    private $mediaFacade;
    private $periodoFacade;
    private $frequenciaFacade;
    
    function __construct(MediaFacade $mediaFacade, PeriodoFacade $periodoFacade, 
            FrequenciaFacade $frequenciaFacade) {
        $this->mediaFacade = $mediaFacade;
        $this->periodoFacade = $periodoFacade;
        $this->frequenciaFacade = $frequenciaFacade;
    }
    
    static function getSubscribedEvents() {
        $willApplyPatch = EntityEvent::ACTION_WILL_APPLY_PATCH;
        return ["MatriculaBundle:Media:{$willApplyPatch}" => 'onMediaSeraModificada'];
    }
    
    function onMediaSeraModificada(EntityEvent $event) {
        $media = $event->getEntity();
        if (is_null($media->getFaltas())) {
            $media->setFaltas($this->calcularFaltas($media));
        }
    }
    
    function calcularFaltas($media) {
        $turma = $media->getDisciplinaCursada()->getEnturmacao()->getTurma();
        $periodoMedia = $turma->getPeriodo() 
            ? $turma->getPeriodo() 
            : $this->periodoFacade->findOne([
                'numero' => $media->getNumero(),
                'calendario' => $turma->getCalendario()
            ]);
        if (!$periodoMedia) {
            return $media->getFaltas();
        }
        return $this->frequenciaFacade->count([
            'disciplinaCursada' => $media->getDisciplinaCursada(),
            'aula_dataInicio' => $periodoMedia->getDataInicio(),
            'aula_dataTermino' => $periodoMedia->getDataFim(),
            'status' => Frequencia::FALTA
        ]);
    }
    
}
