<?php

namespace SME\IntranetBundle\Listener;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use SME\MoodleBundle\Entity\MoodleUser;
use SME\ErudioBundle\Entity\ErudioUser;
use SME\ErudioBundle\Entity\ErudioPessoa;
use SME\ErudioBundle\Entity\ErudioPessoaFisica;

class LoginListener implements AuthenticationSuccessHandlerInterface
{
 
    private $router;
    private $kernel;
    private $providerKey;
    private $em;

    public function __construct(RouterInterface $router, Registry $doctrine, $kernel) {
       $this->router = $router;
       $this->em = $doctrine;
       $this->kernel = $kernel;
    }
 
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
         $user = $token->getUser();
         
         try {
            $erudioUsers = $this->em->getManager('erudio')->getRepository('ErudioBundle:ErudioUser')->findBy(array('username' => $user->getUsername()));
            if (count($erudioUsers) === 0) {
                $this->createErudioUser($user);
            }
         } catch (Exception $ex) {
             echo $ex->getMessage(); die();
         }
         
         try {
            $moodleUsers = $this->em->getManager('moodle')->getRepository('MoodleBundle:MoodleUser')->findBy(array('username' => $user->getUsername()));
            if(count($moodleUsers) === 0) {
                $this->createMoodleUser($user);
            }
         } catch(\Exception $ex) {
             
         }
         
         //$url = "http://voldemort/moodle4/intranetLogin.php?username=" . $user->getUsername() . "&env=" . $this->kernel->getEnvironment();
         $url = "http://ead.educacao.itajai.sc.gov.br/intranetLogin.php?username=" . $user->getUsername() . "&link=" . $_SERVER['REQUEST_URI'];
         //$url = $this->router->generate('intranet_index');
         return new RedirectResponse($url);
    }
     
    private function createErudioUser($user) {
        $pessoaFisica = $this->em->getManager()->getRepository('CommonsBundle:PessoaFisica')->findOneBy(array('cpfCnpj' => $user->getUsername()));
        
        $erudioUser = new ErudioUser();
        $erudioUser->setNomeExibicao($pessoaFisica->getNome());
        $erudioUser->setUsername($pessoaFisica->getCpfCnpj());
        $erudioUser->setPassword($user->getPassword());
        $this->em->getManager('erudio')->persist($erudioUser);
        $this->em->getManager('erudio')->flush();
        
        $erudioPessoa = new ErudioPessoa();
        $erudioPessoa->setNome($pessoaFisica->getNome());
        $erudioPessoa->setCpfCnpj($pessoaFisica->getCpfCnpj());
        $erudioPessoa->setDataNascimento($pessoaFisica->getDataNascimento());
        $erudioPessoa->setUsuarioId($erudioUser->getId());
        $this->em->getManager('erudio')->persist($erudioPessoa);
        $this->em->getManager('erudio')->flush();
        
        $erudioPessoaFisica = new ErudioPessoaFisica();
        $erudioPessoaFisica->setId($erudioPessoa->getId());
        $this->em->getManager('erudio')->persist($erudioPessoaFisica);
        $this->em->getManager('erudio')->flush();
    } 

    private function createMoodleUser($user) {
        $pessoaFisica = $this->em->getManager()->getRepository('CommonsBundle:PessoaFisica')->findOneBy(array('cpfCnpj' => $user->getUsername()));
        $legacyUsers = $this->em->getManager('moodle')->getRepository('MoodleBundle:MoodleUser')->findBy(array('email' => $pessoaFisica->getEmail()));
        if(count($legacyUsers) === 1) {
            $moodleUser = $legacyUsers[0];
            $moodleUser->setUsername($pessoaFisica->getCpfCnpj());
            $this->em->getManager('moodle')->merge($moodleUser);
            $this->em->getManager('moodle')->flush();
        } else {
            $name = explode(' ', $pessoaFisica->getNome(), 2);
            $moodleUser = new MoodleUser();
            $moodleUser->setUsername($pessoaFisica->getCpfCnpj());
            $moodleUser->setFirstname($name[0]);
            $moodleUser->setLastname($name[1]);
            $moodleUser->setEmail($pessoaFisica->getEmail());
            $moodleUser->setPassword($user->getPassword());
             
            if($pessoaFisica->getEndereco()) {
                $cidade = $pessoaFisica->getEndereco()->getCidade();
                if ($cidade == null) {
                    $moodleUser->setCity('Itajaí');
                } else {
                    $moodleUser->setCity($pessoaFisica->getEndereco()->getCidade()->getNome());
                }
            }
            $this->em->getManager('moodle')->persist($moodleUser);
            $this->em->getManager('moodle')->flush();
        }
    }

    public function getProviderKey() {
        return $this->providerKey;
    }

    public function setProviderKey($providerKey) {
        $this->providerKey = $providerKey;
    }

}