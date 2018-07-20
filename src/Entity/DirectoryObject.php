<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 16/7/2018
 * Time: 10:34 AM
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DirectoryObject
 * @package App\Entity
 * @ORM\Table(name="directory_objects")
 * @ORM\Entity()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="object_type", type="string")
 * @ORM\MappedSuperclass()
 */
class DirectoryObject {
    /**
     * @var int
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="DirectoryRelation", mappedBy="owner", cascade={"persist"})
     */
    private $relations;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="DirectoryRelation", mappedBy="target", cascade={"persist"})
     */
    private $inverseRelations;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="DirectoryGroup", mappedBy="members")
     */
    private $directoryGroups;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="DirectoryRole", inversedBy="directoryObjects", cascade={"persist", "remove"})
	 * @ORM\JoinTable("directory_objects_roles_mapping")
	 */
	private $rolesCollection;

    public function __construct() {
        $this->relations = new ArrayCollection();
        $this->inverseRelations = new ArrayCollection();
        $this->directoryGroups = new ArrayCollection();
        $this->rolesCollection = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getRelations(): Collection {
        return $this->relations;
    }

    /**
     * @return Collection
     */
    public function getInverseRelations(): Collection {
        return $this->inverseRelations;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    public function getEffectiveRelation(string $relationType, bool $inverse = false) {
        $arr = [];
		if (!$inverse) {
			$relations = $this->relations->filter(function(DirectoryRelation $r) use ($relationType){
				return $r->getType() === $relationType;
			});
		} else {
			$relations = $this->inverseRelations->filter(function(DirectoryRelation $r) use ($relationType){
				return $r->getType() === $relationType;
			});
		}
		foreach ($relations->toArray() as $r) {
			/* @var DirectoryRelation $r */
			if (!$inverse) {
				$obj = $r->getTarget();
			} else {
				$obj = $r->getOwner();
			}
			if ($obj instanceof DirectoryGroup) {
				$arr = array_merge($arr, $this->unpackDirectoryGroup($obj));
			} else {
				$arr[] = $obj;
			}
		}
        return $arr;
    }

    private function unpackDirectoryGroup(DirectoryGroup $group): array {
        $rtn = [];
        foreach ($group->getMembers()->toArray() as $member) {
            if ($member instanceof DirectoryGroup) {
                /* @var \App\Entity\DirectoryGroup $member */
                $unpacked = $this->unpackDirectoryGroup($member);
                $rtn = array_merge($rtn, $unpacked);
            } else {
                /* @var \App\Entity\DirectoryObject $member */
                $rtn[] = $member;
            }
        }
        return $rtn;
    }

    public function getName(): string {
    	return get_class($this). " #".$this->id;
	}

	/**
	 * @return Collection
	 */
	public function getRolesCollection(): Collection {
		return $this->rolesCollection;
	}
}