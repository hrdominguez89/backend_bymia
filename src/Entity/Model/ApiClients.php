<?php

namespace App\Entity\Model;

use App\Entity\ApiClientsTypesRoles;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class ApiClients implements UserInterface, \Serializable, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * 
     */
    protected $name;

    /**
     * @var string|array
     *
     * @ORM\Column(name="roles", type="string", length=255)
     */

    /**
     * @ORM\Column(type="uuid")
     */
    protected $api_client_id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $api_key;

    /**
     * @ORM\ManyToOne(targetEntity=ApiClientsTypesRoles::class, inversedBy="apiClients")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $api_client_type_role;


    // INICIA METODOS

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getApiKey(): ?string
    {
        return $this->api_key;
    }

    public function setApiKey(string $api_key): self
    {
        $this->api_key = password_hash($api_key, PASSWORD_BCRYPT);

        return $this;
    }

    
    public function getUserIdentifier()
    {
        return $this->api_client_id;
    }

    public function getApiClientId()
    {
        return $this->api_client_id;
    }

    public function setApiClientId($api_client_id): self
    {
        $this->api_client_id = $api_client_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->api_client_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function setName($name): string
    {
        return $this->name = $name;
    }

    public function getApiClientTypeRole(): ?ApiClientsTypesRoles
    {
        return $this->api_client_type_role;
    }

    public function setApiClientTypeRole(?ApiClientsTypesRoles $api_client_type_role): self
    {
        $this->api_client_type_role = $api_client_type_role;

        return $this;
    }



    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return array_unique([$this->api_client_type_role->getRole()]);
    }

    public function getRole(): ?ApiClientsTypesRoles
    {
        return $this->api_client_type_role;
    }

    public function setRole(?ApiClientsTypesRoles $api_client_type_role): self
    {
        $this->api_client_type_role = $api_client_type_role;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->api_key;
    }

    /**
     * @param string|null $api_key
     * @return $this
     */
    public function setPassword(?string $api_key): self
    {
        $this->api_key = password_hash($api_key, PASSWORD_BCRYPT);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): ?string
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        return serialize([$this->id, $this->api_client_id, $this->api_key]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($data)
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        [$this->id, $this->api_client_id, $this->api_key] = unserialize($data, ['allowed_classes' => false]);
    }
}
