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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use MatriculaBundle\Traits\CalculosMedia;
use CoreBundle\ORM\AbstractEditableEntity;
use CursoBundle\Entity\Disciplina;
use CursoBundle\Entity\DisciplinaOfertada;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_matricula_disciplina")
*/
class DisciplinaCursada extends AbstractEditableEntity {
    
    use CalculosMedia;
    
    const STATUS_CURSANDO = "CURSANDO",
          STATUS_APROVADO = "APROVADO",
          STATUS_REPROVADO = "REPROVADO",
          STATUS_DISPENSADO = "DISPENSADO",
          STATUS_INCOMPLETO = 'INCOMPLETO',
          STATUS_EXAME = 'EM_EXAME';
    
    /**
    * @JMS\Groups({"LIST"})  
    * @ORM\Column(type = "string", nullable = false) 
    */
    private $status;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @JMS\MaxDepth(depth = 2)
    * @ORM\ManyToOne(targetEntity = "Matricula") 
    */
    private $matricula;
    
    /** 
    * @JMS\Groups({"LIST"})   
    * @ORM\ManyToOne(targetEntity = "CursoBundle\Entity\Disciplina") 
    */
    private $disciplina;
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\Type("float")
    * @ORM\Column(name = "media_final")
    */
    private $mediaFinal;
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\Type("float")
    * @ORM\Column(name = "frequencia_total")
    */
    private $frequenciaTotal;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @JMS\Type("DateTime<'Y-m-d\TH:i:s'>")
    * @ORM\Column(name="data_encerramento", type="datetime", nullable=false) 
    */
    protected $dataEncerramento;
    
    /**
    * @JMS\Groups({"DETAILS"})
     * @JMS\MaxDepth(depth = 1)
    * @ORM\ManyToOne(targetEntity = "Enturmacao", inversedBy="disciplinasCursadas") 
    */
    private $enturmacao;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\MaxDepth(depth = 2)
    * @ORM\ManyToOne(targetEntity = "CursoBundle\Entity\DisciplinaOfertada")
    * @ORM\JoinColumn(name = "turma_disciplina_id")  
    */
    private $disciplinaOfertada;
    
    /**  
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "Media", mappedBy = "disciplinaCursada", fetch = "EXTRA_LAZY")
    */
    private $medias;
    
    /**
    * @JMS\Exclude
    */
    private $mediaPreliminar;
    
    /**
    * @JMS\Exclude
    */
    private $frequenciaPreliminar;
    
    /**
    * @JMS\Exclude
    */
    private $statusPrevisto;
    
    function __construct(Matricula $matricula, Disciplina $disciplina) {
        $this->matricula = $matricula;
        $this->disciplina = $disciplina;
        $this->init();
    }
    
    function init() {
        $this->medias = new ArrayCollection();
        if (is_null($this->status)) {
            $this->status = self::STATUS_CURSANDO;
        }
    }
    
    /**
     * @JMS\Groups({"LIST"})
     * @JMS\VirtualProperty
     */
    function getNome() {
        return $this->disciplina->getNome();
    }
    
    /**
     * @JMS\Groups({"LIST"})
     * @JMS\VirtualProperty
     */
    function getNomeExibicao() {
        return $this->disciplina->getNomeExibicao();
    }
    
     /**
     * @JMS\Groups({"LIST"})
     * @JMS\VirtualProperty
     */
    function getSigla() {
        return $this->disciplina->getSigla();
    }
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty
    * @JMS\Type("integer")
    */
    function getAno() {
        return $this->dataEncerramento ? $this->dataEncerramento->format('Y') : date('Y');
    }
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty
    */
    function emAberto() {
        return $this->status === self::STATUS_CURSANDO 
                || $this->status === self::STATUS_EXAME 
                || is_null($this->mediaFinal);
    }
    
    /**
    * @JMS\Groups({"medias"})
    * @JMS\MaxDepth(depth = 2)
    * @JMS\Type("ArrayCollection<MatriculaBundle\Entity\Media>")
    * @JMS\VirtualProperty
    */
    function getMedias() {
        return $this->medias->matching(
            Criteria::create()->where(
                Criteria::expr()->eq('ativo', true)
            )->orderBy(['numero' => 'ASC'])
        );
    }
    
