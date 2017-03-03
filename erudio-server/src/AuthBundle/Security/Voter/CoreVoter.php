<?php

namespace AuthBundle\Security\Voter;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CoreVoter extends Voter {
    
    const VIEW = 'view';
    const EDIT = 'edit';
    
    private $orm;
    
    public function __construct (Registry $doctrine) {
        $this->orm = $doctrine;
    }
    
    protected function supports($attribute, $subject) {
        
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token) {
        $usuario = $token->getUser();
    }

}
