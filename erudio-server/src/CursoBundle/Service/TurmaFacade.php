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

use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use CursoBundle\Entity\Vaga;

class TurmaFacade extends AbstractFacade {
    
    function getEntityClass() {
        return 'CursoBundle:Turma';
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
                $qb->andWhere('t.encerrado = :encerrado')->setParameter('encerrado', $value);
            },
            'curso' => function(QueryBuilder $qb, $value) {
                $qb->join('etapa.curso', 'curso')                   
                   ->andWhere('curso.id = :curso')->setParameter('curso', $value);
            },
            'etapa' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('etapa.id = :etapa')->setParameter('etapa', $value);
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
                   ->andWhere('unidadeEnsino.id LIKE :unidadeEnsino')
                   ->setParameter('unidadeEnsino', '%' . $value . '%');
            }
        );
    }    
    
    protected function prepareQuery(QueryBuilder $qb, array $params){
        $qb->join('t.etapa', 'etapa');
    }
        
    protected function beforeRemove($turma) {
        $turma->setStatus('ENCERRADO');
    }
    
    protected function afterCreate($entidade) {
        $vagas = $entidade->getLimiteAlunos();
        for ($i=0; $i<$vagas; $i++) {
            $vaga = new Vaga();
            $vaga->setTurma($entidade->getId());
            $this->orm->getManager()->persist($vaga);
            $this->orm->getManager()->flush();
        }
    }
    
    protected function beforeUpdate($turma) {
        $vagas = $this->orm->getRepository('CursoBundle:Vaga')->findBy(array('turma' => $turma));
        $num_vagas = count($vagas);
        if($num_vagas != $turma->getLimiteAlunos()) {
            if($num_vagas > $turma->getLimiteAlunos()) {
                for($i = 0; $i < $num_vagas - $turma->getLimiteAlunos(); $i++) {
                    $vaga = new Vaga();
                    $vaga->setTurma($turma->getId());
                    $this->orm->getManager()->persist($vaga);                
                }
            } else {
                throw new IllegalUpdateException(
                    IllegalUpdateException::FINAL_STATE, 
                    'Operação não permitida, não é possível diminuir a quantidade de vagas de uma turma'
                );
            }
        }
    }

}