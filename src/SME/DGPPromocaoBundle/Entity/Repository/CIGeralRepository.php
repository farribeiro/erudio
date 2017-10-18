<?php

namespace SME\DGPPromocaoBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class CIGeralRepository extends EntityRepository {
    
    public function find($id)
    {
        $ci = parent::find($id);
        if($ci) {
            $promocoes = $this->_em->createQuery('select p from DGPPromocaoBundle:Promocao p'
                    . ' join p.vinculo v join v.servidor s join p.ciGeral c'
                    . ' where c.id = :ci order by s.nome')->setParameter('ci', $id)->execute();
            $ci->setPromocoes(new ArrayCollection($promocoes));
        }
        return $ci;
    }
    
}
