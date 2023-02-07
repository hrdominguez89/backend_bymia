<?php

namespace App\Entity;

use App\Repository\WarehousesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;


/**
 * @ORM\Entity(repositoryClass=WarehousesRepository::class)
 */
class Warehouses
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id3pl;

    /**
     * @ORM\Column(type="datetime",nullable=false,options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=Inventory::class, mappedBy="warehouse")
     */
    private $inventories;

    /**
     * @ORM\OneToMany(targetEntity=Orders::class, mappedBy="warehouse")
     */
    private $orders;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->products = new ArrayCollection();
        $this->inventories = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getId3pl(): ?int
    {
        return $this->id3pl;
    }

    public function setId3pl(int $id3pl): self
    {
        $this->id3pl = $id3pl;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Inventory>
     */
    public function getInventories(): Collection
    {
        return $this->inventories;
    }

    public function addInventory(Inventory $inventory): self
    {
        if (!$this->inventories->contains($inventory)) {
            $this->inventories[] = $inventory;
            $inventory->setWarehouse($this);
        }

        return $this;
    }

    public function removeInventory(Inventory $inventory): self
    {
        if ($this->inventories->removeElement($inventory)) {
            // set the owning side to null (unless already changed)
            if ($inventory->getWarehouse() === $this) {
                $inventory->setWarehouse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setWarehouse($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getWarehouse() === $this) {
                $order->setWarehouse(null);
            }
        }

        return $this;
    }
}
