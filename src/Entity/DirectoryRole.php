<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use App\Entity\DirectoryObject;
/**
 * @ORM\Table(name="directory_roles")
 * @ORM\Entity
 */
class DirectoryRole extends Role{
    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="DirectoryObject", mappedBy="rolesCollection")
     * @var DirectoryObject
     */
    private $directoryObjects;

    /**
     * @ORM\Column(type="string", length=128, name="role_name", unique=true)
     */
    private $roleName;

    public function __construct(string $role) {
        parent::__construct($role);
        $this->directoryObjects = new ArrayCollection();
        $this->roleName = $role;
    }

    /**
     * @return string
     */
    public function getRole(): string {
        return $this->roleName;
    }

    public function getDirectoryObjects(): Collection {
        return $this->directoryObjects;
    }
}