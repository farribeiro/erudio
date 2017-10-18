<?php

namespace SME\MoodleBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="mdl_files")
 */
class FileUser {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column() */
    private $contextid;
    
    /** @ORM\Column() */
    private $userid;
    
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
    	$this->id = $id;
    }
    
    public function getContextId() {
        return $this->contextid;
    }

    public function setContextId($contextid) {
        $this->contextid = $contextid;
    }

    public function getUserId() {
        return $this->userid;
    }

    public function setUserId($userid) {
        $this->userid = $userid;
    }
}
