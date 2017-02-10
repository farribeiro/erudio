<?php
namespace AuthBundle\Security\Authentication\Provider;

date_default_timezone_set('America/Sao_Paulo');

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use AuthBundle\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\Security\Core\Util\StringUtils;

class WsseProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;
    private $lifetime;

    public function __construct(UserProviderInterface $userProvider, $cacheDir, $lifetime) {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
        $this->lifetime     = $lifetime;
    }

    public function authenticate(TokenInterface $token) {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        if ($user && $this->validateDigest($token->digest, $token->nonce, $token->created, $user->getPassword())) {
            $authenticatedToken = new WsseUserToken($user->getRoles());
            $authenticatedToken->setUser($user);
            return $authenticatedToken;
        }
        
        throw new AuthenticationException('The WSSE authentication failed.');
    }

    protected function validateDigest($digest, $nonce, $created, $secret) {
        $date = new \DateTime();
        $date->modify('+15 minutes');
        $time = $date->getTimestamp();
        
        // Check created time is not in the future
        if (strtotime($created) > $time) {
            return false;
        }
        
        // Expire timestamp after 5 minutes
        if ($time - strtotime($created) > $this->lifetime) {
            return false;
        }
        
        // Validate that the nonce is *not* used in the last 5 minutes
        // if it has, this could be a replay attack
        //if (file_exists($this->cacheDir.'/'.$nonce) && file_get_contents($this->cacheDir.'/'.$nonce) + $this->lifetime > $time) {
        //    throw new NonceExpiredException('Previously used nonce detected');
        //}

        // If cache directory does not exist create it
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
        //file_put_contents($this->cacheDir.'/'.$nonce, $time);

        // Validate Secret
        $expected = \base64_encode(\sha1(\base64_decode($nonce).$created.$secret, false));
        return StringUtils::equals($expected, $digest);
    }

    public function supports(TokenInterface $token) {
        return $token instanceof WsseUserToken;
    }
}