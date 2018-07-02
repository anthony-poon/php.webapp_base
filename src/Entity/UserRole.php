<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
/**
 * @ORM\Table(name="user_roles")
 * @ORM\Entity
 */
class UserRole extends Role{
    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="user_roles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=128, unique=true, nullable=True, name="role_name")
     */
    private $role;

    public function __construct(string $role) {
        parent::__construct($role);
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getRole(): string {
        return $this->role;
    }

    /**
     * @return User
     */
    public function getUser(): User {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserRole
     */
    public function setUser(User $user): UserRole {
        $this->user = $user;
        return $this;
    }
}