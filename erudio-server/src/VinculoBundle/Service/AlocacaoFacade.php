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

namespace VinculoBundle\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\ORM\Exception\IllegalOperationException;
use CoreBundle\Event\EntityEvent;
use VinculoBundle\Entity\Alocacao;

class AlocacaoFacade extends AbstractFacade {
    
    function __construct(RegistryInterface $orm, EventDispatcherInterface $eventDispatcher) {
        parent::__construct($orm, null, $eventDispatcher);
    }
    
    function getEntityClass() {
        return 'VinculoBundle:Alocacao';
    }
    
    function queryAlias() {
        return 'a';
    }
    
    function parameterMap() {
        return [
            'instituicao' => function(QueryBuilder $qb, $value) {
                $qb->join('a.instituicao', 'instituicao')
                    ->andWhere('instituicao.id = :instituicao')->setParameter('instituicao', $value);
            },
            'funcionario' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('vinculo.funcionario = :pessoa')->setParameter('pessoa', $value);
            },
            'funcionario_nome' => function(QueryBuilder $qb, $value) {
                $qb->join('vinculo.funcionario', 'funcionario')
                    ->andWhere('funcionario.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'vinculo' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('vinculo.id = :vinculo')->setParameter('vinculo', $value);
            },
            'professor' => function(QueryBuilder $qb, $value) {
                $qb->join('vinculo.cargo', 'cargo')
                   ->andWhere('cargo.professor = :professor')->setParameter('professor', $value);
            }
        ];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('a.vinculo', 'vinculo');
    }
    
    protected function beforeCreate($alocacao) {
        if ($this->excedeCargaHoraria($alocacao)) {
            throw new IllegalOperationException(
                'Carga horária não pode exceder limite do vínculo'
            );
        }
    }

    protected function afterCreate($alocacao) {
        EntityEvent::createAndDispatch($alocacao, EntityEvent::ACTION_CREATED, $this->eventDispatcher);
    }
    
    protected function afterRemove($alocacao) {
        EntityEvent::createAndDispatch($$alocacao, EntityEvent::ACTION_REMOVED, $this->eventDispatcher);
    }
    
    /**
     * Checa se a criação da alocação viola a carga horária máxima do vínculo.
     * 
     * @param Alocacao $alocacao
     */
    function excedeCargaHoraria(Alocacao $alocacao) {
        $vinculo = $alocacao->getVinculo();
        $vinculo->getAlocacoes()->add($alocacao);
        return !$vinculo->cargaHorariaValida();
    }

}
