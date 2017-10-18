<?php

namespace SME\DGPProcessoAnualBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="sme_dgp_processo_anual_inscricao")
*/
class Inscricao {
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ProcessoAnual")
     * @ORM\JoinColumn(name="processo_anual_id", referencedColumnName="id")
     */
    private $processoAnual;
    
    /**
     * @ORM\ManyToOne(targetEntity="SME\DGPBundle\Entity\Vinculo")
     * @ORM\JoinColumn(name="vinculo_id", referencedColumnName="id")
     */
    private $vinculoServidor;
    
    /** @ORM\Column(nullable=false) */
    private $ano;
    
    /** @ORM\Column(name="data_cadastro", type="datetime", nullable=false) */
    private $dataCadastro;
    
    public function getId() {
        return $this->id;
    }

    public function getProcessoAnual() {
        return $this->processoAnual;
    }

    public function getVinculoServidor() {
        return $this->vinculoServidor;
    }

    public function getAno() {
        return $this->ano;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setProcessoAnual($processoAnual) {
        $this->processoAnual = $processoAnual;
    }

    public function setVinculoServidor($vinculoServidor) {
        $this->vinculoServidor = $vinculoServidor;
    }

    public function setAno($ano) {
        $this->ano = $ano;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

}
