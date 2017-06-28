<?php

namespace SigAlimentarExtensionBundle\Model;

use MatriculaBundle\Entity\Enturmacao;

class MatriculaSig {
    
    const tabelaStatus = [
        'APROVADO' => 1,
        'REPROVADO' => 2,
        'EM_ANDAMENTO' => 3,
        'TRANSFERIDO' => 4,
        'RECLASSIFICADO' => 5,
        'DEIXOU_DE_FREQUENTAR' => 6,
        'EM_EXAME' => 7
    ];
    
    public $status;
    public $cod_matricula;
    public $cod_aluno;
    public $aluno;
    public $dataNascimento;
    public $codigoPessoa;
    public $turma;
    public $serie;
    public $curso;
    public $cod_escola;
    public $turno;
    
    function __construct($status, $cod_matricula, $cod_aluno, $aluno, $dataNascimento, 
            $codigoPessoa, $turma, $serie, $curso, $cod_escola, $turno) {
        $this->status = $status;
        $this->cod_matricula = $cod_matricula;
        $this->cod_aluno = $cod_aluno;
        $this->aluno = $aluno;
        $this->dataNascimento = $dataNascimento;
        $this->codigoPessoa = $codigoPessoa;
        $this->turma = $turma;
        $this->serie = $serie;
        $this->curso = $curso;
        $this->cod_escola = $cod_escola;
        $this->turno = $turno;
    }
    
    static function fromEnturmacao(Enturmacao $enturmacao) {
        return new MatriculaSig(
            $enturmacao->getEncerrado() ? 4 : 3,
            $enturmacao->getId(),
            $enturmacao->getMatricula()->getCodigo(),
            $enturmacao->getAluno()->getNome(),
            $enturmacao->getAluno()->getDataNascimento()->format('d/m/Y'),
            $enturmacao->getAluno()->getId(),
            $enturmacao->getTurma()->getNome(),
            $enturmacao->getTurma()->getEtapa()->getNome(),
            $enturmacao->getMatricula()->getCurso()->getNome(),
            $enturmacao->getTurma()->getUnidadeEnsino()->getId(),
            $enturmacao->getTurma()->getTurno()->getNome()
        );
    }

}
