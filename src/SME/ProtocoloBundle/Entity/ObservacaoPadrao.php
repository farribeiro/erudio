<?php

namespace SME\ProtocoloBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="jos_protocolo_observacao")
 */
class ObservacaoPadrao {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\ManyToOne(targetEntity="Solicitacao") */
    private $solicitacao;
    
    /** @ORM\ManyToOne(targetEntity="Situacao") */
    private $situacao;
    
    /** @ORM\Column(name="observacao_padrao", nullable=false) */
    private $texto;
    
    public function getId() {
        return $this->id;
    }

    public function getSolicitacao() {
        return $this->solicitacao;
    }

    public function setSolicitacao($solicitacao) {
        $this->solicitacao = $solicitacao;
    }

    public function getSituacao() {
        return $this->situacao;
    }

    public function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }
    
}
