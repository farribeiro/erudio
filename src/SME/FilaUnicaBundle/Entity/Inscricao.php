<?php

namespace SME\FilaUnicaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\CommonsBundle\Entity\PeriodoDia;
use SME\FilaUnicaBundle\Entity\Vaga;

/**
* @ORM\Entity
* @ORM\Table(name="sme_fu_inscricao")
*/
class Inscricao implements \JsonSerializable {
    
    const PONTUACAO_NULA = 99999;
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoInscricao")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    private $tipoInscricao;
    
    /** @ORM\Column(nullable=false) */
    private $protocolo;
    
    /**
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
     * @ORM\JoinColumn(name="pessoa_candidato_id", referencedColumnName="id")
     */
    private $crianca;
    
    /**
     * @ORM\ManyToOne(targetEntity="Zoneamento")
     */
    private $zoneamento;
    
    /**
     * @ORM\ManyToOne(targetEntity="UnidadeEscolar")
     * @ORM\JoinColumn(name="unidade_origem_id", referencedColumnName="id")
     */
    private $unidadeOrigem;
    
    /**
     * @ORM\ManyToOne(targetEntity="UnidadeEscolar")
     * @ORM\JoinColumn(name="unidade_destino_id", referencedColumnName="id")
     */
    private $unidadeDestino;
    
    /**
     * @ORM\ManyToOne(targetEntity="UnidadeEscolar")
     * @ORM\JoinColumn(name="unidade_destino_alternativa_id", referencedColumnName="id")
     */
    private $unidadeDestinoAlternativa;
    
    /** @ORM\Column(name="data_cadastro", type="datetime", nullable=false) */
    private $dataCadastro;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
     * @ORM\JoinColumn(name="pessoa_atendente_id", referencedColumnName="id")
     */
    private $atendente;
    
    /** @ORM\Column(name="data_modificacao", type="datetime", nullable=false) */
    private $dataModificacao;
    
    /** 
        * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
        * @ORM\JoinColumn(name="pessoa_modificacao_id", referencedColumnName="id")
        */
    private $pessoaUltimaModificacao;
    
    /** @ORM\Column(name="data_chamada", type="datetime") */
    private $dataChamada;
    
    /**
     * @ORM\ManyToOne(targetEntity="AnoEscolar")
     * @ORM\JoinColumn(name="ano_escolar_id", referencedColumnName="id")
     */
    private $anoEscolar;
    
    /**
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PeriodoDia")
     * @ORM\JoinColumn(name="periodo_dia_id", referencedColumnName="id")
     */
    private $periodoDia;
    
    /** @ORM\ManyToOne(targetEntity="Status") */
    private $status;
    
    /** @ORM\Column(name="renda_per_capita", nullable=false) */
    private $rendaPerCapita;
    
    /**
     * @ORM\ManyToOne(targetEntity="SituacaoFamiliar")
     * @ORM\JoinColumn(name="situacao_familiar_id", referencedColumnName="id")
     */
    private $situacaoFamiliar;
    
    /**
     * @ORM\Column(name="renda_pontuada")
     */
    private $rendaPontuada;
    
    /** 
     * @ORM\ManyToOne(targetEntity="Vaga", inversedBy="historicoChamadas")
     * @ORM\JoinColumn(name="vaga_ofertada_id") 
     */
    private $vagaOfertada;
    
    /** @ORM\Column(name="codigo_aluno") */
    private $codigoAluno;
    
    /** @ORM\Column(name="data_matricula", type="date") */
    private $dataMatricula;
    
    /** @ORM\Column(type="boolean", name="processo_judicial") */
    private $processoJudicial;
    
    /** @ORM\Column(name="processo_judicial_numero") */
    private $numeroOrdemJudicial;
    
    /** @ORM\Column(name="processo_judicial_data", type="datetime", nullable=false) */
    private $dataProcessoJudicial;
    
    /** @ORM\Column(name="movimentacao_interna") */
    private $movimentacaoInterna;
    
    /** @ORM\Column(nullable=false) */
    private $ativo;
    
    /** @ORM\OneToMany(targetEntity="Evento", mappedBy="inscricao", cascade={"all"}) */
    private $historico;
    
    /** @ORM\OneToMany(targetEntity="ComponenteRenda", mappedBy="inscricao", cascade={"all"}) */
    private $rendaDetalhada;
    
    public function __construct() {
        $this->processoJudicial = false;
        $this->movimentacaoInterna = false;
        $this->historico = new ArrayCollection();
        $this->rendaDetalhada = new ArrayCollection();
    }
    
    /**
     * Método para obter a renda pontuada do inscrito.
     * @return double Valor da renda após o cálculo
     */
    public function calcularPontuacao() {
        $this->rendaPontuada = $this->rendaPerCapita > 0
                ? $this->rendaPerCapita * $this->situacaoFamiliar->getPesoRenda()
                : self::PONTUACAO_NULA;
        return $this->rendaPontuada;
    }
    
