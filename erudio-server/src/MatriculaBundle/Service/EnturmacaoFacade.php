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

namespace MatriculaBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\ORM\Exception\IllegalOperationException;
use CoreBundle\ORM\Exception\IllegalUpdateException;
use MatriculaBundle\Entity\DisciplinaCursada;
use MatriculaBundle\Entity\Enturmacao;
use CursoBundle\Entity\Turma;
use CursoBundle\Service\VagaFacade;

class EnturmacaoFacade extends AbstractFacade {
    
    private $disciplinaCursadaFacade;
    private $vagaFacade;
    
    function __construct(RegistryInterface $doctrine, DisciplinaCursadaFacade $disciplinaCursadaFacade, 
            VagaFacade $vagaFacade) {
        parent::__construct($doctrine);
        $this->disciplinaCursadaFacade = $disciplinaCursadaFacade;
        $this->vagaFacade = $vagaFacade;
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:Enturmacao';
    }
    
    function queryAlias() {
        return 'e';
    }
    
    function parameterMap() {
        return [
            'matricula' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('matricula = :matricula')->setParameter('matricula', $value);
            },
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('turma = :turma')->setParameter('turma', $value);
            },
            'turma_unidadeEnsino' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('turma.unidadeEnsino = :unidadeEnsino')->setParameter('unidadeEnsino', $value);
            },
            'encerrado' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.encerrado = :encerrado')->setParameter('encerrado', $value);
            },
            'concluido' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.concluido = :concluido')->setParameter('concluido', $value);
            },
            'emAndamento' => function(QueryBuilder $qb, $value) {
                $operador = $value ? '=' : '<>';
                $qb->andWhere("e.concluido {$operador} false")->andWhere("e.encerrado {$operador} false");
            }
        ];
    }
    
    

    protected function selectMap(): array {
        return ['e', 'turma', 'matricula', 'aluno'];
    }
    
    function uniqueMap($enturmacao) {
        return [[
            'matricula' => $enturmacao->getMatricula(), 
            'turma' => $enturmacao->getTurma(), 
            'encerrado' => false,
            'concluido' => false
        ]];
    }
    
    /**
     * Lista enturmaçẽos de alunos defasados de um curso ofertado, em determinada etapa.
     * 
     * @param Etapa $etapa
     * @return array alunos defasados
     */
    function getAlunosDefasados($cursoOfertado, $etapa, \DateTime $dataReferencia = null) {
        $dataLimite = $dataReferencia ? $dataReferencia 
                : \DateTime::createFromFormat('Y-m-d', (new \DateTime())->format('Y') . '-03-31');
        $idadeLimite = $etapa->getIdadeRecomendada() + $etapa->getCurso()->getLimiteDefasagem();
        $dataLimite->sub(new \DateInterval("P{$idadeLimite}Y"));
        $qb = $this->orm->getManager()->createQueryBuilder()->select('en')
            ->from($this->getEntityClass(), 'en')
            ->join('en.turma', 't')->join('t.etapa', 'e')->join('t.unidadeEnsino', 'u')
            ->join('en.matricula', 'm')->join('m.aluno', 'a')
            ->where('en.ativo = true')->andWhere('en.encerrado = false')
            ->andWhere('u.id = :unidadeEnsino')->andWhere('e.id = :etapa')
            ->andWhere('a.dataNascimento < :limiteInferior')
            ->setParameter('etapa', $etapa->getId())
            ->setParameter('unidadeEnsino', $cursoOfertado->getUnidadeEnsino()->getId())
            ->setParameter('limiteInferior', $dataLimite);
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Conta alunos de uma turma. O parâmetro adicional filtra a contagem por gênero.
     * 
     * @param Turma $turma
     * @param $genero 'M' para masculino e 'F' para feminino
     * @return integer número de alunos da turma
     */
    function countByTurma(Turma $turma, $genero = '') {
        $qb = $this->orm->getManager()->createQueryBuilder()->select('COUNT(e.id)')
            ->from($this->getEntityClass(), 'e')
            ->join('e.turma', 't')->join('e.matricula', 'm')
            ->where('e.ativo = true')->andWhere('e.encerrado = false')
            ->andWhere('t.id = :turma')->setParameter('turma', $turma->getId());
        if ($genero) {
            $qb = $qb->join('m.aluno', 'a')->andWhere('a.genero = :genero')->setParameter('genero', $genero);
        }
        return $qb->getQuery()->getSingleScalarResult();
    }
    
    /**
     * Encerra uma enturmação de um aluno que está sendo transferido para outra unidade
     * de ensino, bem como suas disciplinas cursadas.
     * 
     * @param Enturmacao $enturmacao
     */
    function encerrarPorMovimentacao(Enturmacao $enturmacao, $manterDisciplinas = true) {
        $enturmacao->encerrar();
        $this->orm->getManager()->merge($enturmacao);
        $this->orm->getManager()->flush();
        if ($manterDisciplinas) {
            $this->desvincularDisciplinas($enturmacao);
        } else {
            $this->encerrarDisciplinas($enturmacao, DisciplinaCursada::STATUS_INCOMPLETO);
        }
        $this->liberarVaga($enturmacao);
    }
    
    /**
     * Encerra uma enturmação de um aluno que está sendo transferido para outra unidade
     * de ensino, bem como suas disciplinas cursadas.
     * 
     * @param Enturmacao $enturmacao
     */
    function finalizar(Enturmacao $enturmacao) {
        $disciplinas = $enturmacao->getDisciplinasCursadas();
        foreach ($disciplinas as $disciplina) {
            if ($disciplina->emAberto()) {
                throw new IllegalUpdateException(
                    IllegalUpdateException::ILLEGAL_STATE_TRANSITION,
                    'Turma não pode ser encerrada, existem alunos com média em aberto na disciplina '
                        . $disciplina->getNomeExibicao()
                );
            }
        }
        $enturmacao->concluir();
        $this->orm->getManager()->merge($enturmacao);
        $this->orm->getManager()->flush();
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('e.matricula', 'matricula')->join('matricula.aluno', 'aluno')
           ->join('e.turma', 'turma')
           ->orderBy('aluno.nome');
    }
    
    protected function beforeCreate($enturmacao) {
        if ($this->possuiVagaAberta($enturmacao) == false) {
            throw new IllegalOperationException('Não existem vagas disponíveis nesta turma');
        }
    }
    
    protected function afterCreate($enturmacao) {
        $enturmacao->getMatricula()->redefinirEtapa();
        $this->vincularDisciplinas($enturmacao);
        $this->ocuparVaga($enturmacao);
    }
    
    protected function afterRemove($enturmacao) {
        $this->desvincularDisciplinas($enturmacao);
        $enturmacao->getMatricula()->resetarEtapa();
        $this->orm->getManager()->flush();
        $this->liberarVaga($enturmacao);
    }
    
    /**
     * Vincula disciplinas cursadas existentes à uma enturmação recém-criada, ou cria
     * novas disciplinas cursadas de acordo com aquelas oferecidas na turma.
     * 
     * @param Enturmacao $enturmacao
     */
    private function vincularDisciplinas(Enturmacao $enturmacao) {
        $matricula = $enturmacao->getMatricula();
        $disciplinasOfertadas = $enturmacao->getTurma()->getDisciplinas();
        $disciplinasEmAndamento = $this->disciplinaCursadaFacade
                ->findByMatriculaAndEtapa($matricula, $enturmacao->getTurma()->getEtapa());
        foreach ($disciplinasOfertadas as $disciplinaOfertada) {   
            $emAndamento = false;                     
            foreach ($disciplinasEmAndamento as $disciplinaCursada) {
                if ($disciplinaCursada->getDisciplina()->getId() === $disciplinaOfertada->getDisciplina()->getId()) {
                    $disciplinaCursada->vincularEnturmacao($enturmacao, $disciplinaOfertada);
                    $this->orm->getManager()->merge($disciplinaCursada);
                    $emAndamento = true;
                    break;
                }                
            }
            if (!$emAndamento) {
                $disciplinaCursada = new DisciplinaCursada($matricula, $disciplinaOfertada->getDisciplina());
                $disciplinaCursada->vincularEnturmacao($enturmacao, $disciplinaOfertada);
                $this->disciplinaCursadaFacade->create($disciplinaCursada);
            }
        }
        $this->orm->getManager()->flush();
    }
    
    /**
     * Encerra disciplinas cursadas de uma enturmação, com o status informado.
     * 
     * @param Enturmacao $enturmacao
     * @param $status
     */
    private function desvincularDisciplinas(Enturmacao $enturmacao) {
        foreach ($enturmacao->getDisciplinasCursadas() as $disciplina) {
            $disciplina->desvincularEnturmacao();
            $this->orm->getManager()->merge($disciplina);
        }
        $this->orm->getManager()->flush();
    }
    
    /**
     * Encerra disciplinas cursadas de uma enturmação, com o status informado.
     * 
     * @param Enturmacao $enturmacao
     * @param $status
     */
    private function encerrarDisciplinas(Enturmacao $enturmacao, $status) {
        foreach ($enturmacao->getDisciplinasCursadas() as $disciplina) {
            $disciplina->encerrar($status);
            $this->orm->getManager()->merge($disciplina);
        }
        $this->orm->getManager()->flush();
    }
    
    /**
     * Exclui disciplinas de uma enturmação.
     * 
     * @param Enturmacao $enturmacao
     */
    private function excluirDisciplinas(Enturmacao $enturmacao) {
        $this->disciplinaCursadaFacade->removeBatch(
            $enturmacao->getDisciplinasCursadas()
                ->map(function($d) { return $d->getId(); })
                ->toArray()
        );
    }
    
    /**
     * Indica se existe vaga aberta para a enturmação informada.
     * 
     * @param Enturmacao $enturmacao
     * @return true caso a turma da enturmação possua vaga disponível, e false em
     * caso contrário
     */
    private function possuiVagaAberta(Enturmacao $enturmacao) {
        return $enturmacao->getTurma()->getVagasAbertas()->count() > 0;
    }
    
    /**
     * Aloca uma vaga na turma da enturmação.
     * 
     * @param Enturmacao $enturmacao
     */
    private function ocuparVaga(Enturmacao $enturmacao) {
        $this->vagaFacade->ocupar($enturmacao->getTurma()->getVagasAbertas()->first(), $enturmacao);
    }
    
    /**
     * Libera a vaga ocupada pela enturmação.
     * 
     * @param Enturmacao $enturmacao
     */
    private function liberarVaga(Enturmacao $enturmacao) {
        if ($enturmacao->getVaga()) {
            $this->vagaFacade->liberar($enturmacao->getVaga());
        }
    }
    
}