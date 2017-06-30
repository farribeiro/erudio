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

namespace CursoBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\ORM\Exception\IllegalUpdateException;
use CoreBundle\ORM\Exception\IllegalOperationException;
use CursoBundle\Entity\Vaga;
use CursoBundle\Entity\Turma;
use CursoBundle\Entity\DisciplinaOfertada;
use MatriculaBundle\Service\EnturmacaoFacade;

class TurmaFacade extends AbstractFacade {
    
    private $disciplinaOfertadaFacade;
    private $enturmacaoFacade;
    private $vagaFacade;
    
    function __construct(RegistryInterface $doctrine, EnturmacaoFacade $enturmacaoFacade, 
            DisciplinaOfertadaFacade $disciplinaFacade, VagaFacade $vagaFacade) {
        parent::__construct($doctrine);
        $this->disciplinaOfertadaFacade = $disciplinaFacade;
        $this->enturmacaoFacade = $enturmacaoFacade;
        $this->vagaFacade = $vagaFacade;
    }
    
    function getEntityClass() {
        return 'CursoBundle:Turma';
    }
    
    function removerAgrupamento(Turma $turma) {
        $turma->setAgrupamento(null);
        $this->orm->getManager()->flush();
    }
    
    function queryAlias() {
        return 't';
    }
    
    function parameterMap() {
        return array (
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'apelido' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.apelido LIKE :apelido')->setParameter('apelido', '%' . $value . '%');
            },        
            'status' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.status = :status')->setParameter('status', $value);
            },                    
            'encerrado' => function(QueryBuilder $qb, $value) {
                $operator = $value ? '=' : '<>';
                $qb->andWhere("t.status ${operator} :encerrado")->setParameter('encerrado', Turma::STATUS_ENCERRADO);
            },
            'curso' => function(QueryBuilder $qb, $value) {
                $qb->join('etapa.curso', 'curso')                   
                   ->andWhere('curso.id = :curso')->setParameter('curso', $value);
            },
            'etapa' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('etapa.id = :etapa')->setParameter('etapa', $value);
            },
            'etapa_ordem' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('etapa.ordem = :ordemEtapa')->setParameter('ordemEtapa', $value);
            },
            'quadroHorario' => function(QueryBuilder $qb, $value) {
                $qb->join('t.quadroHorario', 'quadroHorario')
                    ->andWhere('quadroHorario.id = :quadroHorario')->setParameter('quadroHorario', $value);
            },
            'agrupamento' => function(QueryBuilder $qb, $value) {
                $qb->join('t.agrupamento', 'agrupamento')
                    ->andWhere('agrupamento.id = :agrupamento')->setParameter('agrupamento', $value);
            },
            'unidadeEnsino' => function(QueryBuilder $qb, $value) {
                $qb->join('t.unidadeEnsino', 'unidadeEnsino')
                   ->andWhere('unidadeEnsino.id = :unidadeEnsino')
                   ->setParameter('unidadeEnsino', $value);
            }
        );
    }
    
    protected function selectMap() {
        return ['t', 'etapa'];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('t.etapa', 'etapa')
           ->addOrderBy('etapa.curso','ASC')
           ->addOrderBy('etapa.ordem','ASC')
           ->addOrderBy('t.nome', 'ASC');
    }
    
    protected function beforeCreate($turma) {
        if ($turma->getEtapa()->getIntegral() && $turma->getPeriodo()) {
            throw new IllegalOperationException(
                'Turmas de etapa integral não devem ser vinculadas a um período do calendário'
            );
        }
    }
    
    protected function beforeRemove($turma) {
        if ($turma->getEnturmacoes()->count() > 0) {
            throw new IllegalUpdateException(
                IllegalUpdateException::ILLEGAL_STATE_TRANSITION, 
                'Operação não permitida, não é possível excluir uma turma com alunos enturmados'
            );
        }
    }
    
    protected function afterCreate($turma) {
        $this->criarDisciplinas($turma);
        $this->gerarVagas($turma);
    }
    
    protected function afterUpdate($turma) {
        $this->gerarVagas($turma);
        if ($turma->getEncerrado()) {
            $this->finalizar($turma);
        }
    }
    
    protected function afterRemove($turma) {
        $this->encerrarDisciplinas($turma);
        $this->encerrarVagas($turma);
    }
    
    private function criarDisciplinas(Turma $turma) {
         if($turma->getEtapa()->getIntegral()) {
            foreach ($turma->getEtapa()->getDisciplinas() as $disciplina) {
                $disciplinaOfertada = new DisciplinaOfertada($turma, $disciplina);
                $this->disciplinaOfertadaFacade->create($disciplinaOfertada);
            }
        }
    }
    
    private function gerarVagas(Turma $turma) {
        $numeroVagas = $turma->getVagas()->count();
        if ($numeroVagas < $turma->getLimiteAlunos()) {
            $quantidade = $turma->getLimiteAlunos() - $numeroVagas;
            for ($i = 0; $i < $quantidade; $i++) {
                $vaga = new Vaga($turma);
                $this->vagaFacade->create($vaga);
            }
        } else if ($turma->getTotalEnturmacoes() > $turma->getLimiteAlunos()) {
            throw new IllegalUpdateException(
                IllegalUpdateException::FINAL_STATE, 
                'Operação não permitida, não é possível diminuir a quantidade '
                    . 'de vagas abaixo da quantidade de enturmações atual'
            );
        } else {
            $vagasEliminadas = 0;
            foreach ($turma->getVagasAbertas() as $vaga) {
                if ($vagasEliminadas == $numeroVagas - $turma->getLimiteAlunos()) {
                    break;
                }
                $this->vagaFacade->remove($vaga->getId());
                $vagasEliminadas++;
            }
        }
    }
    
    private function encerrarDisciplinas(Turma $turma) {
        foreach ($turma->getDisciplinas() as $disciplina) {
            $this->disciplinaOfertadaFacade->remove($disciplina);
        }
    }
    
    private function encerrarVagas(Turma $turma) {
        foreach ($turma->getVagasAbertas() as $vaga) {
            $this->vagaFacade->remove($vaga);
        } 
    }
 
    function finalizar(Turma $turma) {
        $turma->setStatus(Turma::STATUS_ENCERRADO);
        foreach ($turma->getEnturmacoes() as $enturmacao) {
            $this->enturmacaoFacade->finalizar($enturmacao);
        }
    }
    
}
