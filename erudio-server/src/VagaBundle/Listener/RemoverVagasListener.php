<?php

namespace VagaBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use CoreBundle\Event\EntityEvent;
use VagaBundle\Service\VagaFacade;

class RemoverVagasListener implements EventSubscriberInterface {
    
    private $vagaFacade;
    
    function __construct(VagaFacade $vagaFacade) {
        $this->vagaFacade = $vagaFacade;
    }
    
    static function getSubscribedEvents() {
        return ['CursoBundle:Turma:Removed' => 'onTurmaRemovida'];
    }

    function onTurmaRemovida(EntityEvent $event) {
        $this->vagaFacade->removerVagas($event->getEntity());
    }
    
}
