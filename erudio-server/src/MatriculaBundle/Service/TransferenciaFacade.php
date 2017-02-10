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
use CoreBundle\ORM\Exception\IllegalUpdateException;
use MatriculaBundle\Entity\Transferencia;
use MatriculaBundle\Entity\DisciplinaCursada;
use MatriculaBundle\Entity\Media;

class TransferenciaFacade extends AbstractFacade {
    
    private $mediaFacade;
    
    function setMediaFacade($mediaFacade) {
        $this->mediaFacade = $mediaFacade;
    }
    
     function getEntityClass() {
        return 'MatriculaBundle:Transferencia';
    }
    
    function queryAlias() {
        return 't';
    }
    
    function parameterMap() {
        return array (
            'matricula' => function(QueryBuilder $qb, $value) {
                $qb->join('t.matricula', 'matricula')
                   ->andWhere('matricula.id = :matricula')->setParameter('matricula', $value);
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
        );
    }
    
    protected function beforeUpdate($transferencia) {
        if($transferencia->getStatus() !== Transferencia::STATUS_PENDENTE) {
            throw new IllegalUpdateException(
                IllegalUpdateException::FINAL_STATE, 
                'Operação não permitida, a transferência já foi finalizada'
            );
        }
    }
    
    protected function afterUpdate($transferencia) {        
        if($transferencia->getStatus() !== Transferencia::STATUS_PENDENTE) {
            if($transferencia->getStatus() === Transferencia::STATUS_ACEITO) {
                $matricula = $transferencia->getMatricula();
                $enturmacao = $this->orm->getRepository('MatriculaBundle:Enturmacao')->findOneBy(array('matricula' => $matricula));
                $vagas = $this->orm->getRepository('CursoBundle:Vaga')->findBy(array('enturmacao' => $enturmacao));
                foreach ($vagas as $vaga) {
                    $vaga->setEnturmacao(null);
                    $this->orm->getManager()->merge($vaga);
                    $this->orm->getManager()->flush();
                }
                $matricula->getDisciplinasCursadas()
                    ->filter(function($t) {
                        return $t->getStatus() === DisciplinaCursada::STATUS_CURSANDO;
                    })->map(function($t) {
                        $t->setStatus(DisciplinaCursada::STATUS_INCOMPLETO);
                        $this->orm->getManager()->merge($t);
                        $novaDisciplina = new DisciplinaCursada($t->getMatricula(), $t->getDisciplina());
                        $numeroMedias = $novaDisciplina->getDisciplina()->getEtapa()->getSistemaAvaliacao()->getQuantidadeMedias();  
                        for($i = 1; $i <= $numeroMedias; $i++) {
                                $media = new Media($novaDisciplina, $i);
                                $this->mediaFacade->create($media);
                        }
                        $this->orm->getManager()->persist($novaDisciplina);
                    });
                $transferencia->getMatricula()->transferir($transferencia->getUnidadeEnsinoDestino());
            }
            $transferencia->setDataEncerramento(new \DateTime());
            $this->orm->getManager()->merge($transferencia);
            $this->orm->getManager()->flush();
        }
    }
    
    function findAllByNome($params, $page = null) {
        $nome = $params['nome'];
        $qb = $this->orm->getRepository('PessoaBundle:PessoaFisica')->createQueryBuilder('p')->where('p.nome LIKE :nome')->setParameter('nome', '%'.$nome.'%');
        if(is_numeric($page)) { $qb->setMaxResults(self::PAGE_SIZE)->setFirstResult(self::PAGE_SIZE * $page); }
        $pessoas = $qb->getQuery()->getResult();
        $matriculas = array();
        $transferencias = array();
        
        if (!empty($pessoas)) {
            foreach ($pessoas as $pessoa) {
                $matriculaArray = $this->orm->getRepository('MatriculaBundle:Matricula')->findBy(array('aluno'=>$pessoa));
                if (!empty($matriculaArray)) { foreach ($matriculaArray as $matArray) { $matriculas[] = $matArray; } }
            }
            
            if (!empty($matriculas)) {
                unset($params['nome']);
                foreach ($matriculas as $matricula) {
                    $params['matricula'] = $matricula->getId();
                    $transferenciasArray = $this->findAll($params);
                    foreach ($transferenciasArray as $transArray) { $transferencias[] = $transArray; }
                }
                
                if (!empty($transferencias)) { return $transferencias; } else { return array(); }
            } else { return array(); }
        } else { return array(); }
    }
}

