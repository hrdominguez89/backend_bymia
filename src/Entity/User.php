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
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

}
