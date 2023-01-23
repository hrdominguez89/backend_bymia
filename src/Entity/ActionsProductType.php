<?php

namespace App\Entity;

use App\Repository\ActionsProductTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActionsProductTypeRepository::class)
 */
class ActionsProductType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=HistoryProductStockUpdated::class, mappedBy="action")
     */
    private $historyProductStockUpdateds;

    public function __construct()
    {
        $this->historyProductStockUpdateds = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, HistoryProductStockUpdated>
     */
    public function getHistoryProductStockUpdateds(): Collection
    {
        return $this->historyProductStockUpdateds;
    }

    public function addHistoryProductStockUpdated(HistoryProductStockUpdated $historyProductStockUpdated): self
    {
        if (!$this->historyProductStockUpdateds->contains($historyProductStockUpdated)) {
            $this->historyProductStockUpdateds[] = $historyProductStockUpdated;
            $historyProductStockUpdated->setAction($this);
        }

        return $this;
    }

    public function removeHistoryProductStockUpdated(HistoryProductStockUpdated $historyProductStockUpdated): self
    {
        if ($this->historyProductStockUpdateds->removeElement($historyProductStockUpdated)) {
            // set the owning side to null (unless already changed)
            if ($historyProductStockUpdated->getAction() === $this) {
                $historyProductStockUpdated->setAction(null);
            }
        }

        return $this;
    }
}
