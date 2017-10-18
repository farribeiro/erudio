<?php

namespace SME\ProtocoloBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="jos_protocolo_documento")
 */
class InformacaoDocumento {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /**
     * @ORM\Column(name="campo", nullable=false)
     */
    private $nomeCampo;
    
    /**
     * @ORM\Column(nullable=false)
     */
    private $valor;
    
    /**
     * @ORM\ManyToOne(targetEntity="Protocolo", inversedBy="informacoesDocumento")
     */
    private $protocolo;
    
    public function getId() {
        return $this->id;
    }
    
    public function getNomeCampo() {
        return $this->nomeCampo;
    }

    public function setNomeCampo($nomeCampo) {
        $this->nomeCampo = $nomeCampo;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getProtocolo() {
        return $this->protocolo;
    }

    public function setProtocolo($protocolo) {
        $this->protocolo = $protocolo;
    }
    
}
