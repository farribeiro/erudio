<?php

namespace SME\DGPPromocaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SME\DGPPromocaoBundle\Entity\Promocao;

/**
* @ORM\Entity()
* @ORM\Table(name="sme_dgp_promocao_vertical")
*/
class PromocaoVertical extends Promocao {
    
    const CARGA_HORARIA_MINIMA = 360;
    
    /** @ORM\Column(name="curso_nome") */
    private $nomeCurso;
    
    /** @ORM\Column(name="curso_data_conclusao", type="date") */
    private $dataConclusaoCurso;
    
    /** @ORM\Column(name="curso_carga_horaria") */
    private $cargaHorariaCurso;
    
    /** @ORM\Column(name="curso_instituicao") */
    private $instituicaoCurso;
    
    /**
        *  @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\GrauFormacao")
        *  @ORM\JoinColumn(name="curso_grau_id", referencedColumnName="id")
        */
    private $grauCurso;
    
    public function getNomeCurso() {
        return $this->nomeCurso;
    }

    public function getDataConclusaoCurso() {
        return $this->dataConclusaoCurso;
    }

    public function getCargaHorariaCurso() {
        return $this->cargaHorariaCurso;
    }

    public function getInstituicaoCurso() {
        return $this->instituicaoCurso;
    }

    public function getGrauCurso() {
        return $this->grauCurso;
    }

    public function setNomeCurso($nomeCurso) {
        $this->nomeCurso = $nomeCurso;
    }

    public function setDataConclusaoCurso($dataConclusaoCurso) {
        $this->dataConclusaoCurso = $dataConclusaoCurso;
    }

    public function setCargaHorariaCurso($cargaHorariaCurso) {
        $this->cargaHorariaCurso = $cargaHorariaCurso;
    }

    public function setInstituicaoCurso($instituicaoCurso) {
        $this->instituicaoCurso = $instituicaoCurso;
    }

    public function setGrauCurso($grauCurso) {
        $this->grauCurso = $grauCurso;
    }
    
}
