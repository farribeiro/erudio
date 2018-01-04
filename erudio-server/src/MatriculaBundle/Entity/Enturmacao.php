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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;
use CursoBundle\Entity\Turma;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_enturmacao")
*/
class Enturmacao extends AbstractEditableEntity {
    
    const STATUS_APROVADO = 'APROVADO';
    const STATUS_REPROVADO = 'REPROVADO';
    const STATUS_EM_EXAME = 'EM_EXAME';
    const STATUS_TRANSFERIDO = 'TRANSFERIDO';
    const STATUS_DESLIGADO = 'DESLIGADO';
    const STATUS_CURSANDO = 'CURSANDO';
    
    /**  
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(type = "boolean") 
    */
    private $encerrado = false;
    
    /**  
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(type = "boolean") 
    */
    private $concluido = false;
    
    /**  
    * @JMS\Groups({"LIST"})
    * @JMS\MaxDepth(depth = 2)
    * @ORM\ManyToOne(targetEntity = "Matricula", inversedBy = "enturmacoes")
    * @ORM\JoinColumn(nullable = false) 
    */
    private $matricula;
    
    /**  
    * @JMS\Groups({"LIST"})
    * @JMS\MaxDepth(depth = 2)
    * @ORM\ManyToOne(targetEntity = "CursoBundle\Entity\Turma", inversedBy = "enturmacoes")
    * @ORM\JoinColumn(nullable = false)
    */
    private $turma;
    
    /** 
    * @JMS\Exclude
    * @ORM\OneToOne(targetEntity = "EtapaCursada", mappedBy = "enturmacao")
    */
    private $etapaCursada;
    
    /**  
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "DisciplinaCursada", mappedBy = "enturmacao")
    */
    private $disciplinasCursadas;
    
    /**
    * @JMS\Exclude
    * @ORM\OneToOne(targetEntity = "VagaBundle\Entity\Vaga", mappedBy="enturmacao")
    */
    private $vaga;
    
    function __construct(Matricula $matricula, Turma $turma) {
        $this->matricula = $matricula;
        $this->turma = $turma;
        $this->disciplinasCursadas = new ArrayCollection();
    }
    
    /**  
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty
    * @JMS\Type("boolean")
    */
    function isEmAndamento() {
        return !$this->encerrado && !$this->concluido;
    }
    
    function getStatus() {
        if ($this->concluido) {
            return $this->etapaCursada ? $this->etapaCursada->getStatus() : self::STATUS_CURSANDO;
        } else if ($this->encerrado) {
            return $this->matricula->isCursando() ? self::STATUS_TRANSFERIDO : self::STATUS_DESLIGADO;
        } else {
            return self::STATUS_CURSANDO;
        }
    }
    
    function isAprovado() {
        return !$this->getDisciplinasCursadas()->exists(function($k, $d) {
            return $d->getStatus() != DisciplinaCursada::STATUS_APROVADO &&
                   $d->getStatus() != DisciplinaCursada::STATUS_DISPENSADO;
        });
    }
    
    function getAluno() {
        return $this->matricula->getAluno();
    }
    
    function getAno() {
        return $this->turma->getAno();
    }
    
    function getDisciplinasCursadas() {
        return $this->disciplinasCursadas->matching(
            Criteria::create()->where(
                Criteria::expr()->eq('ativo', true)
            )->orderBy(['disciplina' => 'ASC'])
        );
    }
     
    function getAnosDefasagem(\DateTime $dataReferencia = null) {
        $idadeEtapa = $this->turma->getEtapa()->getIdadeRecomendada();
        $data = $dataReferencia 
                ? $dataReferencia 
                : \DateTime::createFromFormat('Y-m-d', date('Y') . '-03-31');
        $idadeAluno = $this->matricula->getAluno()->getDataNascimento()->diff($data);
        return $idadeEtapa ? $idadeAluno->y - $idadeEtapa : 0;
    }
    
    function concluir() {
        $this->concluido = true;
    }

    function encerrar() {
        $this->encerrado = true;
    }
    
    function getEtapaCursada() {
        return $this->etapaCursada;
    }
    
    function getEncerrado() {
        return $this->encerrado;
    }
    
    function getConcluido() {
        return $this->concluido;
    }
    
    function getMatricula() {
        return $this->matricula;
    }

    function getTurma() {
        return $this->turma;
    }
    
    function getVaga() {
        return $this->vaga;
    }

}
