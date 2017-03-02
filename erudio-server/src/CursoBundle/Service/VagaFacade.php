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
use MatriculaBundle\Entity\Enturmacao;

class VagaFacade extends AbstractFacade {
    
    function getEntityClass() {
        return 'CursoBundle:Vaga';
    }
    
    function queryAlias() {
        return 't';
    }
    
    function parameterMap() {
        return array (
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.turma = :turma')->setParameter('turma', $value);
            },
            'solicitacaoVaga' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.solicitacaoVaga = :solicitacaoVaga')->setParameter('solicitacaoVaga', $value);
            },
            'enturmacao' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.enturmacao = :enturmacao')->setParameter('enturmacao', $value);
            }
        );
    }
    
    function liberar(Vaga $vaga) {
        $vaga->liberar();
        $this->orm->getManager()->merge($vaga);
        $this->orm->getManager()->flush();
    }
    
    function ocupar(Vaga $vaga, Enturmacao $enturmacao) {
        $vaga->setEnturmacao($enturmacao);
        $this->orm->getManager()->merge($vaga);
        $this->orm->getManager()->flush();
    }
    
}

