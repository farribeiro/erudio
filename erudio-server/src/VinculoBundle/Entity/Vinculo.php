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

namespace VinculoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_vinculo")
*/
class Vinculo extends AbstractEditableEntity {
    
    const STATUS_ATIVO = "ATIVO",
          STATUS_AFASTADO = "AFASTADO",
          STATUS_DESLIGADO = "DESLIGADO";
    
    const CONTRATO_EFETIVO = "EFETIVO",
          CONTRATO_TEMPORARIO = "TEMPORARIO",
          CONTRATO_COMISSIONADO = "COMISSIONADO";
    
    /**
    * @JMS\Groups({"LIST"})   
    * @ORM\Column
    */
    private $codigo;
    
    /**
    * @JMS\Groups({"LIST"})  
    * @ORM\Column(type = "string", nullable = false) 
    */
    private $status;
    
    /**
    * @JMS\Groups({"LIST"})  
    * @ORM\Column(name="tipo_contrato", type = "string", nullable = false) 
    */
    private $tipoContrato;
    
    /**
    * @JMS\Groups({"LIST"})  
    * @ORM\Column(name = "carga_horaria", type = "integer", nullable = false) 
    */
    private $cargaHoraria;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @JMS\MaxDepth(depth = 1)
    * @JMS\Type("PessoaBundle\Entity\PessoaFisica")
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\PessoaFisica")
    * @ORM\JoinColumn(name = "pessoa_fisica_funcionario_id") 
    */
    private $funcionario;
    
    /**
    * @JMS\Groups({"LIST"}) 
    * @ORM\ManyToOne(targetEntity = "Cargo")
    */
    private $cargo;
    
    /**
    * @JMS\Groups({"LIST"}) 
    * @JMS\MaxDepth(depth = 1)
    * @JMS\Type("PessoaBundle\Entity\Instituicao")
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\Instituicao") 
    */
    private $instituicao;
    
    /**
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "Alocacao", mappedBy = "vinculo", fetch="EXTRA_LAZY")
    */
    private $alocacoes;
    
    public function init() {
        $this->status = self::STATUS_ATIVO;
    }
    
    function getCodigo() {
        return $this->codigo;
    }
    
    function definirCodigo($codigo) {
        if($this->codigo == null) {
            $this->codigo = $codigo;
        }
    }

    function getStatus() {
        return $this->status;
    }

    function getTipoContrato() {
        return $this->tipoContrato;
    }

    function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    function getFuncionario() {
        return $this->funcionario;
    }

    function getCargo() {
        return $this->cargo;
    }

    function getInstituicao() {
        return $this->instituicao;
    }

    function setStatus($status) {
        $this->status = $status;
    }
    
    function setCargo($cargo) {
        $this->cargo = $cargo;
    }
    
    function setTipoContrato($tipoContrato) {
        $this->tipoContrato = $tipoContrato;
    }
   
    function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }
    
    /**
     * @JMS\VirtualProperty
     */
    function getAlocacoes() {
        return $this->alocacoes->matching(
            Criteria::create()->where(Criteria::expr()->eq('ativo', true))
        );
    }
    
    function getAlocacoes() {
        return $this->alocacoes;
    }   
}
