<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="directory_groups")
 * @ORM\Entity
 */
class DirectoryGroup extends DirectoryObject implements \ArrayAccess, \Iterator, \Countable {
	/**
	 * @var \Iterator
	 */
	private $iterator;
    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="DirectoryObject", inversedBy="directoryGroups")
     * @ORM\JoinTable(name="directory_objects_groups_mapping")
     */
    private $members;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $groupName;

    public function __construct() {
        parent::__construct();
        $this->members = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getMembers(): Collection {
        return $this->members;
    }
	/**
	 * @return DirectoryGroup
	 */
	public function setMembers($members): DirectoryGroup {
		if ($members instanceof Collection) {
			$this->members = $members;
		} else if (is_array($members)) {
			$this->members = new ArrayCollection($members);
		} else {
			$this->members = new ArrayCollection([$members]);
		}
    	return $this;
	}

    /**
     * @return string
     */
    public function getGroupName(): ?string {
        return $this->groupName;
    }



    /**
     * @param string $groupName
     * @return DirectoryGroup
     */
    public function setGroupName(string $groupName): DirectoryGroup {
        $this->groupName = $groupName;
        return $this;
    }

	public function offsetExists($offset) {
		return $this->members->offsetExists($offset);
	}

	public function offsetGet($offset) {
		return $this->members->offsetGet($offset);
	}

	public function offsetSet($offset, $value) {
		return $this->members->offsetSet($offset, $value);
	}

	public function offsetUnset($offset) {
		return $this->members->offsetUnset($offset);
	}

	public function current() {
		return $this->iterator->current();
	}

	public function next() {
		$this->iterator->next();
	}

	public function key() {
		return $this->iterator->key();
	}

	public function valid() {
		return $this->iterator->valid();
	}

	public function rewind() {
		return $this->iterator = $this->members->getIterator();
	}

	public function count() {
		return $this->members->count();
	}

	public function getName(): string {
		return $this->groupName;
	}
}