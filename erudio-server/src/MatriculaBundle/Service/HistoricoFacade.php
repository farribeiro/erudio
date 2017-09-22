<?php

namespace MatriculaBundle\Service;

use MatriculaBundle\Entity\Matricula;

class HistoricoFacade {
      
    private $disciplinaCursadaFacade;
    
    function __construct(DisciplinaCursadaFacade $disciplinaCursadaFacade) {
        $this->disciplinaCursadaFacade = $disciplinaCursadaFacade;
    }
    
    function gerarPreliminar(Matricula $matricula) {
        $etapas = $matricula->getCurso()->getEtapas();
        $notas = [];
        foreach ($etapas as $etapa) {
            $disciplinas = $this->disciplinaCursadaFacade->findAprovadas($matricula, $etapa);
            foreach ($disciplinas as $d) {
                $notas[$d->getNomeExibicao()][$etapa->getOrdem()] = $d->getMediaFinal();
            }
        }
        return [
            'matricula' => $matricula,
            'etapasCursadas' => $matricula->getEtapasCursadas()->toArray(),
            'notas' => $notas
        ];
    }
    
}
