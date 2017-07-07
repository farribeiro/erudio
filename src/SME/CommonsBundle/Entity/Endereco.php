<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\CommonsBundle\Entity\Cidade;

/**
* @ORM\Entity
* @ORM\Table(name="sme_endereco")
*/
class Endereco {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $logradouro;
    
    /** @ORM\Column() */
    private $numero = 0;
    
    /** @ORM\Column() */
    private $complemento;
    
    /** @ORM\Column(nullable=false) */
    private $cep;
    
    /** @ORM\Column() */
    private $bairro;
    
    /** @ORM\OneToOne(targetEntity="Cidade") */
    private $cidade;
    
    /** @ORM\Column() */
    private $latitude;
    
    /** @ORM\Column() */
    private $longitude;
    
    public function getId() {
        return $this->id;
    }

    public function getLogradouro() {
        return $this->logradouro;
    }

    public function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function getComplemento() {
        return $this->complemento;
    }

    public function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    public function getCep() {
        return $this->cep;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function setCidade(Cidade $cidade) {
        $this->cidade = $cidade;
    }
    
    function getLatitude() {
        return $this->latitude;
    }

    function getLongitude() {
        return $this->longitude;
    }

    function setLatitude($latitude) {
        $this->latitude = $latitude;
    }

    function setLongitude($longitude) {
        $this->longitude = $longitude;
    }
    
}
