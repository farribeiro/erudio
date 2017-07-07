<?php

namespace SME\MoodleBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="mdl_user")
 */
class MoodleUser {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column() */
    private $username;
    
    /** @ORM\Column() */
    private $password;
    
    /** @ORM\Column() */
    private $auth = 'manual';
    
    /** @ORM\Column() */
    private $firstname;

    /** @ORM\Column() */
    private $lastname;
    
    /** @ORM\Column() */
    private $email;
    
    /** @ORM\Column() */
    private $confirmed = 1;
    
    /** @ORM\Column() */
    private $mnethostid = 1;
    
    /** @ORM\Column() */
    private $city = 'Itajaí - SC';
    
    /** @ORM\Column() */
    private $lang = 'pt_br';
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
    	$this->id = $id;
    }
    
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->username;
    }

    public function setPassword($password) {
        $this->password = md5($password);
    }

    public function getAuth() {
        return $this->nomeExibicao;
    }

    public function setAuth($auth) {
        $this->auth = $auth;
    }
    
    public function getFirstname() {
    	return $this->firstname;
    }
    
    public function setFirstname($firstname) {
    	$this->firstname = $firstname;
    }
    
    public function getLastname() {
    	return $this->lastname;
    }
    
    public function setLastname($lastname) {
    	$this->lastname = $lastname;
    }
    
    public function getEmail() {
    	return $this->email;
    }
    
    public function setEmail($email) {
    	$this->email = $email;
    }
    
    public function getConfirmed() {
        return $this->confirmed;
    }

    public function getMnethostid() {
        return $this->mnethostid;
    }

    public function getCity() {
        return $this->city;
    }

    public function getLang() {
        return $this->lang;
    }

    public function setConfirmed($confirmed) {
        $this->confirmed = $confirmed;
    }

    public function setMnethostid($mnethostid) {
        $this->mnethostid = $mnethostid;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function setLang($lang) {
        $this->lang = $lang;
    }

}
