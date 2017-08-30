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
use CoreBundle\ORM\Exception\IllegalUpdateException; 
use MatriculaBundle\Entity\Transferencia;
use MatriculaBundle\Entity\Matricula;

class TransferenciaFacade extends AbstractFacade {
    
    private $enturmacaoFacade;
    private $mediaFacade;
    
    function __construct(RegistryInterface $doctrine, EnturmacaoFacade $enturmacaoFacade, MediaFacade $mediaFacade) {
        parent::__construct($doctrine);
        $this->enturmacaoFacade = $enturmacaoFacade;
        $this->mediaFacade = $mediaFacade;
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:Transferencia';
    }
    
    function queryAlias() {
        return 't';
    }
    
    function parameterMap() {
        return [
            'matricula' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('matricula.id = :matricula')->setParameter('matricula', $value);
            },
            'matricula_aluno_nome' => function(QueryBuilder $qb, $value) {
                $qb->join('matricula.aluno', 'aluno')
                   ->andWhere('aluno.nome LIKE :aluno')->setParameter('aluno', '%' . $value . '%');
            },
            'status' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.status = :status')->setParameter('status', $value);
            },
            'unidadeEnsinoOrigem' => function(QueryBuilder $qb, $value) {
                $qb->join('t.unidadeEnsinoOrigem', 'unidadeOrigem')
                   ->andWhere('unidadeOrigem.id = :unidadeOrigem')->setParameter('unidadeOrigem', $value);
            },
            'unidadeEnsinoDestino' => function(QueryBuilder $qb, $value) {
                $qb->join('t.unidadeEnsinoDestino', 'unidadeDestino')
                   ->andWhere('unidadeDestino.id = :unidadeDestino')->setParameter('unidadeDestino', $value);
            }
        ];
    }
    
    function uniqueMap($transferencia) {
        return [
            ['matricula' => $transferencia->getMatricula(), 'status' => Transferencia::STATUS_PENDENTE]
        ];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('t.matricula', 'matricula');
    }
    
    protected function beforeUpdate($transferencia) {
        if ($transferencia->getStatus() !== Transferencia::STATUS_PENDENTE) {
            throw new IllegalUpdateException(
                IllegalUpdateException::FINAL_STATE, 
                'Operação não permitida, a transferência já foi finalizada'
            );
        }
    }
    
    protected function afterCreate($transferencia) {        
        if($transferencia->getStatus() !== Transferencia::STATUS_PENDENTE) {
            $this->encerrar($transferencia);
        }
    }
    
    protected function afterUpdate($transferencia) {        
        if($transferencia->getStatus() !== Transferencia::STATUS_PENDENTE) {
            $this->encerrar($transferencia);
        }
    }
    
    function encerrar(Transferencia $transferencia) {
        if($transferencia->getStatus() === Transferencia::STATUS_ACEITO) {
            $this->encerrarEnturmacoes($transferencia->getMatricula());
            $transferencia->getMatricula()->transferir($transferencia->getUnidadeEnsinoDestino());
        }
        $transferencia->setDataEncerramento(new \DateTime());
        $this->orm->getManager()->merge($transferencia);
        $this->orm->getManager()->flush();
    }
    
    private function encerrarEnturmacoes(Matricula $matricula) {
        $enturmacoes = $this->enturmacaoFacade->findAll(['matricula' => $matricula]);
        foreach ($enturmacoes as $enturmacao) {
            $this->enturmacaoFacade->encerrarPorMovimentacao($enturmacao);
        }
        $this->orm->getManager()->flush();
    }

}

