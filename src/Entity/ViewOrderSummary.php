<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ViewOrderSummaryRepository", readOnly=true)
 * @ORM\Table("mia_view_orders_summary")
 */
class ViewOrderSummary
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="cant", type="integer")
     */
    private $cant;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return $this
     */
    public function setDate(string $date): ViewOrderSummary
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return int
     */
    public function getCant(): int
    {
        return $this->cant;
    }

    /**
     * @param int $cant
     * @return $this
     */
    public function setCant(int $cant): ViewOrderSummary
    {
        $this->cant = $cant;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount(float $amount): ViewOrderSummary
    {
        $this->amount = $amount;

        return $this;
    }


}
