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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\Event\EntityEvent;
use CoreBundle\ORM\Exception\IllegalUpdateException;
use MatriculaBundle\Entity\Enturmacao;
use MatriculaBundle\Entity\DisciplinaCursada;
use MatriculaBundle\Model\RegistroFaltas;
use MatriculaBundle\Traits\CalculosMedia;
use MatriculaBundle\Entity\Media;

class MediaFacade extends AbstractFacade {
    
    use CalculosMedia;
    
    function __construct(RegistryInterface $doctrine, LoggerInterface $logger, 
            EventDispatcherInterface $eventDispatcher) {
        parent::__construct($doctrine, $logger, $eventDispatcher);
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:Media';
    }
    
    function queryAlias() {
        return 'm';
    }
    
    function parameterMap() {
        return [
            'numero' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('m.numero = :numero')->setParameter('numero', $value);
            },
            'disciplinaCursada' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('disciplinaCursada = :disciplinaCursada')
                    ->setParameter('disciplinaCursada', $value);
            },
            'disciplinaOfertada' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('disciplinaCursada.disciplinaOfertada = :disciplinaOfertada')
                   ->andWhere('disciplinaCursada.status <> :incompleto')
                   ->andWhere('disciplinaCursada.ativo = true')
                   ->setParameter('incompleto', DisciplinaCursada::STATUS_INCOMPLETO)
                   ->setParameter('disciplinaOfertada', $value);
            },
            'enturmacao' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('disciplinaCursada.enturmacao = :enturmacao')
                   ->setParameter('enturmacao', $value);
            },
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->join('disciplinaCursada.disciplinaOfertada', 'disciplinaOfertada')
                    ->andWhere('disciplinaOfertada.turma = :turma')
                    ->setParameter('turma', $value);
            }
        ];
    }
    
    function uniqueMap($media) {
        return [[
            'numero' => $media->getNumero(), 
            'disciplinaCursada' => $media->getDisciplinaCursada()
        ]];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('m.disciplinaCursada', 'disciplinaCursada')
           ->join('disciplinaCursada.matricula', 'matricula')
           ->join('matricula.aluno', 'aluno')
           ->orderBy('aluno.nome');
    }
    
    function getFaltasUnificadas(Enturmacao $enturmacao, $numeroMedia) {
        $primeiraDisciplina = $enturmacao->getDisciplinasCursadas()->first();
        if (!$primeiraDisciplina) {
            throw new \Exception("O aluno com a matrícula {$enturmacao->getMatricula()->getCodigo()} "
                . "não possui disciplinas em sua enturmação");
        }
        $media = $this->findAll([
            'numero' => $numeroMedia, 
            'disciplinaCursada' => $primeiraDisciplina->getId()
        ])[0];
        return new RegistroFaltas($enturmacao, $media->getNumero(), $media->getFaltas());
    }
    
    function inserirFaltasUnificadas($faltas, $numeroMedia, Enturmacao $enturmacao) {
        $medias = $this->findAll(['numero' => $numeroMedia, 'enturmacao' => $enturmacao->getId()]);
        foreach ($medias as $media) {
            $this->validarAtualizacao($media);
            $media->setFaltas($faltas);
            $this->orm->getManager()->flush();
        }
    }
    
    protected function beforeApplyChanges($media, $patch) {
        $this->validarAtualizacao($media);
        $sistemaAvaliacao = $media->getDisciplinaCursada()->getEtapa()->getSistemaAvaliacao();
        $calcularValor = is_null($patch->getValor()) && $sistemaAvaliacao->utilizaNotas();
        if ($calcularValor) {
            $patch->setValor($this->calcularMedia($media));
        }
        EntityEvent::createAndDispatch($media, EntityEvent::ACTION_WILL_APPLY_PATCH, $this->eventDispatcher);
    }
    
    function validarAtualizacao(Media $media) {
        if ($media->getDisciplinaCursada()->getStatus() !== DisciplinaCursada::STATUS_CURSANDO && !$media->isExame()) {
            throw new IllegalUpdateException(
                IllegalUpdateException::FINAL_STATE, 
                'Média parcial não pode ser alterada após consolidação da média final'
            );
        }
        if (!$media->getDisciplinaCursada()->emAberto()) {
            throw new IllegalUpdateException(
                IllegalUpdateException::FINAL_STATE, 
                'Valor não pode ser alterado, média final da disciplina já foi consolidado'
            );
        }
    }

}

