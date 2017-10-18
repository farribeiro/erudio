<?php

namespace MatriculaBundle\Service;

use MatriculaBundle\Entity\Matricula;
use MatriculaBundle\Entity\ObservacaoHistorico;

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
            'frequencias' => $frequencias,
            'observacoes' => $this->criarObservacoes($matricula)
        ];
    }
    
    function criarObservacoes(Matricula $matricula) {
        $observacoes = $this->gerarObservacoesAutomaticas($matricula);
        foreach ($matricula->getObservacoesHistorico() as $observacao) {
            $observacoes[] = $observacao;
        }
        usort($observacoes, function($a, $b) {
            return strcmp($a->getTexto(), $b->getTexto());
        });
        return $observacoes;
    }
    
    function gerarObservacoesAutomaticas(Matricula $matricula) {
        return $matricula->getEtapasCursadas()->map(function($e) use ($matricula) {
            $texto = $e->getAno() . ' - ' . $e->getEtapa()->getObservacaoAprovacao();
            return ObservacaoHistorico::criar($matricula, $texto);
        })->toArray();
    }
    
}
