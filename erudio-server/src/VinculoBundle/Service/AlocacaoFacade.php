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

use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use VinculoBundle\Entity\Alocacao;
use AuthBundle\Entity\Atribuicao;
use AuthBundle\Service\AtribuicaoFacade;
use CoreBundle\ORM\Exception\IllegalOperationException;
use CoreBundle\ORM\Exception\UniqueViolationException;

class AlocacaoFacade extends AbstractFacade {
    
    private $atribuicaoFacade;
    
    function setAtribuicaoFacade(AtribuicaoFacade $atribuicaoFacade) {
        $this->atribuicaoFacade = $atribuicaoFacade;
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
        $this->validarCargaHoraria($alocacao);
    }

    protected function afterCreate($alocacao) {
        $this->gerarAtribuicao($alocacao);
    }
    
    protected function afterRemove($alocacao) {
        $this->removerAtribuicao($alocacao);
    }
    
    /**
     * Checa se a criação da alocação viola a carga horária máxima do vínculo.
     * 
     * @param Alocacao $alocacao
     */
    private function validarCargaHoraria(Alocacao $alocacao) {
        $vinculo = $alocacao->getVinculo();
        $chTotal = $alocacao->getCargaHoraria();
        foreach ($vinculo->getAlocacoes() as $a) {
            $chTotal += $a->getCargaHoraria();
        }
        if ($vinculo->getCargaHoraria() < $chTotal) {
            throw new IllegalOperationException(
                'Carga horária não pode exceder limite do vínculo'
            );
        }
    }
    
    /**
     * Gera uma atribuição para o usuário da pessoa alocada, contendo as permissões
     * pertinentes à ela.
     * 
     * @param Alocacao $alocacao
     */
    private function gerarAtribuicao(Alocacao $alocacao) {
        $grupo = $alocacao->getVinculo()->getCargo()->getGrupo();
        if ($grupo) {
            try {
                $atribuicao = Atribuicao::criarAtribuicao(
                    $alocacao->getVinculo()->getFuncionario()->getUsuario(), 
                    $grupo, 
                    $alocacao->getInstituicao()
                );
                $this->atribuicaoFacade->create($atribuicao, false);
            } catch (UniqueViolationException $ex) {
                //ignorar se já existe
            }
        }
    }
    
    /**
     * Remove a atribuição que foi gerada para o usuário da pessoa no ato da alocação,
     * retirando assim suas permissões.
     * 
     * @param Alocacao $alocacao
     */
    private function removerAtribuicao(Alocacao $alocacao) {
        $atribuicoes = $this->atribuicaoFacade->findAll([
            'usuario' => $alocacao->getVinculo()->getFuncionario()->getUsuario(),
            'grupo' => $alocacao->getVinculo()->getCargo()->getGrupo(), 
            'instituicao' => $alocacao->getInstituicao()
        ]);
        foreach ($atribuicoes as $a) {
            $this->atribuicaoFacade->remove($a->getId());
        }
    }

}
