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
use AuthBundle\Entity\Usuario;
use AuthBundle\Service\UsuarioFacade;
use CoreBundle\ORM\Exception\IllegalUpdateException;

class MatriculaFacade extends AbstractFacade {
    
    private $usuarioFacade;
    
    function setUsuarioFacade(UsuarioFacade $usuarioFacade) {
        $this->usuarioFacade = $usuarioFacade;
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:Matricula';
    }
    
    function queryAlias() {
        return 'm';
    }
    
    function parameterMap() {
        return array (
            'aluno' => function(QueryBuilder $qb, $value) {
                $qb->join('m.aluno', 'aluno')
                   ->andWhere('aluno.id = :aluno')->setParameter('aluno', $value);
            },
            'aluno_nome' => function(QueryBuilder $qb, $value) {
                $qb->join('m.aluno', 'aluno')
                   ->andWhere('aluno.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'curso' => function(QueryBuilder $qb, $value) {
                $qb->join('m.curso', 'curso')
                   ->andWhere('curso.id = :curso')->setParameter('curso', $value);
            },
            'unidadeEnsino' => function(QueryBuilder $qb, $value) {
                $qb->join('m.unidadeEnsino', 'unidadeEnsino')
                   ->andWhere('unidadeEnsino.id = :unidadeEnsino')->setParameter('unidadeEnsino', $value);
            },
            'codigo' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('m.codigo LIKE :codigo')->setParameter('codigo', '%' . $value . '%');
            },
            'alfabetizado' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('m.alfabetizado = :alfabetizado')->setParameter('alfabetizado', $value);
            },
            'status' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('m.status = :status')->setParameter('status', $value);
            }
        );
    }
    
    protected function beforeCreate($matricula) {
        $this->gerarCodigo($matricula);
    }
    
    protected function afterCreate($matricula) {
        $this->gerarUsuario($matricula);
    }
    
    protected function afterUpdate($matricula) {
        if($matricula->getAlfabetizado() == "") {
            $matricula->setAlfabetizado(null);
        }
        $this->orm->getManager()->flush();
    }
    
    private function gerarCodigo($matricula) {
        $now = new \DateTime();
        $ano = $now->format('Y');
        $qb = $this->orm->getManager()->createQueryBuilder()
            ->select('MAX(m.codigo)')
            ->from($this->getEntityClass(), 'm')
            ->where('m.codigo LIKE :codigo')
            ->setParameter('codigo', $ano . $matricula->getCurso()->getId() . '%');
        $numero = $qb->getQuery()->getSingleScalarResult();
        if(!$numero) {
            $numero = $ano . $matricula->getCurso()->getId() . '00000';
        }
        $matricula->definirCodigo($numero + 1);
    }
    
    private function gerarUsuario($matricula) {
        $pessoa = $matricula->getAluno();
        if (!$pessoa->getUsuario()) {
            $usuario = Usuario::criarUsuario($pessoa, $matricula->getCodigo());
            $this->usuarioFacade->create($usuario);
            $matricula->getAluno()->setUsuario($usuario);
            $this->orm->getManager()->merge($pessoa);
            $this->orm->getManager()->flush();
        }
    }
    
}

