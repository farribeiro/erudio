<?php

namespace SME\FilaUnicaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SME\FilaUnicaBundle\Entity\Inscricao;
use SME\FilaUnicaBundle\Entity\UnidadeEscolar;
use SME\FilaUnicaBundle\Entity\AnoEscolar;
use SME\CommonsBundle\Entity\PeriodoDia;

/**
* @ORM\Entity
* @ORM\Table(name="sme_fu_vaga")
*/
class Vaga implements \JsonSerializable {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** 
     * @ORM\ManyToOne(targetEntity="UnidadeEscolar") 
     * @ORM\JoinColumn(name="unidade_id") 
     */
    private $unidadeEscolar;
    
    /** 
     * @ORM\ManyToOne(targetEntity="AnoEscolar")  
     * @ORM\JoinColumn(name="ano_escolar_id") 
     */
    private $anoEscolar;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PeriodoDia")  
     * @ORM\JoinColumn(name="periodo_dia_id") 
     */
    private $periodoDia;
    
    /** 
     * @ORM\ManyToOne(targetEntity="Inscricao")
     * @ORM\JoinColumn(name="inscricao_chamada_id") 
     */
    private $inscricaoEmChamada;
    
    /** @ORM\OneToMany(targetEntity="Inscricao", mappedBy="vagaOfertada") */
    private $historicoChamadas;
    
    /** @ORM\Column(name="data_cadastro", type="datetime") */
    private $dataCadastro;
    
    /** @ORM\Column(name="data_modificacao", type="datetime", nullable=false) */
    private $dataModificacao;
    
    /** @ORM\Column(name="data_matricula", type="datetime", nullable=false) */
    private $dataMatricula;
    
    /** @ORM\Column(name="matricula_confirmada", type="boolean", nullable=false) */
    private $matriculaConfirmada;

    /** @ORM\Column() */
    private $observacao;
    
    /** 
    * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
    * @ORM\JoinColumn(name="pessoa_cadastro_id", referencedColumnName="id")
    */
    private $pessoaCadastro;
    
    /** 
    * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
    * @ORM\JoinColumn(name="pessoa_modificacao_id", referencedColumnName="id")
    */
    private $pessoaModificacao;
    
    
    /** @ORM\Column(type="boolean", nullable=false) */
    private $ativo;
    
    public function __construct() {
        $this->historicoChamadas = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getUnidadeEscolar() {
        return $this->unidadeEscolar;
    }

    public function setUnidadeEscolar(UnidadeEscolar $unidadeEscolar) {
        $this->unidadeEscolar = $unidadeEscolar;
    }

    public function getAnoEscolar() {
        return $this->anoEscolar;
    }

    public function setAnoEscolar(AnoEscolar $anoEscolar) {
        $this->anoEscolar = $anoEscolar;
    }

    public function getInscricaoEmChamada() {
        return $this->inscricaoEmChamada;
    }

    public function setInscricaoEmChamada(Inscricao $inscricaoChamada = null) {
        $this->inscricaoEmChamada = $inscricaoChamada;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
    
    public function getDataModificacao() {
        return $this->dataModificacao;
    }

    public function setDataModificacao(\DateTime $dataModificacao) {
        $this->dataModificacao = $dataModificacao;
    }
   
    public function getDataMatricula() {
        return $this->dataMatricula;
    }

    public function setDataMatricula(\DateTime $dataMatricula = null) {
        $this->dataMatricula = $dataMatricula;
    }
 
    public function getPeriodoDia() {
        return $this->periodoDia;
    }

    public function setPeriodoDia(PeriodoDia $periodoDia) {
        $this->periodoDia = $periodoDia;
    }
    
    public function getMatriculaConfirmada() {
        return $this->matriculaConfirmada;
    }

    public function setMatriculaConfirmada($matriculaConfirmada) {
        $this->matriculaConfirmada = $matriculaConfirmada;
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }
    
    public function getPessoaCadastro() {
        return $this->pessoaCadastro;
    }

    public function setPessoaCadastro($pessoaCadastro) {
        $this->pessoaCadastro = $pessoaCadastro;
    }
    
    public function getPessoaModificacao() {
        return $this->pessoaModificacao;
    }

    public function setPessoaModificacao($pessoaModificacao) {
        $this->pessoaModificacao = $pessoaModificacao;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    public function getHistoricoChamadas() {
        return $this->historicoChamadas;
    }
    
    public function jsonSerialize() {
        return array(
            'inscricao' => $this->inscricaoEmChamada,
            'anoEscolar' => $this->anoEscolar->getNome(),
            'unidadeEscolar' => $this->unidadeEscolar->getEntidade()->getPessoaJuridica()->getNome()
        );
    }

}
