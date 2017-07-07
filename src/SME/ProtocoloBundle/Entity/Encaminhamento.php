<?php

namespace SME\ProtocoloBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\CommonsBundle\Entity\PessoaFisica;

/**
 * @ORM\Entity()
 * @ORM\Table(name="jos_protocolo_encaminhamento")
 */
class Encaminhamento {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
     * @ORM\JoinColumn(name="pessoa_encaminha_id", referencedColumnName="id")
     */
    private $pessoaEncaminha;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
     * @ORM\JoinColumn(name="pessoa_recebe_id", referencedColumnName="id")
     */
    private $pessoaRecebe;
    
    /**
     * @ORM\ManyToOne(targetEntity="MotivoEncaminhamento")
     * @ORM\JoinColumn(name="motivo_id", referencedColumnName="id")
     */
    private $motivo;
    
    /**
     * @ORM\Column(name="data_cadastro", type="datetime")
     */
    private $dataCadastro;
    
    /**
     * @ORM\Column()
     */
    private $recebido;
    
    /**
     * @ORM\Column(name="data_recebimento", type="datetime")
     */
    private $dataRecebimento;
    
    /**
     * @ORM\ManyToOne(targetEntity="Protocolo", inversedBy="encaminhamentos")
     */
    private $protocolo;
    
    /**
     * @ORM\Column()
     */
    private $observacao;
    
    public function getId() {
        return $this->id;
    }
    
    public function getPessoaEncaminha() {
        return $this->pessoaEncaminha;
    }

    public function setPessoaEncaminha(PessoaFisica $pessoaEncaminha) {
        $this->pessoaEncaminha = $pessoaEncaminha;
    }

    public function getPessoaRecebe() {
        return $this->pessoaRecebe;
    }

    public function setPessoaRecebe(PessoaFisica $pessoaRecebe) {
        $this->pessoaRecebe = $pessoaRecebe;
    }

    public function getMotivo() {
        return $this->motivo;
    }

    public function setMotivo(MotivoEncaminhamento $motivo=null) {
        $this->motivo = $motivo;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function getRecebido() {
        return $this->recebido;
    }

    public function setRecebido($recebido) {
        $this->recebido = $recebido;
    }

    public function getDataRecebimento() {
        return $this->dataRecebimento;
    }

    public function setDataRecebimento($dataRecebimento) {
        $this->dataRecebimento = $dataRecebimento;
    }

    public function getProtocolo() {
        return $this->protocolo;
    }

    public function setProtocolo(Protocolo $protocolo) {
        $this->protocolo = $protocolo;
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }
    
}
