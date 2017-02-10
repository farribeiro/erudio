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
 * @ORM\Table(name = "sme_pessoa_telefone")
 */
class Telefone extends AbstractEditableEntity {
    
    const RESIDENCIAL = "RESIDENCIAL",
          CELULAR = "CELULAR",
          COMERCIAL = "COMERCIAL";
    
    /** 
    *   @JMS\Groups({"LIST"})   
    *   @ORM\Column(nullable = false) 
    */
    private $numero;
    
    /** 
    *   @JMS\Groups({"LIST"})   
    *   @ORM\Column(nullable = false) 
    */
    private $descricao;
    
    /** 
    * @JMS\Groups({"LIST"})   
    * @ORM\Column(name = "falar_com") 
    */
    private $falarCom;
    
    /**
    * @JMS\Type("PessoaBundle\Entity\Pessoa")
    * @ORM\ManyToOne(targetEntity = "Pessoa", inversedBy = "telefones") 
    */
    private $pessoa;
    
    public function getNumero() {
        return $this->numero;
    }

    public function setNumero($numero) {
        $this->numero = \str_replace(array('(', ')', '-'), '', $numero);
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getFalarCom() {
        return $this->falarCom;
    }

    public function setFalarCom($falarCom) {
        $this->falarCom = $falarCom;
    }
    
    public function getPessoa() {
        return $this->pessoa;
    }    
    
    public function getNumeroFormatado() {
        if(!\is_numeric($this->numero)) { $this->setNumero($this->numero); }
        $size = \strlen($this->numero);
        if($size < 10) {
            return \substr($this->numero, 0, $size - 4) . '-' . \substr($this->numero, $size - 4);
        } elseif($size <= 11) {
            return '(' . \substr($this->numero, 0, 2) . ')' . 
                \substr($this->numero, 2, $size - 6) . '-' . \substr($this->numero, $size - 4);
        } else {
            return $this->numero;
        }
    }
    
}