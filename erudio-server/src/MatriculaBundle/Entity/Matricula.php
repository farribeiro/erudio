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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use CoreBundle\ORM\AbstractEditableEntity;
use PessoaBundle\Entity\UnidadeEnsino;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_matricula")
*/
class Matricula extends AbstractEditableEntity {
    
    const STATUS_CURSANDO = "CURSANDO",
          STATUS_APROVADO = "APROVADO",
          STATUS_REPROVADO = "REPROVADO",
          STATUS_CANCELADO = "CANCELADO",
          STATUS_TRANCADO = "TRANCADO",
          STATUS_ABANDONO = "ABANDONO",
          STATUS_FALECIDO = "FALECIDO";
  
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
    * @ORM\Column(type = "datetime", nullable = true)
    */
    private $dataEncerramento;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @JMS\MaxDepth(depth = 1)
    * @JMS\Type("PessoaBundle\Entity\PessoaFisica")
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\PessoaFisica")
    * @ORM\JoinColumn(name = "pessoa_fisica_aluno_id")
    */
    private $aluno;
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\MaxDepth(depth = 1)
    * @ORM\ManyToOne(targetEntity = "CursoBundle\Entity\Curso") 
    */
    private $curso;
    
    /**
    * @JMS\Exclude
    * @ORM\ManyToOne(targetEntity = "CursoBundle\Entity\Etapa") 
    */
    private $etapa;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\MaxDepth(depth = 2)
    * @JMS\Type("PessoaBundle\Entity\UnidadeEnsino")
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\UnidadeEnsino")
    * @ORM\JoinColumn(name = "unidade_ensino_id")
    */
    private $unidadeEnsino;
    
    /**
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "EtapaCursada", mappedBy = "matricula", fetch="EXTRA_LAZY") 
    */
    private $etapasCursadas;
    
    /**
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "DisciplinaCursada", mappedBy = "matricula", fetch="EXTRA_LAZY") 
    */
    private $disciplinasCursadas;
    
    /**
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "Enturmacao", mappedBy = "matricula", fetch="EXTRA_LAZY") 
    */
    private $enturmacoes;
    
    function init() {
        $this->enturmacoes = new ArrayCollection();
        $this->status = self::STATUS_CURSANDO;
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
    
    function getDataEncerramento() {
        return $this->dataEncerramento;
    }

    function getAluno() {
        return $this->aluno;
    }

    function getCurso() {
        return $this->curso;
    }
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty
    */
    function getNomeAluno() {
        return $this->aluno->getNome();
    }
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty
    * @JMS\MaxDepth(depth = 1)
    */
    function getEtapaAtual() {
        return $this->etapa ? $this->etapa : $this->redefinirEtapa(); //retirar posteriormente
    }

    function getUnidadeEnsino() {
        return $this->unidadeEnsino;
    }
    
    function setStatus($status) {
        $this->status = $status;
        if ($this->status != self::STATUS_CURSANDO) {
            $this->dataEncerramento = new \DateTime();
        }
    }
    
    function redefinirEtapa() {
        if ($this->getEnturmacoesAtivas()->count() > 0) {
            $this->etapa = $this->getEnturmacoesAtivas()->first()->getTurma()->getEtapa();
        }
        return $this->etapa;
    }
    
    function resetarEtapa() {
        $this->etapa = null;
    }

    function transferir(UnidadeEnsino $unidadeEnsino) {
        $this->unidadeEnsino = $unidadeEnsino;
        foreach ($this->enturmacoes as $enturmacao) {
            $enturmacao->encerrar();
        }
    }
    
    function getEtapasCursadas() {
        return $this->etapasCursadas->matching(
            Criteria::create()->where(Criteria::expr()->eq('ativo', true))
        );
    }
    
    function getDisciplinasCursadas() {
        return $this->disciplinasCursadas->matching(
            Criteria::create()->where(Criteria::expr()->eq('ativo', true))
        );
    }

    function getEnturmacoes() {
        return $this->enturmacoes->matching(
            Criteria::create()->where(Criteria::expr()->eq('ativo', true))
        );
    }
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @JMS\VirtualProperty
    * @JMS\MaxDepth(depth = 3)
    * @JMS\Type("ArrayCollection<MatriculaBundle\Entity\Enturmacao>")
    */
    function getEnturmacoesAtivas() {
        return $this->enturmacoes->matching(
            Criteria::create()->where(Criteria::expr()->andX(              
                Criteria::expr()->eq('ativo', true),
                Criteria::expr()->eq('encerrado', false)
            ))
        );  
    }
    
}
