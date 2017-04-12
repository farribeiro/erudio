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
use MatriculaBundle\Entity\Desligamento;
use MatriculaBundle\Entity\Matricula;
use MatriculaBundle\Entity\DisciplinaCursada;
use Doctrine\Common\Collections\Criteria;

class DesligamentoFacade extends AbstractFacade {
    
    function getEntityClass() {
        return 'MatriculaBundle:Desligamento';
    }
    
    function queryAlias() {
        return 'd';
    }
    
    function parameterMap() {
        return array (
            'matricula' => function(QueryBuilder $qb, $value) {
                $qb->join('d.matricula', 'matricula')
                   ->andWhere('matricula.id = :matricula')->setParameter('matricula', $value);
            }
        );
    }
    
    protected function afterCreate($desligamento) {
        $this->desligarMatricula($desligamento);
        $this->encerrarEnturmacoes($desligamento->getMatricula());
        $this->encerrarDisciplinas($desligamento->getMatricula());
    }
    
    private function desligarMatricula(Desligamento $desligamento) {
        $matricula = $desligamento->getMatricula();
        switch($desligamento->getMotivo()) {
            case Desligamento::ABANDONO:
                $matricula->setStatus(Matricula::STATUS_ABANDONO);
                break;
            case Desligamento::FALECIMENTO:
                $matricula->setStatus(Matricula::STATUS_FALECIDO);
                break;
            case Desligamento::TRANSFERENCIA_EXTERNA:
                $matricula->setStatus(Matricula::STATUS_TRANCADO);
                break;
            case Desligamento::CANCELAMENTO:
                $matricula->setStatus(Matricula::STATUS_CANCELADO);
        }
        $this->orm->getManager()->merge($matricula);
        $this->orm->getManager()->flush();
    }
    
    private function encerrarDisciplinas(Matricula $matricula) {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('status', DisciplinaCursada::STATUS_CURSANDO));
        $disciplinas = $matricula->getDisciplinasCursadas()->matching($criteria);
        foreach ($disciplinas as $d) {
            $d->encerrar(DisciplinaCursada::STATUS_INCOMPLETO);
            $this->orm->getManager()->merge($d);
            $this->orm->getManager()->flush();
        }
    }
    
    private function encerrarEnturmacoes(Matricula $matricula) {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('encerrado', false));
        $enturmacoes = $matricula->getEnturmacoes()->matching($criteria);
        foreach ($enturmacoes as $e) {
            $e->encerrar();
            $this->orm->getManager()->merge($e);
            $this->orm->getManager()->flush();
        }
    }
    
}