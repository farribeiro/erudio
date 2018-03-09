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

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\ORM\Exception\IllegalOperationException;
use MatriculaBundle\Entity\Desligamento;
use MatriculaBundle\Entity\Matricula;

class DesligamentoFacade extends AbstractFacade {
    
    private $enturmacaoFacade;
    private $matriculaFacade;
    
    function __construct(RegistryInterface $doctrine, EnturmacaoFacade $enturmacaoFacade,
            MatriculaFacade $matriculaFacade) 
    {
        parent::__construct($doctrine);
        $this->enturmacaoFacade = $enturmacaoFacade;
        $this->matriculaFacade = $matriculaFacade;
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:Desligamento';
    }
    
    function queryAlias() {
        return 'd';
    }
    
    function parameterMap() {
        return [
            'matricula' => function(QueryBuilder $qb, $value) {
                $qb->join('d.matricula', 'matricula')
                   ->andWhere('matricula.id = :matricula')->setParameter('matricula', $value);
            }
        ];
    }
    
    protected function beforeCreate($desligamento) {
        if (!$desligamento->getMatricula()->isCursando()) {
            throw new IllegalOperationException('Matrícula não pode ser desligada em sua situação atual');
        }
    }
    
    protected function afterCreate($desligamento) {
        $this->desligarMatricula($desligamento);
        $this->encerrarEnturmacoes($desligamento->getMatricula());
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
            case Desligamento::MUDANCA_DE_CURSO:
                $matricula->setStatus(Matricula::STATUS_MUDANCA_DE_CURSO);
                break;
            case Desligamento::CANCELAMENTO:
                $matricula->setStatus(Matricula::STATUS_CANCELADO);
        }
        $this->matriculaFacade->patch($matricula->getId(), $matricula);
    }
    
    private function encerrarEnturmacoes(Matricula $matricula) {
        $enturmacoes = $matricula->getEnturmacoesEmAndamento();
        foreach ($enturmacoes as $e) {
            $this->enturmacaoFacade->encerrarPorMovimentacao($e, false);
        }
    }
    
}