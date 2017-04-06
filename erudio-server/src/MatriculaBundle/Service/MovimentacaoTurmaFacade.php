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
use CoreBundle\ORM\Exception\IllegalOperationException;
use MatriculaBundle\Entity\Enturmacao;

class MovimentacaoTurmaFacade extends AbstractFacade {
    
    private $enturmacaoFacade;
    
    function setEnturmacaoFacade(EnturmacaoFacade $enturmacaoFacade) {
        $this->enturmacaoFacade = $enturmacaoFacade;
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
        $turmaOrigem = $movimentacao->getEnturmacaoOrigem()->getTurma();
        if ($movimentacao->getTurmaDestino()->getEtapa()->getId() != $turmaOrigem->getEtapa()->getId()) {
            throw new IllegalOperationException(
                'Uma movimentação de turma só pode ser realizada entre turmas da mesma etapa.'
            );
        }
        $enturmacaoDestino = $this->enturmacaoFacade->create(
            new Enturmacao($movimentacao->getMatricula(), $movimentacao->getTurmaDestino())
        );
        $movimentacao->aplicar($enturmacaoDestino);
    }
    
    protected function afterCreate($movimentacao) {
        $this->enturmacaoFacade->encerrarPorMovimentacao($movimentacao->getEnturmacaoOrigem());
    }
    
}
