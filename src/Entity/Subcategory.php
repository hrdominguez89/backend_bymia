<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubcategoryRepository")
 * @ORM\Table("mia_sub_category")
 * @UniqueEntity(
 *      fields={"name","category"},
 *      errorPath="name",
 *      message="La subcategoría indicada ya se encuentra registrada para este tipo de categoría."
 * )
 */
class Subcategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name",nullable=false, type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255,nullable=false)
     */
    protected $slug;


    /**
     *
     * @ORM\Column(name="id3pl", type="bigint", nullable=true)
     */
    protected $id3pl;


    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":False})
     */
    private $visible;

    /**
     * @ORM\Column(type="datetime",nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="subcategories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=CommunicationStatesBetweenPlatforms::class, inversedBy="subcategories")
     * @ORM\JoinColumn(nullable=false, options={"default":1})
     * 
     */
    private $status_sent_3pl;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     * 
     */
    private $attempts_send_3pl;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $error_message_3pl;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="subcategory")
     */
    private $products;


    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->visible = false;
        $this->attempts_send_3pl = 0;
        $this->products = new ArrayCollection();
    }

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = strtoupper($name);

        $slugify = new Slugify();
        $this->slug = $slugify->slugify($name);

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
     * @return int|null
     */
    public function getId3pl(): ?int
    {
        return $this->id3pl;
    }

    /**
     * @param int|null $id3pl
     * @return $this
     */
    public function setId3pl(?int $id3pl): self
    {
        $this->id3pl = $id3pl;

        return $this;
    }


    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "slug" => $this->getSlug(),
            "customFields" => "",
        ];
    }

    /**
     * @return array
     */
    public function asArray2(): array
    {
        return [
            "slug" => $this->getSlug(),
            "name" => $this->getName(),
            "type" => "child",
        ];
    }

    /**
     * @return string[]
     */
    public function asMenu(): array
    {
        return [
            "type" => 'link',
            "label" => $this->getName(),
            "url" => '/shop/catalog/' . $this->getSlug(),
        ];
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($category->getName().' '.$this->slug);

        return $this;
    }

    public function getStatusSent3pl(): ?CommunicationStatesBetweenPlatforms
    {
        return $this->status_sent_3pl;
    }

    public function setStatusSent3pl(?CommunicationStatesBetweenPlatforms $status_sent_3pl): self
    {
        $this->status_sent_3pl = $status_sent_3pl;

        return $this;
    }

    public function getAttemptsSend3pl(): ?int
    {
        return $this->attempts_send_3pl;
    }

    public function setAttemptsSend3pl(int $attempts_send_3pl): self
    {
        $this->attempts_send_3pl = $attempts_send_3pl;

        return $this;
    }

    public function getErrorMessage3pl(): ?string
    {
        return $this->error_message_3pl;
    }

    public function setErrorMessage3pl(?string $error_message_3pl): self
    {
        $this->error_message_3pl = $error_message_3pl;

        return $this;
    }

    public function incrementAttemptsToSendSubcategoryTo3pl()
    {
        $this->setAttemptsSend3pl($this->attempts_send_3pl + 1); //you can access your entity values directly
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setSubcategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getSubcategory() === $this) {
                $product->setSubcategory(null);
            }
        }

        return $this;
    }
}
