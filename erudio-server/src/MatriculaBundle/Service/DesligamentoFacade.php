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
        }
        $matricula->getEnturmacoes()
            ->filter(function($e) {
                return !$e->getEncerrado();
            })->forAll(function($e) {
                $e->encerrar();
                $this->orm->getManager()->merge($e);
            });
        $this->orm->getManager()->flush();
        $matricula->getDisciplinasCursadas()
            ->filter(function($d) {
                return $d->getStatus() === DisciplinaCursada::STATUS_CURSANDO;
            })->forAll(function($d) {
                $d->setStatus(DisciplinaCursada::STATUS_INCOMPLETO);
                $this->orm->getManager()->merge($d);
            });
        $this->orm->getManager()->flush();
        $this->reabrirVagas($matricula);
    }
    
    private function reabrirVagas(Matricula $matricula) {
        $enturmacoes = $this->orm->getRepository('MatriculaBundle:Enturmacao')->findBy(['matricula' => $matricula]);
        foreach($enturmacoes as $enturmacao) {
            $vagas = $this->orm->getRepository('CursoBundle:Vaga')->findBy(['enturmacao' => $enturmacao]);
            foreach ($vagas as $vaga) {
                $vaga->setEnturmacao(null);
                $this->orm->getManager()->merge($vaga);
            }
        }
        $this->orm->getManager()->flush();
    }
    
}