    /**
    * @JMS\Groups({"medias"})
    * @JMS\VirtualProperty
    */
    function getMediaPreliminar() {
        if (!$this->mediaPreliminar) {
            try {
                $this->mediaPreliminar = $this->calcularMediaFinal($this);
            } catch (\Exception $ex) {
                return null;
            }
        }
        return $this->mediaPreliminar;
    }
    
    /**
    * @JMS\Groups({"medias"})
    * @JMS\VirtualProperty
    */
    function getFrequenciaPreliminar() {
        if (!$this->frequenciaPreliminar) {
            try {
                $this->frequenciaPreliminar = $this->calcularFrequenciaTotal($this);
            } catch (\Exception $ex) {
                return null;
            }
        }
        return $this->frequenciaPreliminar;
    }
    
    /**
    * @JMS\Groups({"medias"})
    * @JMS\VirtualProperty
    */
    function getStatusPrevisto() {
        if (!$this->statusPrevisto) {
            $this->statusPrevisto = self::determinarStatus($this, true);
        }
        return $this->statusPrevisto;
    }
    
    function encerrar($status = null) {
        $statusFinal = $status ? $status : $this->status;
        if ($statusFinal != self::STATUS_CURSANDO && $statusFinal != self::STATUS_EXAME) {
            $this->status = $statusFinal;
            $this->dataEncerramento = new \DateTime();
        }
    }
    
    function atualizarStatus() {
        $this->status = self::determinarStatus($this);
    }
    
    static function determinarStatus(DisciplinaCursada $disciplina, $preliminar = false) {
        $mediaFinal = $preliminar ? $disciplina->getMediaPreliminar() : $disciplina->getMediaFinal();
        $frequenciaTotal = $preliminar ? $disciplina->getFrequenciaPreliminar() : $disciplina->getFrequenciaTotal();
        if (is_null($mediaFinal)) {
            return $disciplina->getStatus();
        }
        $sistemaAvaliacao = $disciplina->getDisciplina()->getEtapa()->getSistemaAvaliacao();
        if ($frequenciaTotal < $sistemaAvaliacao->getFrequenciaAprovacao()) {
            return self::STATUS_REPROVADO;
        }
        if ($disciplina->getStatus() === self::STATUS_EXAME) {
            $status = $mediaFinal >= $sistemaAvaliacao->getNotaAprovacaoExame() 
                    ? self::STATUS_APROVADO : self::STATUS_REPROVADO;
        } else if ($sistemaAvaliacao->getExame() === false) {
            $status = $mediaFinal >= $sistemaAvaliacao->getNotaAprovacao()
                    ? self::STATUS_APROVADO : self::STATUS_REPROVADO;
        } else {
            $status = $mediaFinal >= $sistemaAvaliacao->getNotaAprovacao()
                    ? self::STATUS_APROVADO : self::STATUS_EXAME;
        }
        return $status;
    }
    
    function vincularEnturmacao(Enturmacao $enturmacao, DisciplinaOfertada $disciplinaOfertada) {
        if ($enturmacao->getTurma()->getId() != $disciplinaOfertada->getTurma()->getId()) {
            throw new \InvalidArgumentException('A enturmação e a disciplina ofertada devem ser da mesma turma');
        }
        $this->enturmacao = $enturmacao;
        $this->disciplinaOfertada = $disciplinaOfertada;
    }
    
    function desvincularEnturmacao() {
        $this->enturmacao = null;
        $this->disciplinaOfertada = null;
    }
    
    function getMatricula() {
        return $this->matricula;
    }

    function getDisciplina() {
        return $this->disciplina;
    }
    
    function getEnturmacao() {
        return $this->enturmacao;
    }
    
    function getDisciplinaOfertada() {
        return $this->disciplinaOfertada;
    }
 
    function getStatus() {
        return $this->status;
    }
    
    function getMediaFinal() {
        return $this->mediaFinal;
    }

    function getFrequenciaTotal() {
        return $this->frequenciaTotal;
    }
    
    function getDataEncerramento() {
        return $this->dataEncerramento;
    }
    
    function setMediaFinal($mediaFinal) {
        $this->mediaFinal = $mediaFinal;
    }

    function setFrequenciaTotal($frequenciaTotal) {
        $this->frequenciaTotal = $frequenciaTotal;
    }
    
}
