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
use CoreBundle\Event\EntityEvent;
use CoreBundle\ORM\Exception\IllegalOperationException;
use CoreBundle\ORM\Exception\IllegalUpdateException;
use VinculoBundle\Entity\Vinculo;

class VinculoFacade extends AbstractFacade {
    
    const LIMITE_VINCULOS_ATIVOS = 2;
    
    function __construct(RegistryInterface $orm, EventDispatcherInterface $eventDispatcher) {
        parent::__construct($orm, null, $eventDispatcher);
    }
    
    function getEntityClass() {
        return 'VinculoBundle:Vinculo';
    }
    
    function queryAlias() {
        return 'v';
    }
    
    function parameterMap() {
        return array (
            'cargo' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('cargo.id = :cargo')->setParameter('cargo', $value);
            },
            'funcionario' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('funcionario.id = :funcionario')->setParameter('funcionario', $value);
            },
            'funcionario_nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('funcionario.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'funcionario_cpf' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('funcionario.cpfCnpj = :cpf')->setParameter('cpf', $value);
            },
            'status' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('v.status = :status')->setParameter('status', $value);
            },
            'codigo' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('v.codigo = :codigo')->setParameter('codigo', $value);
            },
            'professor' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('cargo.professor = :professor')->setParameter('professor', $value);
            }
        );
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('v.funcionario', 'funcionario')->join('v.cargo', 'cargo');
    }
    
    protected function beforeCreate($vinculo) {
        $this->validarNumeroVinculos($vinculo);
        $this->gerarCodigo($vinculo);
    }
    
    protected function afterCreate($vinculo) {
        $this->orm->getManager()->detach($vinculo);
        EntityEvent::createAndDispatch($vinculo, EntityEvent::ACTION_CREATED, $this->eventDispatcher);
    }
    
    protected function afterUpdate($vinculo) {
        if (!$vinculo->cargaHorariaValida()) {
            throw new IllegalUpdateException(
                IllegalUpdateException::ILLEGAL_STATE_TRANSITION, 
                'Carga horária não pode ser inferior ao total das alocações'
            );
        }
    }
    
    /**
     * Gera o código de um vínculo recém-criado. Caso o vínculo informado já possua um
     * código, ele será simplesmente mantido.
     * 
     * @param Vinculo $vinculo
     */
    private function gerarCodigo(Vinculo $vinculo) {
        $now = new \DateTime();
        $ano = $now->format('Y');
        $qb = $this->orm->getManager()->createQueryBuilder()
            ->select('MAX(v.codigo)')
            ->from($this->getEntityClass(), 'v')
            ->where('v.codigo LIKE :codigo')
            ->setParameter('codigo', $ano . $vinculo->getInstituicao()->getId() . '%');
        $numero = $qb->getQuery()->getSingleScalarResult();
        if(!$numero) {
            $numero = $ano . $vinculo->getInstituicao()->getId() . '00000';
        }
        $vinculo->definirCodigo($numero + 1);
    }
    
    protected function validarNumeroVinculos(Vinculo $vinculo) {
        $quantidadeVinculosAtivos = $this->count([
            'funcionario' => $vinculo->getFuncionario(),
            'status' => Vinculo::STATUS_ATIVO
        ]);
        if ($quantidadeVinculosAtivos > self::LIMITE_VINCULOS_ATIVOS) {
            throw new IllegalOperationException(
                'A pessoa não pode possuir mais de ' . self::LIMITE_VINCULOS_ATIVOS 
                .' vínculos ativos'
            );
        }
    }

}

