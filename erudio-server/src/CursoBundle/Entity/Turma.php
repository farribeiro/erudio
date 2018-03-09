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

namespace CursoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_turma")
*/
class Turma extends AbstractEditableEntity {
    
    const STATUS_CRIADO = 'CRIADO'; // legado
    const STATUS_EM_ANDAMENTO = 'EM_ANDAMENTO';
    const STATUS_ENCERRADO = 'ENCERRADO';
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column 
    */
    private $nome;
    
    /** 
     * @JMS\Groups({"LIST"})
     * @ORM\Column(nullable = true) 
     */
    private $apelido;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(type = "string") 
    */
    private $status;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(name = "limite_alunos", type = "integer") 
    */
    private $limiteAlunos;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @JMS\Type("DateTime<'Y-m-d\TH:i:s'>")
    * @ORM\Column(name = "data_encerramento", type = "datetime", nullable = true) 
    */
    protected $dataEncerramento;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\Type("PessoaBundle\Entity\UnidadeEnsino")
    * @JMS\MaxDepth(depth = 1)
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\UnidadeEnsino")
    * @ORM\JoinColumn(name = "unidade_ensino_id", nullable = false) 
    */
    private $unidadeEnsino;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\MaxDepth(depth = 2)
    * @ORM\ManyToOne(targetEntity = "Etapa")
    * @ORM\JoinColumn(nullable = false)
    */
    private $etapa;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\ManyToOne(targetEntity = "Turno")
    * @ORM\JoinColumn(nullable = false)
    */
    private $turno;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @JMS\MaxDepth(depth = 1)
    * @ORM\ManyToOne(targetEntity = "CalendarioBundle\Entity\Calendario")
    * @ORM\JoinColumn(nullable = false)
    */
    private $calendario;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @ORM\ManyToOne(targetEntity = "CalendarioBundle\Entity\Periodo")
    * @ORM\JoinColumn(name = "calendario_periodo_id") 
    */
    private $periodo;
    
    /** 
    * @JMS\Groups({"DETAILS"})
    * @JMS\MaxDepth(depth = 1)
    * @ORM\ManyToOne(targetEntity = "QuadroHorarioBundle\Entity\QuadroHorario")
    * @ORM\JoinColumn(name = "quadro_horario_id", nullable = false) 
    */
    private $quadroHorario;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\AccessType("public_method")
    * @ORM\ManyToOne(targetEntity = "AgrupamentoTurma")
    * @ORM\JoinColumn(name = "turma_agrupamento_id") 
    */
    private $agrupamento;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\ManyToOne(targetEntity = "AgrupamentoDisciplina")
    * @ORM\JoinColumn(name = "disciplina_agrupamento_id") 
    */
    private $agrupamentoDisciplinas;
    
    /**
    * @JMS\Exclude 
    * @ORM\OneToMany(targetEntity = "DisciplinaOfertada", mappedBy = "turma", fetch="EXTRA_LAZY")
    */
    private $disciplinas;
    
    /** 
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "MatriculaBundle\Entity\Enturmacao", mappedBy = "turma", fetch="EXTRA_LAZY") 
    */
    private $enturmacoes;
    
    /** 
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "VagaBundle\Entity\Vaga", mappedBy = "turma", fetch="EXTRA_LAZY") 
    */
    private $vagas;
    
    function init() {
        $this->turno = $this->quadroHorario->getTurno();
        $this->status = self::STATUS_EM_ANDAMENTO;
        $this->enturmacoes = new ArrayCollection();
        $this->vagas = new ArrayCollection();
    }
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty
    */
    function getAno() {
        return $this->calendario->getAno();
    }

    function getDisciplinas() {
        return $this->disciplinas->matching(
            Criteria::create()
                ->where(Criteria::expr()->eq('ativo', true))
                ->orderBy(['disciplina' => 'ASC'])
        );
    }
    
    function getTotalEnturmacoes() {
        return $this->enturmacoes->matching(
            Criteria::create()->where(Criteria::expr()->andX(              
                Criteria::expr()->eq('ativo', true), 
                Criteria::expr()->eq('encerrado', false)
            ))
        )->count();
    }
    
