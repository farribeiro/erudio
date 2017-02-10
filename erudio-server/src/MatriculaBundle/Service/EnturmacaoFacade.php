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
use MatriculaBundle\Entity\Media;
use MatriculaBundle\Entity\Enturmacao;

class EnturmacaoFacade extends AbstractFacade {
    
    private $mediaFacade;
    
    function setMediaFacade($mediaFacade) {
        $this->mediaFacade = $mediaFacade;
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:Enturmacao';
    }
    
    function queryAlias() {
        return 'e';
    }
    
    function parameterMap() {
        return array (
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
        );
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('e.matricula', 'matricula')->join('matricula.aluno', 'aluno')->orderBy('aluno.nome');
    }
    
    protected function afterCreate($enturmacao) {
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
            /*if (!$enturmado) {  
                $novaDisciplina = new DisciplinaCursada($matricula, $disciplinaOfertada->getDisciplina());
                $novaDisciplina->setEnturmacao($enturmacao);
                $novaDisciplina->setDisciplinaOfertada($disciplinaOfertada);
                $numeroMedias = $novaDisciplina->getDisciplina()->getEtapa()->getSistemaAvaliacao()->getQuantidadeMedias();  
                for($i = 1; $i <= $numeroMedias; $i++) {
                        $media = new Media($novaDisciplina, $i);
                        $this->mediaFacade->create($media);
                }
                $this->orm->getManager()->persist($novaDisciplina);
            }*/
        }
        $this->orm->getManager()->flush();
    }
    
    protected function afterUpdate($enturmacao) {
         if ($enturmacao->getEncerrado() == false) {
             $enturmacao->setEncerrado(false);
             $this->orm->getManager()->merge($enturmacao);
             //$this->orm->getManager()->flush();
         }
             
    }
    
}

