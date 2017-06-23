<?php

namespace MatriculaBundle\Traits;

use MatriculaBundle\Entity\Media;
use MatriculaBundle\Entity\DisciplinaCursada;
use AvaliacaoBundle\Entity\SistemaAvaliacao;
use CoreBundle\ORM\Exception\IllegalOperationException;

/**
 * Trait com as funções para efetuar os cálculos relacionados a médias parciais e finais.
 * Engloba atualmente os sistemas de avaliação quantitativa simples e qualitativa conceitual.
 */
trait CalculosMedia {
    
    /**
     * Calcula a média final de uma disciplina cursada pelo aluno.
     * 
     * @param DisciplinaCursada $disciplinaCursada
     * @return float valor da média final
     * @throws IllegalOperationException caso existam médias sem nota
     */
    function calcularMediaFinal(DisciplinaCursada $disciplinaCursada) {
        $somaNotas = 0;
        $somaPesos = 0;
        foreach ($disciplinaCursada->getMedias() as $media) {
            if (is_null($media->getValor())) {
                throw new IllegalOperationException(
                        'Média final não pode ser calculada sem o preenchimento de todas as parciais e/ou exame');
            }
            $somaNotas += $media->getValor();
            $somaPesos += $media->getPeso();
        }
        return $somaNotas / $somaPesos;
    }
    
    /**
     * Calcula a frequência total de uma disciplina cursada pelo aluno.
     * 
     * @param DisciplinaCursada $disciplinaCursada
     * @return float porcentagem da frequência do aluno nas aulas
     */
    function calcularFrequenciaTotal(DisciplinaCursada $disciplinaCursada) {
        $frequenciaUnificada = $disciplinaCursada->getDisciplina()->getEtapa()->getFrequenciaUnificada();
        return $frequenciaUnificada 
                ? $this->calcularFrequenciaPorDia($disciplinaCursada) 
                : $this->calcularFrequenciaPorAula($disciplinaCursada);
    }
    
    /**
     * 
     * @param DisciplinaCursada $disciplinaCursada
     * @return float porcentagem de frequência calculada
     */
    function calcularFrequenciaPorDia(DisciplinaCursada $disciplinaCursada) {
        return 100;
    }
    
    /**
    * 
    * @param DisciplinaCursada $disciplinaCursada
    * @return float porcentagem de frequência calculada
    */
    function calcularFrequenciaPorAula(DisciplinaCursada $disciplinaCursada) {
        $somaFaltas = 0;
        foreach ($disciplinaCursada->getMedias() as $media) {
            $somaFaltas += $media->getFaltas();
        }
        $cargaHoraria = $disciplinaCursada->getDisciplina()->getCargaHoraria();
        $duracaoAula = $disciplinaCursada->getEnturmacao()->getTurma()
                ->getQuadroHorario()->getModelo()->getDuracaoAula();
        $numeroAulas = floor($cargaHoraria / ($duracaoAula / 60));
        return 100 - ($somaFaltas * 100) / $numeroAulas;
    }
    
    /**
     * Calcula a nota de uma média do aluno, independente do sistema de avaliação usado.
     * 
     * @param Media $media
     * @return float valor da média
     */
    function calcularMedia(Media $media) {
        $sistemaAvaliacao = $media->getDisciplinaCursada()->getDisciplina()->getEtapa()->getSistemaAvaliacao();
        switch($sistemaAvaliacao->getTipo()) {
            case SistemaAvaliacao::TIPO_QUANTITATIVO:
                return $this->calcularMediaPonderada($media->getNotas());
            case SistemaAvaliacao::TIPO_QUALITATIVO:
                return $this->calcularMediaConceitual($media->getNotas());
        }
    }
    
    /**
     * Calcula a média das notas, usando o sistema quantitativo de média ponderada.
     * 
     * @param ArrayCollection $notas
     * @return type
     */
    function calcularMediaPonderada($notas) {
        $valor = $peso = 0.00;
        foreach ($notas as $nota) {
            $peso += $nota->getAvaliacao()->getPeso();
            $valor += $nota->getValor() * (float)$nota->getAvaliacao()->getPeso();
        }
        return count($notas) ? round($valor / $peso, 2) : 0;
    }
    
    /**
     * Calcula a média das notas, usando o sistema qualitativo conceitual.
     * 
     * @param ArrayCollection $notas
     * @return float valor da média
     * @throws IllegalOperationException caso não exista uma nota de fechamento da média
     * cadastrado.
     */
    function calcularMediaConceitual($notas) {
        $valor = 0.00;
        $notaFechamento = $notas->filter(function($n) { return $n->getFechamentoMedia(); })->first();
        if (!$notaFechamento) {
            throw new IllegalOperationException('Não existem notas de fechamento para a média');
        }
        $habilidades = $notaFechamento->getHabilidadesAvaliadas()
                ->filter(function($h) { return $h->getConceito()->getValorMaximo() > 0; });
        $numHabilidades = $habilidades->count();
        $conceitos = [];
        foreach($habilidades as $habilidade) {
            $conceitos[] = $habilidade->getConceito()->getId();
        }
        $countConceitos = array_count_values($conceitos);        
        foreach($habilidades as $habilidade) {            
            $valor += ($countConceitos[$habilidade->getConceito()->getId()] >= $numHabilidades / 2)
                ? $habilidade->getConceito()->getValorMaximo() 
                : $habilidade->getConceito()->getValorMinimo();
        }        
        return $numHabilidades > 0 ? round($valor / $numHabilidades, 2) : 0.00;
    }
    
}
