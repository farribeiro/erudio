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
 *    02111-1307, USA.                                               *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace MatriculaBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use MatriculaBundle\Entity\Reclassificacao;
use MatriculaBundle\Entity\Enturmacao;
use CoreBundle\ORM\Exception\IllegalOperationException;

class ReclassificacaoFacade extends AbstractFacade {
    
    private $enturmacaoFacade;
    
    function __construct(RegistryInterface $doctrine, EnturmacaoFacade $enturmacaoFacade) {
        parent::__construct($doctrine);
        $this->enturmacaoFacade = $enturmacaoFacade;
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:Reclassificacao';
    }
    
    function queryAlias() {
        return 'm';
    }
    
    function parameterMap() {
        return [
            'matricula' => function(QueryBuilder $qb, $value) {
                $qb->join('m.matricula', 'matricula')
                   ->andWhere('matricula.id = :matricula')->setParameter('matricula', $value);
            }
        ];
    }
    
    protected function beforeCreate($reclassificacao) {
        if ($reclassificacao->getNotas()->count() == 0) {
            throw new IllegalOperationException('É necessário registrar ao menos uma nota em uma reclassificação');
        }
        foreach ($reclassificacao->getNotas() as $nota) {
            $nota->setReclassificacao($reclassificacao);
        }
        $this->validarReclassificacao($reclassificacao);
        $this->gerarEnturmacaoDestino($reclassificacao);
    }
    
    protected function afterCreate($reclassificacao) {
        $this->encerrarEnturmacaoOrigem($reclassificacao);
    }
    
    private function validarReclassificacao(Reclassificacao $reclassificacao) {
        $etapaOrigem = $reclassificacao->getEnturmacaoOrigem()->getTurma()->getEtapa();
        $etapaDestino = $reclassificacao->getTurmaDestino()->getEtapa();
        if ($etapaOrigem->getOrdem() >= $etapaDestino->getOrdem()) {
            throw new IllegalOperationException('A etapa de destino de uma reclassificação deve ser superior à de origem');
        }
        if ($etapaOrigem->getOrdem() + 2 < $etapaDestino->getOrdem()) {
            throw new IllegalOperationException('A etapa de destino de uma reclassificação não pode ser mais de dois níveis acima da origem');
        }
        return true;
    }
    
    private function encerrarEnturmacaoOrigem(Reclassificacao $reclassificacao) {
        $this->enturmacaoFacade->encerrarPorMovimentacao($reclassificacao->getEnturmacaoOrigem(), false);
    }
    
    private function gerarEnturmacaoDestino(Reclassificacao $reclassificacao) {
        $enturmacaoDestino = $this->enturmacaoFacade->create(
            new Enturmacao($reclassificacao->getMatricula(), $reclassificacao->getTurmaDestino())
        );
        $reclassificacao->aplicar($enturmacaoDestino);
    }
    
}
