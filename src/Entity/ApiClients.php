<?php

namespace App\Entity;

use App\Repository\ApiClientsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiClientsRepository::class)
 */
class ApiClients
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="uuid")
     */
    private $api_client_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $api_key;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=ApiClientsTypesRoles::class, inversedBy="apiClients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $api_client_type_role;

    /**
     * @ORM\Column(type="boolean", options={"default":TRUE})
     */
    private $status = TRUE;

    /**
     * @ORM\Column(type="boolean", options={"default":FALSE})
     */
    private $deleted = FALSE;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getApiKey(): ?string
    {
        return $this->api_key;
    }

    public function setApiKey(string $api_key): self
    {
        $this->api_key = password_hash($api_key, PASSWORD_BCRYPT);

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
