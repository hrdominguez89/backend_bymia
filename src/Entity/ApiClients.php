<?php

namespace App\Entity;

use App\Entity\Model\ApiClients as BaseApiClients;
use App\Repository\ApiClientsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiClientsRepository::class)
 */
class ApiClients extends BaseApiClients
{
    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean", options={"default":TRUE})
     */
    private $status = TRUE;

    /**
     * @ORM\Column(type="boolean", options={"default":FALSE})
     */
    private $deleted = FALSE;

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

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