    /**
     * Método que determina se uma inscrição é ou não publicamente visível na fila. Em geral,
     * inscrições de transferências e processos judiciais são visíveis apenas internamente, bem
     * como inscrições regulares que sofram movimentação interna em decorrência de erro cometido
     * no cadastro.
     * @return boolean Verdadeiro caso esta inscrição seja publicamente visível na fila
     */
    public function visivel() {
        return $this->tipoInscricao->getId() == TipoInscricao::REGULAR && !$this->movimentacaoInterna;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getHistorico() {
        return $this->historico;
    }
    
    public function setHistorico($historico) {
        $this->historico = $historico;
    }
 
    public function getRendaDetalhada() {
        return $this->rendaDetalhada;
    }

    public function setRendaDetalhada($rendaDetalhada) {
        $this->rendaDetalhada = $rendaDetalhada;
    }
    
    public function getRendaPontuada() {
        return $this->rendaPontuada;
    }

    public function setRendaPontuada($rendaPontuada) {
        $this->rendaPontuada = $rendaPontuada;
    }
  
    public function getProtocolo() {
        return $this->protocolo;
    }

    public function setProtocolo($protocolo) {
        $this->protocolo = $protocolo;
    }
    
    public function getCrianca() {
        return $this->crianca;
    }

    public function setCrianca(PessoaFisica $crianca) {
        $this->crianca = $crianca;
    }

    public function getZoneamento() {
        return $this->unidadeDestino->getZoneamento();
    }

    public function setZoneamento(Zoneamento $zoneamento) {
        $this->zoneamento = $zoneamento;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro(\DateTime $dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
    
    public function getDataModificacao() {
        return $this->dataModificacao;
    }

    public function setDataModificacao(\DateTime $dataModificacao) {
        $this->dataModificacao = $dataModificacao;
    }
    
    public function getPessoaUltimaModificacao() {
        return $this->pessoaUltimaModificacao;
    }

    public function setPessoaUltimaModificacao($pessoaUltimaModificacao) {
        $this->pessoaUltimaModificacao = $pessoaUltimaModificacao;
    }
  
    public function getDataChamada() {
        return $this->dataChamada;
    }

    public function setDataChamada(\DateTime $dataChamada = null) {
        $this->dataChamada = $dataChamada;
    }
    
    public function getAtendente() {
        return $this->atendente;
    }

    public function setAtendente(PessoaFisica $atendente) {
        $this->atendente = $atendente;
    }
    
    public function getAnoEscolar() {
        return $this->anoEscolar;
    }

    public function setAnoEscolar(AnoEscolar $anoEscolar = null) {
        $this->anoEscolar = $anoEscolar;
    }
    
    public function getStatus() {
        return $this->status;
    }

    public function setStatus(Status $status) {
        $this->status = $status;
    }
    
    public function getUnidadeOrigem() {
        return $this->unidadeOrigem;
    }

    public function setUnidadeOrigem(UnidadeEscolar $unidadeOrigem) {
        $this->unidadeOrigem = $unidadeOrigem;
    }

    public function getUnidadeDestino() {
        return $this->unidadeDestino;
    }

    public function setUnidadeDestino(UnidadeEscolar $unidadeDestino) {
        $this->unidadeDestino = $unidadeDestino;
    }
    
    public function getUnidadeDestinoAlternativa() {
        return $this->unidadeDestinoAlternativa ? $this->unidadeDestinoAlternativa : $this->unidadeDestino;
    }

    public function setUnidadeDestinoAlternativa($unidadeDestinoAlternativa) {
        $this->unidadeDestinoAlternativa = $unidadeDestinoAlternativa;
    }
    
    public function getTipoInscricao() {
        return $this->tipoInscricao;
    }

    public function setTipoInscricao(TipoInscricao $tipoInscricao) {
        $this->tipoInscricao = $tipoInscricao;
    }
    
    public function getPeriodoDia() {
        return $this->periodoDia;
    }

    public function setPeriodoDia(PeriodoDia $periodoDia) {
        $this->periodoDia = $periodoDia;
    }
    
    public function getRendaPerCapita() {
        return $this->rendaPerCapita;
    }

    public function setRendaPerCapita($rendaPerCapita) {
        $this->rendaPerCapita = $rendaPerCapita;
    }

    public function getSituacaoFamiliar() {
        return $this->situacaoFamiliar;
    }

    public function setSituacaoFamiliar(SituacaoFamiliar $situacaoFamiliar) {
        $this->situacaoFamiliar = $situacaoFamiliar;
    }
    
    public function getVagaOfertada() {
        return $this->vagaOfertada;
    }

    public function setVagaOfertada(Vaga $vagaOfertada = null) {
        $this->vagaOfertada = $vagaOfertada;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    public function getNumeroOrdemJudicial() {
        return $this->numeroOrdemJudicial;
    }

    public function setNumeroOrdemJudicial($ordemJudicial) {
        $this->numeroOrdemJudicial = $ordemJudicial;
    }
    
    public function getCodigoAluno() {
        return $this->codigoAluno;
    }

    public function getDataMatricula() {
        return $this->dataMatricula;
    }

    public function setCodigoAluno($codigoAluno) {
        $this->codigoAluno = $codigoAluno;
    }

    public function setDataMatricula($dataMatricula) {
        $this->dataMatricula = $dataMatricula;
    }
    
    public function getProcessoJudicial() {
        return $this->processoJudicial;
    }

    public function setProcessoJudicial($processoJudicial) {
        $this->processoJudicial = $processoJudicial;
    }
 
    public function getDataProcessoJudicial() {
        return $this->dataProcessoJudicial;
    }

    public function setDataProcessoJudicial($dataProcessoJudicial) {
        $this->dataProcessoJudicial = $dataProcessoJudicial;
    }
    
    public function getMovimentacaoInterna() {
        return $this->movimentacaoInterna;
    }

    public function setMovimentacaoInterna($movimentacaoInterna) {
        $this->movimentacaoInterna = $movimentacaoInterna;
    }
    
    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'protocolo' => $this->protocolo,
            'zoneamento' => $this->zoneamento->getNome() . ' - ' . $this->zoneamento->getDescricao(),
            'anoEscolar' => $this->anoEscolar->getNome(),
            'status' => $this->status->getNome(),
            'dataCadastro' => $this->dataCadastro->format('d/m/Y G:i:s')
        );
    }

}
