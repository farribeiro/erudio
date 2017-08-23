<?php

namespace VagaBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use CoreBundle\Event\EntityEvent;
use VagaBundle\Service\VagaFacade;

class LiberarVagaListener implements EventSubscriberInterface {
    
    private $vagaFacade;
    
    function __construct(VagaFacade $vagaFacade) {
        $this->vagaFacade = $vagaFacade;
    }
    
    static function getSubscribedEvents() {
        return [
            'MatriculaBundle:Enturmacao:Updated' => 'onEnturmacaoAtualizada',
            'MatriculaBundle:Enturmacao:Removed' => 'onEnturmacaoRemovida'
        ];
    }
    
    function onEnturmacaoAtualizada(EntityEvent $event) {
        $enturmacao = $event->getEntity();
        if ($enturmacao->getEncerrado() && $enturmacao->getVaga()) {
            $this->vagaFacade->liberar($enturmacao->getVaga());
        }
    }
    
    function onEnturmacaoRemovida(EntityEvent $event) {
        $enturmacao = $event->getEntity();
        if ($enturmacao->getVaga()) {
            $this->vagaFacade->liberar($enturmacao->getVaga());
        }
    }
    
}
