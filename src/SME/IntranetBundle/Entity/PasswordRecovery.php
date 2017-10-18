<?php

namespace SME\IntranetBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_intranet_password_recovery")
 */
class PasswordRecovery implements \Serializable, \JsonSerializable {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(name="user_id", nullable=false)
	 */
	private $userId;

	/** @ORM\Column(nullable=false, length=200) */
	private $token;

	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	private $data;

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getUserId() {
		return $this->userId;
	}

	public function setUserId($userId) {
		$this->userId = $userId;
	}

	public function getToken() {
		return $this->token;
	}

	public function setToken($token) {
		$this->token = $token;
	}

	public function getData() {
		return $this->data;
	}

	public function setData($data) {
		$this->data = $data;
	}

	public function serialize()
	{
		return \json_encode(array($this->id, $this->userId, $this->token, $this->data));
	}

	public function unserialize($serialized)
	{
		list($this->id, $this->userId, $this->token, $this->data) = \json_decode($serialized);
	}

	public function jsonSerialize() {
		return array(
				'id' => $this->id,
				'userId' => $this->userId,
				'token' => $this->token,
				'data' => $this->data
		);
	}

}

?>