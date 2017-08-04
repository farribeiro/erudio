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
            foreach ($disciplinas as $disciplina) {
                $notas[$d->getNomeExibicao()][$etapa->getOrdem() - 1] = $disciplina->getMediaFinal();
            }
        }
        return [
            'matricula' => $matricula,
            'etapasCursadas' => $matricula->getEtapasCursadas(),
            'notas' => $notas
        ];
    }
    
}
