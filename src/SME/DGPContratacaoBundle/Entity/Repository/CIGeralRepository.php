<?php

namespace SME\DGPContratacaoBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class CIGeralRepository extends EntityRepository {
    
    public function find($id)
    {
        $ci = parent::find($id);
        if($ci) {
            $vinculos = $this->_em->createQuery('select v from DGPBundle:Vinculo v join v.cargo c join v.ciGeral ci'
                    . ' where ci.id=:ci order by c.nome')->setParameter('ci', $id)->execute();
            $ci->setVinculos(new ArrayCollection($vinculos));
        }
        return $ci;
    }
    
}
