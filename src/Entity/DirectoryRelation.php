<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="directory_relations")
 * @ORM\Entity
 */
class DirectoryRelation {
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	private $id;
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $type;
	/**
	 * @var DirectoryObject
	 * @ORM\ManyToOne(targetEntity="DirectoryObject", inversedBy="relations")
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
	 */
	private $owner;
	/**
	 * @var DirectoryObject
	 * @ORM\ManyToOne(targetEntity="DirectoryObject", inversedBy="inverseRelations")
	 * @ORM\JoinColumn(name="target_id", referencedColumnName="id")
	 */
	private $target;

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return DirectoryRelation
	 */
	public function setId(int $id): DirectoryRelation {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType(): ?string {
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return DirectoryRelation
	 */
	public function setType(string $type): DirectoryRelation {
		$this->type = $type;
		return $this;
	}

	/**
	 * @return DirectoryObject
	 */
	public function getTarget(): ?DirectoryObject {
		return $this->target;
	}

	/**
	 * @param DirectoryObject $target
	 * @return DirectoryRelation
	 */
	public function setTarget(DirectoryObject $target): DirectoryRelation {
		$this->target = $target;
		return $this;
	}

	/**
	 * @return User
	 */
	public function getOwner(): DirectoryObject {
		return $this->owner;
	}

	/**
	 * @param DirectoryObject $owner
	 * @return DirectoryRelation
	 */
	public function setOwner(DirectoryObject $owner): DirectoryRelation {
		$this->owner = $owner;
		return $this;
	}


}