    function getEnturmacoes($orderByName = true) {
        $enturmacoes = $this->enturmacoes->matching(
            Criteria::create()->where(Criteria::expr()->andX(              
                Criteria::expr()->eq('ativo', true), 
                Criteria::expr()->eq('encerrado', false)
            ))
        )->toArray();
        if ($orderByName) {
            usort($enturmacoes, function($e1, $e2) {
                return strcasecmp($e1->getMatricula()->getAluno()->getNome(), 
                    $e2->getMatricula()->getAluno()->getNome()); 
            });
        }
        return new ArrayCollection($enturmacoes);
    }
    
    function getProfessores() {
        $aux = $this->disciplinas->map(
            function($d) { return $d->getProfessores()->toArray(); }
        )->toArray();
        $professores = [];
        array_walk_recursive($aux, function($p) use (&$professores) {
            if (!in_array($p, $professores)) {
                $professores[] = $p;
            } 
        });
        return $professores;
    }
       
    function getVagas() {
        return $this->vagas->matching(
            Criteria::create()->where(Criteria::expr()->eq('ativo', true))
        );
    }
    
    function getVagasAbertas() {
        return $this->vagas->matching(
            Criteria::create()->where(Criteria::expr()->andX(              
                Criteria::expr()->eq('ativo', true), 
                Criteria::expr()->isNull('enturmacao')
            ))
        );
    }
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty
    */
    function getEncerrado() {
        return $this->status === self::STATUS_ENCERRADO;
    }
    
    /**
    * @JMS\Groups({"DETAILS", "contagem_enturmacoes"})
    * @JMS\VirtualProperty
    */
    function getQuantidadeAlunos() {
        return $this->enturmacoes->matching(
            Criteria::create()->where(Criteria::expr()->andX(              
                Criteria::expr()->eq('ativo', true), 
                Criteria::expr()->eq('encerrado', false)
            ))
        )->count();
    }
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty
    */
    function getNomeCompleto() {
        $nome = $this->apelido ?  "{$this->nome} - {$this->apelido}" : $this->nome;
        return $this->periodo ?
            "{$nome} - {$this->periodo->getNumero()}º {$this->etapa->getUnidadeRegime()}" 
            : $nome;
    }
    
    function encerrar() {
        $this->status = self::STATUS_ENCERRADO;
        $this->dataEncerramento = new \DateTime();
    }
    
    function getNomeExibicao() {
        return $this->getNomeCompleto();
    }
    
    function getNome() {
        return $this->nome;
    }

    function getApelido() {
        return $this->apelido;
    }

    function getLimiteAlunos() {
        return $this->limiteAlunos;
    }

    function getUnidadeEnsino() {
        return $this->unidadeEnsino;
    }

    function getEtapa() {
        return $this->etapa;
    }

    function getTurno() {
        return $this->turno;
    }
    
    function getCalendario() {
        return $this->calendario;
    }
    
    function getPeriodo() {
        return $this->periodo;
    }
    
    function getQuadroHorario() {
        return $this->quadroHorario;
    }

    function getAgrupamento() {
        return $this->agrupamento;
    }
    
    function getAgrupamentoDisciplinas() {
        if ($this->etapa->getIntegral()) {
            return null;
        }
        //Ternário para retrocompatibilidade com turmas antigas. Será retirado futuramente.
        return $this->agrupamentoDisciplinas 
                ? $this->agrupamentoDisciplinas 
                : $this->disciplinas->first()->getAgrupamento();
    }
    
    function getDataEncerramento() {
        return $this->dataEncerramento 
                ? $this->dataEncerramento 
                : $this->calendario->getDataTermino();
    }
    
    function setNome($nome) {
        $this->nome = $nome;
    }

    function setApelido($apelido) {
        $this->apelido = $apelido;
    }

    function setLimiteAlunos($limiteAlunos) {
        $this->limiteAlunos = $limiteAlunos;
    }
    
    function setAgrupamento(AgrupamentoTurma $agrupamento = null) {
        $this->agrupamento = $agrupamento;
    }
        
    function getStatus() {
        return $this->status;
    }

    function setStatus($status) {
        $this->status = $status;
    }
    
    function setQuadroHorario($quadroHorario) {
        $this->quadroHorario = $quadroHorario;
        $this->turno = $quadroHorario->getTurno();
    }
    
    function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }
    
}