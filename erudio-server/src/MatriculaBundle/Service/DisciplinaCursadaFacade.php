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
use MatriculaBundle\Entity\Media;

class DisciplinaCursadaFacade extends AbstractFacade {
    
    private $mediaFacade;
    
    function setMediaFacade($mediaFacade) {
        $this->mediaFacade = $mediaFacade;
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:DisciplinaCursada';
    }
    
    function queryAlias() {
        return 'd';
    }
    
    function parameterMap() {
        return array (
            'dataCadastro' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('d.dataCadastro LIKE :dataCadastro')->setParameter('dataCadastro', '%' . $value . '%');
            },
            'status' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('d.status = :status')->setParameter('status', $value);
            },
            'enturmacao' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('enturmacao.id = :enturmacao')->setParameter('enturmacao', $value);
            },
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->join('enturmacao.turma', 'turma')
                   ->andWhere('turma.id = :turma')->setParameter('turma', $value);
            },
            'disciplina' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('disciplina.id = :disciplina')->setParameter('disciplina', $value);
            },
            'etapa' => function(QueryBuilder $qb, $value) {
                $qb->join('disciplina.etapa', 'etapa')
                   ->andWhere('etapa.id = :etapa')->setParameter('etapa', $value);
            },
            'matricula' => function(QueryBuilder $qb, $value) {
                $qb->join('d.matricula', 'm')
                    ->andWhere('m.id = :matricula')->setParameter('matricula', $value);
            }
        );
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('d.disciplina', 'disciplina')->leftJoin('d.enturmacao', 'enturmacao');
    }
    
    protected function afterCreate($disciplinaCursada) {
        $numeroMedias = $disciplinaCursada->getDisciplina()->getEtapa()->getSistemaAvaliacao()->getQuantidadeMedias();
        for($i = 1; $i <= $numeroMedias; $i++) {
            $media = new Media($disciplinaCursada, $i);
            $this->mediaFacade->create($media);
        }
    }
    
    protected function beforeRemove($disciplinaCursada) {
        
    }
    
}

