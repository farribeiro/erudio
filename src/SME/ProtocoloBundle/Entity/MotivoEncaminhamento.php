<?php

namespace SME\ProtocoloBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\ProtocoloBundle\Entity\Categoria;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="jos_protocolo_encaminhamento_motivo")
 */
class MotivoEncaminhamento {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false, unique=true) */
    private $descricao;
    
    /** 
    * @ORM\ManyToOne(targetEntity="Categoria")
    * @ORM\JoinColumn(name="categoria_aplicavel_id", referencedColumnName="id") 
    */
    private $categoriaAplicavel;
    
    public function getId() {
        return $this->id;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
    
    public function getCategoriaAplicavel() {
        return $this->categoriaAplicavel;
    }

    public function setCategoriaAplicavel(Categoria $categoriaAplicavel) {
        $this->categoriaAplicavel = $categoriaAplicavel;
    }
    
}
