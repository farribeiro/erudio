<?php

namespace SME\IntranetBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Validator\Validator;
use SME\IntranetBundle\Entity\PortalUser;

class IntranetUserProvider implements UserProviderInterface {
    
    private $orm;
    private $validator;
    
    public function __construct (Registry $doctrine, Validator $validator) {
        $this->orm = $doctrine;
        $this->validator = $validator;
    }
    
    public function loadUserByUsername($username) {
        $user = $this->orm->getRepository('IntranetBundle:PortalUser')->findOneBy(array('username' => $username));
        if($user) {
            return $user;
        }
        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));  
    }

    public function refreshUser(UserInterface $user) {
        if (!$user instanceof PortalUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class) {
        return $class === 'SME\IntranetBundle\Entity\PortalUser';
    }
    
}
