<?php

namespace SME\DGPPromocaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SME\DGPPromocaoBundle\Entity\Promocao;

/**
* @ORM\Entity()
* @ORM\Table(name="sme_dgp_promocao_horizontal")
*/
class PromocaoHorizontal extends Promocao {
    
    const CARGA_HORARIA_MINIMA = 40;
    
    /** @ORM\OneToMany(targetEntity="FormacaoInterna", mappedBy="promocaoHorizontal", cascade={"all"}, orphanRemoval=true) */
    private $formacoesInternas;
    
    /** @ORM\OneToMany(targetEntity="FormacaoExterna", mappedBy="promocaoHorizontal", cascade={"all"}, orphanRemoval=true) */
    private $formacoesExternas;
    
    public function __construct() {
        $this->formacoesInternas = new ArrayCollection();
        $this->formacoesExternas = new ArrayCollection();
    }
    
    public function getFormacoesInternas() {
        return $this->formacoesInternas;
    }

    public function getFormacoesExternas() {
        return $this->formacoesExternas;
    }
    
    public function getCargaHorariaAcumulada() {
        $ch = 0;
        foreach($this->formacoesInternas as $f) {
            $ch += $f->getMatricula()->getFormacao()->getCargaHoraria();
        }
        foreach($this->formacoesExternas as $f) {
            $ch += $f->getCargaHoraria();
        }
        return $ch;
    }

}
