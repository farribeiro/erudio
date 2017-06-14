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
use VinculoBundle\Entity\Alocacao;
use VinculoBundle\Event\AlocacaoEvent;

class AlocacaoFacade extends AbstractFacade {
    
    private $eventDispatcher;
    
    function __construct(RegistryInterface $orm, EventDispatcherInterface $eventDispatcher) {
        parent::__construct($orm);
        $this->eventDispatcher = $eventDispatcher;
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
        $this->eventDispatcher->dispatch(
            AlocacaoEvent::ALOCACAO_CRIADA,
            new AlocacaoEvent($alocacao)
        );
    }
    
    protected function afterRemove($alocacao) {
        $this->eventDispatcher->dispatch(
            AlocacaoEvent::ALOCACAO_REMOVIDA,
            new AlocacaoEvent($alocacao)
        );
    }
    
    /**
     * Checa se a criação da alocação viola a carga horária máxima do vínculo.
     * 
     * @param Alocacao $alocacao
     */
    function excedeCargaHoraria(Alocacao $alocacao) {
        $vinculo = $alocacao->getVinculo();
        $chTotal = $alocacao->getCargaHoraria();
        foreach ($vinculo->getAlocacoes() as $a) {
            $chTotal += $a->getCargaHoraria();
        }
        return $vinculo->getCargaHoraria() < $chTotal;
    }

}
