<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductReviewsRepository")
 * @ORM\Table("mia_product_reviews")
 */
class ProductReviews
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $productId;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $customerId;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @param Product $productId
     * @param Customer $customerId
     * @param Request $request
     */
    public function __construct(Product $productId, Customer $customerId, Request $request = null)
    {
        $this->productId = $productId;
        $this->customerId = $customerId;

        if($request){
            $this->setRating($request->get('review_stars', 5));
            $this->setMessage($request->get('review_text', ''));
        }

        $this->dateCreated = new \DateTime();
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Product
     */
    public function getProductId(): Product
    {
        return $this->productId;
    }

    /**
     * @param Product $productId
     * @return $this
     */
    public function setProductId(Product $productId): ProductReviews
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomerId(): Customer
    {
        return $this->customerId;
    }

    /**
     * @param Customer $customerId
     * @return $this
     */
    public function setCustomerId(Customer $customerId): ProductReviews
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param $rating
     * @return $this
     */
    public function setRating($rating): ProductReviews
    {
        $this->rating = intval($rating);

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): ProductReviews
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     * @return $this
     */
    public function setDateCreated(\DateTime $dateCreated): ProductReviews
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

}
