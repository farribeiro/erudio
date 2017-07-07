<?php

namespace SME\DGPPromocaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
* @ORM\Table(name="sme_dgp_promocao_horizontal_formacao_externa")
*/
class FormacaoExterna {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /**
        * @ORM\ManyToOne(targetEntity="PromocaoHorizontal", inversedBy="formacoesExternas")
        * @ORM\JoinColumn(name="promocao_horizontal_id", referencedColumnName="id")
        */
    private $promocaoHorizontal;
    
    /** @ORM\Column() */
    private $nome;
    
    /** @ORM\Column(name="carga_horaria") */
    private $cargaHoraria;
    
    /** @ORM\Column() */
    private $instituicao;
    
    /** @ORM\Column(name="data_conclusao", type="date") */
    private $dataConclusao;
    
    function getId() {
        return $this->id;
    }

    function getPromocaoHorizontal() {
        return $this->promocaoHorizontal;
    }

    function getNome() {
        return $this->nome;
    }

    function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    function getInstituicao() {
        return $this->instituicao;
    }

    function getDataConclusao() {
        return $this->dataConclusao;
    }

    function setPromocaoHorizontal($promocaoHorizontal) {
        $this->promocaoHorizontal = $promocaoHorizontal;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }

    function setInstituicao($instituicao) {
        $this->instituicao = $instituicao;
    }

    function setDataConclusao($dataConclusao) {
        $this->dataConclusao = $dataConclusao;
    }
    
}
