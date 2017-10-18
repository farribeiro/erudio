<?php

namespace SME\IntranetBundle\Service;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;

class MD5Encoder extends BasePasswordEncoder
{

    private $hash = ",JMJ[`z!Nf12Ye7%-^*8/jW^&;K)";
	
    public function __construct() {

    }

    public function encodePassword($raw, $salt) {
        if (!in_array('md5', hash_algos(), true)) {
            throw new \LogicException('MD5 não é mais suportado pela função hash');
        }
        $digest = hash('md5', $raw . $salt, true);
        return bin2hex($digest);
    }

    public function isPasswordValid($encoded, $raw, $salt) {
        return $this->comparePasswords($encoded, $this->encodePassword($raw, $salt));
    }

    public function encodeMoodlePassword($passwd) {
    	$password = md5($passwd . $this->hash);
    	return $password;
    }
}
