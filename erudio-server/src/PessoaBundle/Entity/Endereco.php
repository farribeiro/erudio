<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace PessoaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "sme_endereco")
*/
class Endereco extends AbstractEditableEntity {
    
    /**
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column
    */
    private $logradouro;
    
    /**
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(type = "integer", nullable = true)
    */
    private $numero;
    
    /**
    * @JMS\Groups({"DETAILS"}) 
    * @ORM\Column(nullable = true)
    */
    private $complemento;
    
    /**
    * @JMS\Groups({"DETAILS"}) 
    * @ORM\Column(nullable = false, length = 8) 
    */
    private $cep;
    
    /**
    * @JMS\Groups({"DETAILS"}) 
    * @ORM\Column(nullable = true)
    */
    private $bairro;
    
    /**
    * @JMS\Groups({"LIST"})
    * @ORM\ManyToOne(targetEntity = "Cidade")
    * @ORM\JoinColumn(nullable = false) 
    */
    private $cidade;
    
    /**
    * @JMS\Groups({"DETAILS"}) 
    * @ORM\Column(nullable = true)
    */
    private $latitude;
    
    /**
    * @JMS\Groups({"DETAILS"}) 
    * @ORM\Column(nullable = true)
    */
    private $longitude;
    
    function getLogradouro() {
        return $this->logradouro;
    }

    function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
    }

    function getNumero() {
        return $this->numero;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function getComplemento() {
        return $this->complemento;
    }

    function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    function getCep() {
        return $this->cep;
    }

    function setCep($cep) {
        $this->cep = $cep;
    }

    function getBairro() {
        return $this->bairro;
    }

    function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    function getCidade() {
        return $this->cidade;
    }

    function setCidade(Cidade $cidade) {
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
    
    function getEnderecoCompleto() {
        return "{$this->getLogradouro()}, {$this->getNumero()}, {$this->getBairro()}, "
            . "CEP {$this->getCep()}, {$this->getCidade()->getNome()} - "
            . "{$this->getCidade()->getEstado()->getSigla()}";
    }
    
}
