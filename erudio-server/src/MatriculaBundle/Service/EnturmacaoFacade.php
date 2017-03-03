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

use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use MatriculaBundle\Entity\DisciplinaCursada;
use MatriculaBundle\Entity\Enturmacao;
use CursoBundle\Service\VagaFacade;
use CoreBundle\ORM\Exception\IllegalOperationException;

class EnturmacaoFacade extends AbstractFacade {
    
    private $disciplinaCursadaFacade;
    private $vagaFacade;
    
    function encerrarPorTransferencia(Enturmacao $enturmacao) {
        $enturmacao->encerrar();
        $this->encerrarDisciplinas($enturmacao, DisciplinaCursada::STATUS_INCOMPLETO);
        $this->liberarVagas($enturmacao);
        $this->orm->getManager()->flush();
    }
    
    function setDisciplinaCursadaFacade(DisciplinaCursadaFacade $disciplinaCursadaFacade) {
        $this->disciplinaCursadaFacade = $disciplinaCursadaFacade;
    }
    
    function setVagaFacade(VagaFacade $vagaFacade) {
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
                $qb->andWhere('matricula.id = :matricula')->setParameter('matricula', $value);
            },
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->join('e.turma', 'turma')
                   ->andWhere('turma.id = :turma')->setParameter('turma', $value);
            },
            'encerrado' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.encerrado = :encerrado')->setParameter('encerrado', $value);
            }
        ];
    }
    
    function uniqueMap($enturmacao) {
        return [
            ['matricula' => $enturmacao->getMatricula(), 'turma' => $enturmacao->getTurma()]
        ];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('e.matricula', 'matricula')->join('matricula.aluno', 'aluno')->orderBy('aluno.nome');
    }
    
    protected function beforeCreate($enturmacao) {
        if ($this->possuiVagaAberta($enturmacao) == false) {
            throw new IllegalOperationException('Não existem vagas disponíveis nesta turma');
        }
    }
    
    protected function afterCreate($enturmacao) {
        $this->vincularDisciplinas($enturmacao);
        $this->ocuparVaga($enturmacao);
    }
    
    protected function afterRemove($enturmacao) {
        $this->excluirDisciplinas($enturmacao);
        $this->liberarVagas($enturmacao);
    }
    
    private function vincularDisciplinas(Enturmacao $enturmacao) {
        $matricula = $enturmacao->getMatricula();
        $disciplinasOfertadas = $enturmacao->getTurma()->getDisciplinas();
        $qb = $this->orm->getRepository('MatriculaBundle:DisciplinaCursada')->createQueryBuilder('d')
            ->join('d.matricula', 'matricula')->join('d.disciplina', 'disciplina')->join('disciplina.etapa', 'etapa')
            ->where('d.' . self::ATTR_ATIVO . ' = true')
            ->andWhere('matricula.id = :matricula')->setParameter('matricula', $matricula->getId())
            ->andWhere('d.status IN (:status)')->setParameter('status', array(
                DisciplinaCursada::STATUS_CURSANDO, 
                DisciplinaCursada::STATUS_DISPENSADO)
            )
            ->andWhere('etapa.id = :etapa')->setParameter('etapa', $enturmacao->getTurma()->getEtapa()->getId());
        $disciplinasCursadas = $qb->getQuery()->getResult();
        foreach ($disciplinasOfertadas as $disciplinaOfertada) {   
            $enturmado = false;                     
            foreach ($disciplinasCursadas as $disciplinaCursada) {
                if($disciplinaCursada->getDisciplina()->getId() === $disciplinaOfertada->getDisciplina()->getId()) {
                    if($disciplinaCursada->getDisciplinaOfertada() === null && $disciplinaCursada->getStatus() === DisciplinaCursada::STATUS_CURSANDO) {
                        $disciplinaCursada->setEnturmacao($enturmacao);
                        $disciplinaCursada->setDisciplinaOfertada($disciplinaOfertada);
                        $this->orm->getManager()->merge($disciplinaCursada);
                    }
                    $enturmado = true;
                    break;
                }                
            }
        }
        $this->orm->getManager()->flush();
    }
    
    private function encerrarDisciplinas(Enturmacao $enturmacao, $status) {
        foreach ($enturmacao->getDisciplinasCursadas() as $disciplina) {
            $disciplina->setStatus($status);
            $this->disciplinaCursadaFacade->update($disciplina->getId(), $disciplina);
        }
    }
    
    private function excluirDisciplinas(Enturmacao $enturmacao) {
        $this->disciplinaCursadaFacade->removeBatch(
            $enturmacao->getDisciplinasCursadas()
                ->map(function($d) { return $d->getId(); })
                ->toArray()
        );
    }
    
    private function possuiVagaAberta(Enturmacao $enturmacao) {
        return $enturmacao->getTurma()->getVagasAbertas()->count() > 0;
    }
    
    private function ocuparVaga(Enturmacao $enturmacao) {
        $this->vagaFacade->ocupar(
                $enturmacao->getTurma()->getVagasAbertas()->first(), $enturmacao);
    }
    
    private function liberarVagas(Enturmacao $enturmacao) {
        $vagas = $this->vagaFacade->findAll(['enturmacao' => $enturmacao]);
        foreach ($vagas as $vaga) {
            $this->vagaFacade->liberar($vaga);
        }
    }
    
}

