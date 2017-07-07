<?php

namespace SME\CommonsBundle\Entity;

use SME\CommonsBundle\Entity\PessoaFisica;
use SME\CommonsBundle\Entity\GrauFormacao;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_pessoa_fisica_formacao")
 */
class Formacao {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @ORM\Column */
	private $nome;
	
	/**
	 * @ORM\Column(name="carga_horaria", type="integer")
	 * */
	private $cargaHoraria;
	
	/**
	 * @ORM\Column(name="data_conclusao", type="date")
	 * */
	private $dataConclusao;
	
	/**
	 * @ORM\Column(name="instituicao_nome")
	 * */
	private $instituicao;
	
	/**
	 * @ORM\ManyToOne(targetEntity="GrauFormacao")
	 * @ORM\JoinColumn(name="grau_formacao_id", referencedColumnName="id")
	 */
	private $grauFormacao;
	
	/**
                * @ORM\ManyToOne(targetEntity="PessoaFisica", inversedBy="formacoes")
                * @ORM\JoinColumn(name="pessoa_fisica_id")
                */
	private $pessoaFisica;

	public function getId() {
 		return $this->id;
 	}

	public function setId($id) {
 		$this->id = $id;
 	}

	public function getNome() {
 		return $this->nome;
 	}

	public function setNome($nome) {
 		$this->nome = $nome;
 	}

	public function getCargaHoraria() {
 		return $this->cargaHoraria;
 	}

	public function setCargaHoraria($cargaHoraria) {
 		$this->cargaHoraria = $cargaHoraria;
 	}

	public function getDataConclusao() {
 		return $this->dataConclusao;
 	}

	public function setDataConclusao($dataConclusao) {
 		$this->dataConclusao = $dataConclusao;
 	}

	public function getInstituicao() {
 		return $this->instituicao;
 	}

	public function setInstituicao($instituicao) {
 		$this->instituicao = $instituicao;
 	}

	public function getGrauFormacao() {
 		return $this->grauFormacao;
 	}

	public function setGrauFormacao(GrauFormacao $grauFormacao) {
 		$this->grauFormacao = $grauFormacao;
 	}

	public function getPessoaFisica() {
 		return $this->pessoaFisica;
 	}

	public function setPessoaFisica(PessoaFisica $pessoaFisica) {
 		$this->pessoaFisica = $pessoaFisica;
 	}

}
?>