<?php

namespace App\Entity;

use App\Repository\ApiClientsTypesRolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiClientsTypesRolesRepository::class)
 */
class ApiClientsTypesRoles
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
    private $role;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ApiClients::class, mappedBy="api_client_type_role")
     */
    private $apiClients;

    public function __construct()
    {
        $this->apiClients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ApiClients>
     */
    public function getApiClients(): Collection
    {
        return $this->apiClients;
    }

    public function addApiClient(ApiClients $apiClient): self
    {
        if (!$this->apiClients->contains($apiClient)) {
            $this->apiClients[] = $apiClient;
            $apiClient->setApiClientTypeRole($this);
        }

        return $this;
    }

    public function removeApiClient(ApiClients $apiClient): self
    {
        if ($this->apiClients->removeElement($apiClient)) {
            // set the owning side to null (unless already changed)
            if ($apiClient->getApiClientTypeRole() === $this) {
                $apiClient->setApiClientTypeRole(null);
            }
        }

        return $this;
    }
}
