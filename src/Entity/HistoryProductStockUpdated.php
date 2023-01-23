<?php

namespace App\Entity;

use App\Repository\HistoryProductStockUpdatedRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoryProductStockUpdatedRepository::class)
 */
class HistoryProductStockUpdated
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="historyProductStockUpdateds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $onhand;

    /**
     * @ORM\Column(type="integer")
     */
    private $commited;

    /**
     * @ORM\Column(type="integer")
     */
    private $incomming;

    /**
     * @ORM\Column(type="integer")
     */
    private $available;

    /**
     * @ORM\ManyToOne(targetEntity=ActionsProductType::class, inversedBy="historyProductStockUpdateds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $updated_at;

    public function __construct()
    {
        $this->updated_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getOnhand(): ?int
    {
        return $this->onhand;
    }

    public function setOnhand(int $onhand): self
    {
        $this->onhand = $onhand;

        return $this;
    }

    public function getCommited(): ?int
    {
        return $this->commited;
    }

    public function setCommited(int $commited): self
    {
        $this->commited = $commited;

        return $this;
    }

    public function getIncomming(): ?int
    {
        return $this->incomming;
    }

    public function setIncomming(int $incomming): self
    {
        $this->incomming = $incomming;

        return $this;
    }

    public function getAvailable(): ?int
    {
        return $this->available;
    }

    public function setAvailable(int $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getAction(): ?ActionsProductType
    {
        return $this->action;
    }

    public function setAction(?ActionsProductType $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
