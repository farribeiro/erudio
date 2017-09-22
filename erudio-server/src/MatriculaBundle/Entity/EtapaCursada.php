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

namespace MatriculaBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;
use MatriculaBundle\Entity\Matricula;
use MatriculaBundle\Entity\Enturmacao;
use CursoBundle\Entity\Etapa;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_matricula_etapa")
*/
class EtapaCursada extends AbstractEditableEntity {
    
    const STATUS_APROVADO = 'APROVADO';
    const STATUS_REPROVADO = 'REPROVADO';
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @JMS\MaxDepth(depth = 2)
    * @ORM\ManyToOne(targetEntity = "Matricula", inversedBy = "etapasCursadas") 
    */
    private $matricula;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @JMS\MaxDepth(depth = 1)
    * @ORM\ManyToOne(targetEntity = "CursoBundle\Entity\Etapa") 
    */
    private $etapa;
    
    /**  
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(type = "integer", nullable = false) 
    */
    private $ano;
    
    /**  
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(nullable = false) 
    */
    private $unidadeEnsino;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @JMS\MaxDepth(depth = 2)
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\Cidade") 
    */
    private $cidade;
    
    /**  
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(nullable = false) 
    */
    private $status = self::STATUS_APROVADO;
    
    /** 
    * @JMS\Groups({"DETAILS"}) 
    * @JMS\MaxDepth(depth = 1)
    * @ORM\ManyToOne(targetEntity = "Enturmacao")
    */
    private $enturmacao;
    
    /**
    * @JMS\Exclude
    * @ORM\Column(type = "boolean", name = "insercao_manual")
    */
    private $insercaoManual = true;
    
    function __construct(Matricula $matricula, Etapa $etapa, $ano, $unidadeEnsino, $cidade,
            $status = self::STATUS_APROVADO, Enturmacao $enturmacao = null) {
        $this->matricula = $matricula;
        $this->etapa = $etapa;
        $this->ano = $ano;
        $this->unidadeEnsino = $unidadeEnsino;
        $this->cidade = $cidade;
        $this->status = $status;
        $this->enturmacao = $enturmacao;
        $this->insercaoManual = false;
    }

    /**
    * @JMS\Groups({"LIST"})
    * @JMS\Type("boolean")
    * @JMS\VirtualProperty
    */   
    function getAuto() {
        return !$this->insercaoManual;
    }
    
    function getAno() {
        return $this->ano;
    }

    function getStatus() {
        return $this->status;
    }

    function getUnidadeEnsino() {
        return $this->unidadeEnsino;
    }
    
    function getCidade() {
        return $this->cidade;
    }
    
    function getMatricula() {
        return $this->matricula;
    }

    function getEtapa() {
        return $this->etapa;
    }

    function getEnturmacao() {
        return $this->enturmacao;
    }

    function setAno($ano) {
        $this->ano = $ano;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setUnidadeEnsino($unidadeEnsino) {
        $this->unidadeEnsino = $unidadeEnsino;
    }    
    
    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

}
