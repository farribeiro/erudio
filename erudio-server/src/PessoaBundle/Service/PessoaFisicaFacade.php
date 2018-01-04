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

namespace PessoaBundle\Service;

use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;

class PessoaFisicaFacade extends AbstractFacade {
    
    function getEntityClass() {
        return 'PessoaBundle:PessoaFisica';
    }
    
    function queryAlias() {
        return 'pessoa';
    }
    
    function parameterMap() {
        return [
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'sobrenome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.nome LIKE :sobrenome')->setParameter('sobrenome', '%' . $value . '%');
            },
            'codigo' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.id = :codigo')->setParameter('codigo', explode('-', $value)[0]);
            },
            'dataNascimento' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.dataNascimento = :dataNascimento')->setParameter('dataNascimento', $value);
            },
            'cpf' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.cpfCnpj = :cpf')->setParameter('cpf', $value);
            },
            'certidaoNascimento' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.certidaoNascimento LIKE :certidao')->setParameter('certidao', '%' . $value . '%');
            },
            'nomeMae' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.nomeMae LIKE :nomeMae')->setParameter('nomeMae', '%' . $value . '%');
            },
            'nomePai' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.nomePai LIKE :nomePai')->setParameter('nomePai', '%' . $value . '%');
            },
            'usuario' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.usuario = :usuario')->setParameter('usuario', $value);
            },
            'deficiente' => function(QueryBuilder $qb, $value) {
                $operator = $value ? 'NOT' : '';
                $qb->andWhere("pessoa.particularidades IS {$operator} EMPTY");
            }
        ];
    }
    
    function uniqueMap($pessoa) {
        if ($pessoa->getCpfCnpj()) {
             $checagem = ['cpf' => $pessoa->getCpfCnpj()];
        } else if ($pessoa->getCertidaoNascimento()) {
            $checagem = ['certidaoNascimento' => $pessoa->getCertidaoNascimento()];
        } else {
            $checagem = [
                'nome' => trim($pessoa->getNome()), 
                'dataNascimento' => $pessoa->getDataNascimento()->format('Y-m-d')
            ];
        }
        return [$checagem];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->addOrderBy('pessoa.nome');
    }
    
}
