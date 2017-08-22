<?php

namespace VagaBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use CoreBundle\Event\EntityEvent;
use VagaBundle\Service\VagaFacade;

class OcuparVagaListener implements EventSubscriberInterface {
    
    private $vagaFacade;
    
    function __construct(VagaFacade $vagaFacade) {
        $this->vagaFacade = $vagaFacade;
    }
    
    static function getSubscribedEvents() {
        return ['MatriculaBundle:Enturmacao:Created' => 'onEnturmacaoCriada'];
    }
    
    function onEnturmacaoCriada(EntityEvent $event) {
        $enturmacao = $event->getEntity();
        $this->vagaFacade->ocupar($enturmacao->getTurma()->getVagasAbertas()->first(), $enturmacao);
    }
    
}
