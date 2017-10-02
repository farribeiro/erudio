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
use MatriculaBundle\Entity\DisciplinaCursada;
use MatriculaBundle\Service\DisciplinaCursadaFacade;

class DisciplinaOfertadaFacade extends AbstractFacade {
    
    private $disciplinaCursadaFacade;
    
    function __construct(RegistryInterface $doctrine, DisciplinaCursadaFacade $disciplinaCursadaFacade) {
        parent::__construct($doctrine);
        $this->disciplinaCursadaFacade = $disciplinaCursadaFacade;
    }
    
    function getEntityClass() {
        return 'CursoBundle:DisciplinaOfertada';
    }
    
    function queryAlias() {
        return 'd';
    }
    
    function parameterMap() {
        return array (
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('d.turma = :turma')->setParameter('turma', $value);
            },
            'disciplina' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('d.disciplina = :disciplina')->setParameter('disciplina', $value);
            }
        );
    }
    
    function uniqueMap($disciplinaOfertada) {
        return [[ 
            'turma' => $disciplinaOfertada->getTurma(), 
            'disciplina' => $disciplinaOfertada->getDisciplina()
        ]];
    }
    
    function afterCreate($disciplinaOfertada) {
        $enturmacoes = $disciplinaOfertada->getTurma()->getEnturmacoes();
        foreach ($enturmacoes as $enturmacao) {
            $disciplinasCursadas = $this->disciplinaCursadaFacade->findAll([
                'matricula' => $enturmacao->getMatricula()->getId(),
                'disciplina' => $disciplinaOfertada->getDisciplina()->getId(),
                'encerrado' => false
            ]);
            if (count($disciplinasCursadas) > 0) {
                $disciplinaCursada = $disciplinasCursadas[0];
                $disciplinaCursada->vincularEnturmacao($enturmacao, $disciplinaOfertada);
                $this->orm->getManager()->flush();
            } else {
                $disciplinaCursada = new DisciplinaCursada($enturmacao->getMatricula(), $disciplinaOfertada->getDisciplina());
                $disciplinaCursada->vincularEnturmacao($enturmacao, $disciplinaOfertada);
                $this->disciplinaCursadaFacade->create($disciplinaCursada);
            }
        }
    }

}

