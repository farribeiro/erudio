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

namespace CursoBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use CursoBundle\Service\TurmaFacade;
use CoreBundle\Event\EntityEvent;
use CoreBundle\ORM\Exception\IllegalOperationException;

/**
 * Altera o status da matrícula para APROVADO caso seja registrada a última etapa
 * cursada do curso em seu histórico.
 */
class ValidarExclusaoCalendarioListener implements EventSubscriberInterface {
    
   private $turmaFacade;
    
    function __construct(TurmaFacade $turmaFacade) {
        $this->turmaFacade = $turmaFacade;
    }
    
    static function getSubscribedEvents() {
        return ['CalendarioBundle:Calendario:Removed' => 'onCalendarioRemovido'];
    }
    
    function onCalendarioRemovido(EntityEvent $event) {
        $turmas = $this->turmaFacade->findAll(['calendario' => $event->getEntity()]);
        if (count($turmas)) {
            throw new IllegalOperationException('Calendários associados a turmas não podem ser excluídos');
        }
    }

}
