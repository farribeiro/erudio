<?php

namespace MatriculaBundle\Service;

use MatriculaBundle\Entity\Matricula;

class HistoricoFacade {
      
    private $disciplinaCursadaFacade;
    
    function __construct(DisciplinaCursadaFacade $disciplinaCursadaFacade) {
        $this->disciplinaCursadaFacade = $disciplinaCursadaFacade;
    }
    
    /**
     * Gera um array com as informações do histórico escolar, com base em todas as etapas
     * e disciplinas cursadas pelo aluno.
     * 
     * @param Matricula $matricula
     * @return array Um array contendo a própria matrícula, lista de etapas cursadas, notas finais
     * de cada disciplina cursada, frequências médias por etapa, e observações
     */
    function gerarPreliminar(Matricula $matricula) {
        $etapas = $matricula->getCurso()->getEtapas();
        $notas = [];
        $frequencias = [];
        foreach ($etapas as $etapa) {
            $disciplinas = $this->disciplinaCursadaFacade->findAprovadas($matricula, $etapa);
            $numeroDisciplinas = count($disciplinas);
            $frequencias[$etapa->getOrdem()] = $numeroDisciplinas ? 0 
                    : ($this->possuiEtapaCursada($matricula, $etapa) ? 100 : 0);
            foreach ($disciplinas as $d) {
                $notas[$d->getNomeExibicao()][$etapa->getOrdem()] = $d->getMediaFinal();
                $frequencias[$etapa->getOrdem()] += $d->getFrequenciaTotal() / $numeroDisciplinas;
            }
        }
        $etapasCursadas = $matricula->getEtapasCursadas()->filter(function($e) use ($matricula) {
            return $e->isAprovado() || $e->getEnturmacao() === $matricula->getEnturmacoesAtivas()->first();
        })->toArray();
        return [
            'matricula' => $matricula,
            'etapasCursadas' => $etapasCursadas,
            'notas' => $notas,
            'frequencias' => $frequencias,
            'observacoes' => $matricula->getObservacoesHistorico()
        ];
    }
    
    function possuiEtapaCursada(Matricula $matricula, $etapa) {
        return $matricula->getEtapasCursadas()->exists(function($k, $e) use ($etapa) {
           return $e->getEtapa()->getOrdem() === $etapa->getOrdem() && $e->isAprovado();
        });
    }
    
}
