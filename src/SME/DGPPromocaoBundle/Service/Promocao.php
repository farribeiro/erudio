<?php

namespace SME\DGPPromocaoBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\NoResultException;
use SME\DGPPromocaoBundle\Entity\Promocao as PromocaoEntity;
use SME\DGPPromocaoBundle\Entity\PromocaoHorizontal;
use SME\DGPPromocaoBundle\Entity\PromocaoVertical;

class Promocao {
    
    const COD_HORIZONTAL = 1;
    const COD_VERTICAL = 2;
    
    private $orm;
    
    public function __construct (Registry $doctrine) {
        $this->orm = $doctrine;
    }
        
    public function definirProtocolo(PromocaoEntity $promocao) {
        if(!$promocao->getDataCadastro()) {
            $promocao->setDataCadastro(new \DateTime());
        }
        $tipoPromocao = $promocao instanceof PromocaoHorizontal ? 'PromocaoHorizontal' : 'PromocaoVertical';
        $codigo = $promocao instanceof PromocaoHorizontal ? self::COD_HORIZONTAL : self::COD_VERTICAL;
        $qb = $this->orm->getManager()->createQueryBuilder()
            ->select('MAX(p.protocolo)')
            ->from('DGPPromocaoBundle:' . $tipoPromocao, 'p')
            ->where('p.protocolo LIKE :protocolo')
            ->setParameter('protocolo', $promocao->getDataCadastro()->format('Y') . $codigo . '%');
        $numero = $qb->getQuery()->getSingleScalarResult();
        if(!$numero) {
            $numero = $promocao->getDataCadastro()->format('Y') . $codigo . '00000';
        }
        $promocao->setProtocolo($numero + 1);
    }
    
}

