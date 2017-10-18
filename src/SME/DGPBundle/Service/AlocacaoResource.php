<?php

namespace SME\DGPBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Validator\Validator;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\CommonsBundle\Entity\Entidade;
use SME\DGPBundle\Entity\Alocacao;

/**
 * Serviço de alocação de servidores
 * Permite executar as operações CRUD sobre a entidade Alocacao 
 * 
 */
class AlocacaoResource {
    
    private $orm;
    private $validator;
    
    public function __construct (Registry $doctrine, Validator $validator) {
        $this->orm = $doctrine;
        $this->validator = $validator;
    }
    
    public function find($id) {
        return $this->orm->getRepository('DGPBundle:Alocacao')->find($id);
    }
    
    public function findBy(array $criteria = array('ativo' => true), array $orderBy = array()) {
        return $this->orm->getRepository('DGPBundle:Alocacao')->findBy($criteria, $orderBy);
    }
    
    public function findByLocalTrabalho(Entidade $entidade) {
        $qb = $this->orm->getManager()->createQueryBuilder();
        return $qb->select('a')
            ->from('DGPBundle:Alocacao','a')
            ->join('a.localTrabalho', 'l')->join('a.vinculoServidor', 'v')->join('v.cargo', 'c')
            ->where('a.ativo = true AND l.id = :local')
            ->setParameter('local', $entidade->getId())
            ->orderBy('c.nome', 'ASC')
            ->getQuery()->getResult();
    }
    
    public function findByPessoa(PessoaFisica $pessoa) {
        $qb = $this->orm->getManager()->createQueryBuilder();
        return $qb->select('a')
            ->from('DGPBundle:Alocacao','a')
            ->join('a.localTrabalho', 'l')->join('l.pessoaJuridica', 'pj')
            ->join('a.vinculoServidor', 'v')->join('v.servidor', 'p')
            ->where('a.ativo = true AND p.id = :pessoa')
            ->setParameter('pessoa', $pessoa->getId())
            ->orderBy('pj.nome', 'ASC')
            ->getQuery()->getResult();
    }
    
    public function create(Alocacao $alocacao) {
        $this->orm->getManager()->persist($alocacao);
        $this->orm->getManager()->flush();
    }
    
    public function merge(Alocacao $alocacao) {
        $this->orm->getManager()->merge($alocacao);
        $this->orm->getManager()->flush();
    }
    
    public function remove(Alocacao $alocacao) {
        $alocacao->setAtivo(false);
        $this->orm->getManager()->merge($alocacao);
        $this->orm->getManager()->flush();
    }
    
}
