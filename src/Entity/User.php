<?php

namespace App\Entity;

use App\Entity\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table("mia_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(type="string")
     */
    protected $roles = [];

    /**
     * @ORM\ManyToOne(targetEntity=Roles::class, inversedBy="users")
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return array_unique([$this->role->getRole()]);
    }

    public function getRole(): ?Roles
    {
        return $this->role;
    }

    public function setRole(?Roles $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
