<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 16/7/2018
 * Time: 10:34 AM
 */

namespace App\Entity\Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * Class DirectoryObject
 * @package App\Entity\Base
 * @ORM\Table(name="directory_object")
 * @ORM\Entity()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="object_type", type="string")
 */
abstract class DirectoryObject {
    /**
     * @var int
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="DirectoryGroup", mappedBy="children")
	 */
	private $parents;

	public function __construct() {
		$this->parents = new ArrayCollection();
	}

	/**
	 * @return Collection
	 */
	public function getParents(): Collection {
		return $this->parents;
	}

	/**
	 * @return int
	 */
	public function getId(): ?int {
		return $this->id;
	}

	public function getParentsRecursive(): array {
		$rtn = [];
		foreach ($this->parents as $p) {
			/* @var \App\Entity\Base\DirectoryGroup $p */
			$rtn[] = $p;
			$this->unpackDirectoryGroup($p, $rtn);
		}
		return $rtn;
	}

	private function unpackDirectoryGroup(DirectoryGroup $group, &$rtn = null) {
		if ($rtn === null) {
			$rtn = [];
		}
		foreach ($group->parents as $p) {
			/* @var \App\Entity\Base\DirectoryGroup $p */
			if (!in_array($p, $rtn) && $p !== $this) {
				$rtn[] = $p;
				if ($p->getParents()->count() > 0) {
					$this->unpackDirectoryGroup($p, $rtn);
				}
			}
		}
	}

	abstract public function getFriendlyName(): string;
	abstract public function getFriendlyClassName(): string;
}