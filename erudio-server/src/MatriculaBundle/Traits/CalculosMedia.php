<?php

namespace MatriculaBundle\Traits;

use MatriculaBundle\Entity\Media;
use MatriculaBundle\Entity\DisciplinaCursada;
use CursoBundle\Entity\SistemaAvaliacao;
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
            $somaNotas += $media->getValor() * ($media->getPeso() > 0 ? (float)$media->getPeso() : 1);
            $somaPesos += $media->getPeso();
        }
        return $somaPesos > 0 ? round($somaNotas / $somaPesos, 2) : null;
    }
    
    /**
     * Calcula a frequência total de uma disciplina cursada pelo aluno, selecionando
     * o método de cálculo apropriado de acordo com o curso e etapa.
     * 
     * @param DisciplinaCursada $disciplinaCursada
     * @return float porcentagem da frequência do aluno nas aulas
     */
    function calcularFrequenciaTotal(DisciplinaCursada $disciplinaCursada) {
        $frequenciaUnificada = $disciplinaCursada->getDisciplina()->getEtapa()->getFrequenciaUnificada();
        return $frequenciaUnificada 
                ? $this->calcularFrequenciaPorDia($disciplinaCursada) 
                : $this->calcularFrequenciaGeralPorEnturmacao($disciplinaCursada);
    }
    
    /**
     * Calcula a frequência da disciplina baseando-se em dias, onde cada falta representa 
     * um dia que o aluno não compareceu, considerando o número de dias efetivos do 
     * calendário escolar como 100%.
     * 
     * Na prática, este cálculo faz com que todas as disciplinas possuam a mesma frequência.
     * 
     * @param DisciplinaCursada $disciplinaCursada
     * @return float porcentagem de frequência calculada
     */
    function calcularFrequenciaPorDia(DisciplinaCursada $disciplinaCursada, $faltas = 0) {
        $calendario = $disciplinaCursada->getEnturmacao()->getTurma()->getCalendario();
        $numeroAulas = $calendario->getQuantidadeDiasEfetivos(
            $disciplinaCursada->getEnturmacao()->getTurma()->getPeriodo()
        );
        if ($numeroAulas <= 0) {
            throw new IllegalOperationException('O cálculo de frequência não pôde ser realizado: o calendário ou período da turma não possui dias efetivos');
        }
        foreach ($disciplinaCursada->getMedias() as $media) {
            $faltas += $media->getFaltas();
        }
        return round(100 - ($faltas * 100) / $numeroAulas, 2);
    }
    
    /**
    * Calcula a frequência da disciplina baseando-se em aulas, onde cada falta representa 
    * uma aula da disciplina em que o aluno não compareceu, considerando a carga horária 
    * total da disciplina como 100%.
    * 
    * Este cálculo retornará uma frequência individual da disciplina, podendo causar
    * a reprovação do aluno em disciplinas isoladas.
    * 
    * @param DisciplinaCursada $disciplinaCursada
    * @return float porcentagem de frequência calculada
    */
    function calcularFrequenciaPorAula(DisciplinaCursada $disciplinaCursada, $faltas = 0) {
        $faltas += $disciplinaCursada->getTotalFaltas();
        $cargaHoraria = $disciplinaCursada->getDisciplina()->getCargaHoraria();
        $duracaoAula = $disciplinaCursada->getDisciplina()->getEtapa()
                ->getSistemaAvaliacao()->getHoraAula();
        $numeroAulas = floor($cargaHoraria / ($duracaoAula / 60));
        if ($numeroAulas <= 0) {
            throw new IllegalOperationException('O cálculo de frequência não pôde ser realizado: duração de aulas ou carga horária das disciplinas não podem ser zero');
        }
        return round(100 - ($faltas * 100) / $numeroAulas, 2);
    }
    
    /**
     * Calcula a frequência do aluno baseando-se em aulas, mas usando como referência 
     * a etapa inteira, ou seja, o total de faltas de todas as disciplinas x total da carga horária da etapa.
     * 
     * Na prática, este cálculo faz com que todas as disciplinas produzam a mesma frequência, assim como na
     * frequência por dia.
     * 
     * @param DisciplinaCursada $disciplinaCursada
     * @return type
     * @throws IllegalOperationException
     */
    function calcularFrequenciaGeralPorEnturmacao(DisciplinaCursada $disciplinaCursada) {
        if (!$disciplinaCursada->getEnturmacao()) {
            return $this->calcularFrequenciaPorAula($disciplinaCursada);
        }
        $disciplinasCursadas = $disciplinaCursada->getEnturmacao()->getDisciplinasCursadas();
        $totalHoras = 0;
        $totalFaltas = 0;
        foreach ($disciplinasCursadas as $d) {
            $totalFaltas += $d->getTotalFaltas();
            $totalHoras += $d->getDisciplina()->getCargaHoraria();
        }
        $duracaoAula = $disciplinaCursada->getDisciplina()->getEtapa()->getSistemaAvaliacao()->getHoraAula();
        $numeroAulas = floor($totalHoras / ($duracaoAula / 60));
        if ($numeroAulas <= 0) {
            throw new IllegalOperationException('O cálculo de frequência não pôde ser realizado: duração de aulas ou carga horária das disciplinas não podem ser zero');
        }
        return round(100 - ($totalFaltas * 100) / $numeroAulas, 2);
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
