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

class MovimentacaoTurmaFacade extends AbstractFacade {
    
    private $disciplinaCursadaFacade;
    
    function setDisciplinaCursadaFacade($disciplinaCursadaFacade) {
        $this->disciplinaCursadaFacade = $disciplinaCursadaFacade;
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:MovimentacaoTurma';
    }
    
    function queryAlias() {
        return 'm';
    }
    
    function parameterMap() {
        return array (
            'matricula' => function(QueryBuilder $qb, $value) {
                $qb->join('m.matricula', 'matricula')
                   ->andWhere('matricula.id = :matricula')->setParameter('matricula', $value);
            }
        );
    }
    
    protected function beforeCreate($movimentacao) {
        $movimentacao->aplicar();
        $disciplinasCursadas = $this->disciplinaCursadaFacade->findAll(
            ['enturmacao' => $movimentacao->getEnturmacaoOrigem()->getId()]
        );
        foreach($disciplinasCursadas as $disciplinaCursada) {
            $disciplinaCursada->setEnturmacao($movimentacao->getEnturmacaoDestino());
            foreach($movimentacao->getEnturmacaoDestino()->getTurma()->getDisciplinas() as $disciplinaOfertada) {
                if($disciplinaOfertada->getDisciplina()->getId() === $disciplinaCursada->getDisciplina()->getId()) {
                    $disciplinaCursada->setDisciplinaOfertada($disciplinaOfertada);
                    break;
                }
            }
        }
    }
    
    protected function afterCreate($movimentacao) {
        $origem = $movimentacao->getEnturmacaoOrigem();
        $destino = $movimentacao->getEnturmacaoDestino();
        $vagasOrigem = $this->orm->getRepository('CursoBundle:Vaga')->findBy(array('enturmacao' => $origem));
        $vagaNova = null;
        $turma = $destino->getTurma();
        $vagas = $this->orm->getRepository('CursoBundle:Vaga')->findBy(array('turma' => $turma));
        foreach ($vagas as $vaga) {
            $enturma = $vaga->getEnturmacao();
            if (is_null($enturma)) {
                $vagaNova = $vaga;
            }
        }
        if (is_null($vagaNova)) {
            $vagaNova->setEnturmacao($destino);
            $this->orm->getManager()->merge($vagaNova); 
            $this->orm->getManager()->flush();            
            //Depois de tudo transferido, desabilita a vaga antiga.
            foreach ($vagasOrigem as $vagaOrigem) { 
                $vagaOrigem->setEnturmacao(null); 
                $this->orm->getManager()->merge($vagaOrigem); 
                $this->orm->getManager()->flush();       
            }
        }
    }
    
}
