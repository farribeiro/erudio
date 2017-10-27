<?php

namespace VagaBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use CoreBundle\Event\EntityEvent;
use VagaBundle\Service\VagaFacade;

class AtualizarVagasListener implements EventSubscriberInterface {
    
    private $vagaFacade;
    
    function __construct(VagaFacade $vagaFacade) {
        $this->vagaFacade = $vagaFacade;
    }
    
    static function getSubscribedEvents() {
        return [
            'CursoBundle:Turma:Created' => 'onTurmaAtualizada',
            'CursoBundle:Turma:Updated' => 'onTurmaAtualizada'
        ];
    }
    
    function onTurmaAtualizada(EntityEvent $event) {
        $this->vagaFacade->atualizarVagas($event->getEntity());
    }
    
}
