<?php

namespace SME\DGPPromocaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
* @ORM\Table(name="sme_dgp_promocao_horizontal_formacao_interna")
*/
class FormacaoInterna {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /**
        * @ORM\ManyToOne(targetEntity="PromocaoHorizontal", inversedBy="formacoesInternas")
        * @ORM\JoinColumn(name="promocao_horizontal_id", referencedColumnName="id")
        */
    private $promocaoHorizontal;
    
    /**
        * @ORM\ManyToOne(targetEntity="SME\DGPFormacaoBundle\Entity\Matricula")
        * @ORM\JoinColumn(name="formacao_matricula_id", referencedColumnName="id")
        */
    private $matricula;
    
    public function getId() {
        return $this->id;
    }

    public function getPromocaoHorizontal() {
        return $this->promocaoHorizontal;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function setPromocaoHorizontal($promocaoHorizontal) {
        $this->promocaoHorizontal = $promocaoHorizontal;
    }

    public function setMatricula($matricula) {
        $this->matricula = $matricula;
    }
    
}
