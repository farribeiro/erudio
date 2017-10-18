<?php

namespace SME\DGPFormacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_dgp_formacao")
 */
class Formacao implements \JsonSerializable {
	
    const MODELO_CERTIFICADO_PADRAO = 'CertificadoReport';
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /** @ORM\Column() */
    private $nome;
    
    /** @ORM\Column(name="nome_certificado") */
    private $nomeCertificado;

    /** @ORM\Column(name="publico_alvo") */
    private $publicoAlvo;
    
    /** @ORM\Column(name="carga_horaria") */
    private $cargaHoraria;

    /** @ORM\Column(name="limite_vagas") */
    private $limiteVagas = null;

    /** @ORM\Column(type="datetime", name="data_inicio_inscricao") */
    private $dataInicioInscricao;

    /** @ORM\Column(type="datetime", name="data_termino_inscricao") */
    private $dataTerminoInscricao;

    /** @ORM\Column(type="date", name="data_inicio_formacao") */
    private $dataInicioFormacao;

    /** @ORM\Column(type="date", name="data_termino_formacao") */
    private $dataTerminoFormacao;

    /** @ORM\Column() */
    private $descricao = '';

    /** @ORM\Column(type="boolean") */
    private $aberto = false;
    
    /** @ORM\Column(type="boolean") */
    private $publicado = true;
    
    /** @ORM\Column(name="modelo_certificado") */
    private $modeloCertificado;
    
    /** @ORM\Column(type="boolean") */
    private $ativo = true;

    /** @ORM\OneToMany(targetEntity="Matricula", mappedBy="formacao", cascade={"all"}, orphanRemoval=true) */
    private $matriculas;
    
    /** @ORM\OneToMany(targetEntity="Encontro", mappedBy="formacao", cascade={"all"}, orphanRemoval=true) */
    private $encontros;

    public function __construct() {
        $this->modeloCertificado = self::MODELO_CERTIFICADO_PADRAO; 
        $this->matriculas = new ArrayCollection();
        $this->encontros = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getNomeCertificado() {
        return $this->nomeCertificado ? $this->nomeCertificado : $this->nome;
    }

    public function setNomeCertificado($nomeCertificado) {
        $this->nomeCertificado = $nomeCertificado;
    }

    public function getPublicoAlvo() {
        return $this->publicoAlvo;
    }

    public function setPublicoAlvo($publicoAlvo) {
        $this->publicoAlvo = $publicoAlvo;
    }

    public function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    public function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }

    public function getLimiteVagas() {
        return $this->limiteVagas;
    }

    public function setLimiteVagas($limiteVagas) {
        $this->limiteVagas = $limiteVagas;
    }

    public function getDataInicioInscricao() {
        return $this->dataInicioInscricao;
    }

    public function setDataInicioInscricao($dataInicioInscricao) {
        $this->dataInicioInscricao = $dataInicioInscricao;
    }

    public function getDataTerminoInscricao() {
        return $this->dataTerminoInscricao;
    }

    public function setDataTerminoInscricao($dataTerminoInscricao) {
        $this->dataTerminoInscricao = $dataTerminoInscricao;
    }

    public function getDataInicioFormacao() {
        return $this->dataInicioFormacao;
    }

    public function setDataInicioFormacao($dataInicioFormacao) {
        $this->dataInicioFormacao = $dataInicioFormacao;
    }

    public function getDataTerminoFormacao() {
        return $this->dataTerminoFormacao;
    }

    public function setDataTerminoFormacao($dataTerminoFormacao) {
        $this->dataTerminoFormacao = $dataTerminoFormacao;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getAberto() {
        return $this->aberto;
    }

    public function setAberto($aberto) {
        $this->aberto = $aberto;
    }
    
    public function getPublicado() {
        return $this->publicado;
    }

    public function setPublicado($publicado) {
        $this->publicado = $publicado;
    }
  
    public function getModeloCertificado() {
        return $this->modeloCertificado;
    }

    public function setModeloCertificado($modeloCertificado) {
        $this->modeloCertificado = $modeloCertificado;
    }
        
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    /**
     * @return array Matrículas ativas da formação
     */
    public function getMatriculas() {
        $iterator = $this->matriculas->getIterator();
        $iterator->uasort(function ($a, $b) {
            return \strcmp($a->getPessoa()->getNome(), $b->getPessoa()->getNome());
        });
        $this->matriculas = new ArrayCollection(\iterator_to_array($iterator));
        return $this->matriculas->filter(function($m) { return $m->getAtivo(); });
    }
    
    /**
     * @return array Encontros ativos da formação
     */
    public function getEncontros() {
        return $this->encontros->filter(function($e) { return $e->getAtivo(); });
    }

    public function setMatriculas($matriculas) {
        $this->matriculas = $matriculas;
    }

    public function getVagasDisponiveis() {
        return $this->limiteVagas > 0 ? $this->limiteVagas - count($this->getMatriculas()) : 1; 
    }
    
    public function isDataInscricaoValida() {
        if ($this->getDataInicioInscricao() && $this->getDataTerminoInscricao()) {
            $difference = $this->getDataInicioInscricao()->diff($this->getDataTerminoInscricao());
            return !$difference->invert;
        }
        return false;
    }

    public function isDataFormacaoValida() {
        if ($this->getDataInicioFormacao() && $this->getDataTerminoFormacao()) {
            $difference = $this->getDataInicioFormacao()->diff($this->getDataTerminoFormacao());
            return !$difference->invert;
        }
        return false;
    }

    public function isDataInscricaoFormacaoValida() {
        if ($this->getDataInicioFormacao() && $this->getDataTerminoInscricao()) {
            $difference = $this->getDataTerminoInscricao()->diff($this->getDataInicioFormacao());
            return !$difference->invert || $difference->d === 0;
        }
        return false;
    }

    public function isCargaHorariaValida() {
        return $this->cargaHoraria > 0;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'nome' => $this->nome,
            'publicoAlvo' => $this->publicoAlvo,
            'descricao' => $this->descricao,
            'dataInicio' => $this->dataInicioFormacao->format('d/m/Y'),
            'dataTermino' => $this->dataTerminoFormacao->format('d/m/Y'),
            'cargaHoraria' => $this->cargaHoraria,
            'limiteVagas' => $this->limiteVagas > 0 ? $this->limiteVagas : null,
            'vagasDisponiveis' => $this->getVagasDisponiveis()
        );
    }
    
}