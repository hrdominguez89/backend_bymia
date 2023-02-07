<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderItemsRepository")
 * @ORM\Table("mia_order_items")
 */
class OrderItems
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderItems", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $orderId;

    /**
     * @var integer
     *
     * @ORM\Column(name="pid", type="bigint")
     */
    private $pId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100)
     */
    private $slug;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=500, nullable=true)
     */
    private $image;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;

    /**
     * @param Order $orderId
     * @param Product $product
     * @param Shopping $shopping
     */
    public function __construct(Order $orderId, Product $product)
    {
        $this->setOrderId($orderId);

        $this->setPId($product->getId());
        $this->setName($product->getName());
        $this->setSlug($product->getSlug());
        // $this->setImage($product->getImage());
        // $this->setPrice($product->calcPrice());
        $this->setTotal($this->price * $this->quantity);
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Order|null
     */
    public function getOrderId(): ?Order
    {
        return $this->orderId;
    }

    /**
     * @param Order $orderId
     * @return $this
     */
    public function setOrderId(Order $orderId): OrderItems
    {
        $this->orderId = $orderId;

        $orderId->addOrderItem($this);

        return $this;
    }

    /**
     * @return int
     */
    public function getPId(): int
    {
        return $this->pId;
    }

    /**
     * @param int $pId
     * @return $this
     */
    public function setPId(int $pId): OrderItems
    {
        $this->pId = $pId;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): OrderItems
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): OrderItems
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return $this
     */
    public function setImage(?string $image): OrderItems
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): OrderItems
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): OrderItems
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param float $total
     * @return OrderItems
     */
    public function setTotal(float $total): OrderItems
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            "id" => $this->getPId(),
            "slug" => $this->getSlug(),
            "name" => $this->getName(),
            "image" => $this->getImage(),
            "options" => [],
            "price" => $this->getPrice(),
            "quantity" => $this->getQuantity(),
            "total" => $this->getTotal(),
        ];
    }
}
