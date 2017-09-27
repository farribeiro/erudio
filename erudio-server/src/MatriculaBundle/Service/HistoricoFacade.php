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
        $frequencias = [];
        foreach ($etapas as $etapa) {
            $disciplinas = $this->disciplinaCursadaFacade->findAprovadas($matricula, $etapa);
            $frequencias[$etapa->getOrdem()] = 0;
            $numeroDisciplinas = count($disciplinas);
            foreach ($disciplinas as $d) {
                $notas[$d->getNomeExibicao()][$etapa->getOrdem()] = $d->getMediaFinal();
                $frequencias[$etapa->getOrdem()] += $d->getFrequenciaTotal() / $numeroDisciplinas;
            }
        }
        return [
            'matricula' => $matricula,
            'etapasCursadas' => $matricula->getEtapasCursadas()->toArray(),
            'notas' => $notas,
            'frequencias' => $frequencias
        ];
    }
    
}
