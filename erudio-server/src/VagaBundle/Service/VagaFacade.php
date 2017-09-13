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

namespace VagaBundle\Service;

use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use VagaBundle\Entity\Vaga;
use CursoBundle\Entity\Turma;
use MatriculaBundle\Entity\Enturmacao;

class VagaFacade extends AbstractFacade {
    
    function getEntityClass() {
        return 'VagaBundle:Vaga';
    }
    
    function queryAlias() {
        return 't';
    }
    
    function parameterMap() {
        return [
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.turma = :turma')->setParameter('turma', $value);
            },
            'enturmacao' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.enturmacao = :enturmacao')->setParameter('enturmacao', $value);
            }
        ];
    }
    
    function atualizarVagas(Turma $turma) {
        $numeroVagas = $turma->getVagas()->count();
        if ($numeroVagas < $turma->getLimiteAlunos()) {
            $quantidade = $turma->getLimiteAlunos() - $numeroVagas;
            for ($i = 0; $i < $quantidade; $i++) {
                $this->create(new Vaga($turma));
            }
        } else {
            $vagasEliminadas = 0;
            foreach ($turma->getVagasAbertas() as $vaga) {
                if ($vagasEliminadas === ($numeroVagas - $turma->getLimiteAlunos())) {
                    break;
                }
                $this->remove($vaga->getId());
                $vagasEliminadas++;
            }
        }
    }
        
    function removerVagas(Turma $turma) {
        if (!$turma->getAtivo()) {
            foreach ($turma->getVagasAbertas() as $vaga) {
                $this->remove($vaga);
            }
        }
    }
    
    function liberar(Vaga $vaga) {
        $vaga->liberar();
        $this->orm->getManager()->flush();
    }
    
    function ocupar(Vaga $vaga, Enturmacao $enturmacao) {
        $vaga->ocupar($enturmacao);
        $this->orm->getManager()->flush();
    }
    
}

