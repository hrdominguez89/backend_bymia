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
    public function __construct()
    {
        parent::__construct();

        $this->roles = json_encode(['ROLE_USER']);
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return array_unique(['ROLE_USER']);
    }

}
