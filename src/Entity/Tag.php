<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table("mia_tag")
 * @UniqueEntity(fields="name", message="La etiqueta indicada ya se encuentra registrada.")
 * 
 */
class Tag
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $visible;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="tag")
     */
    private $products;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $principal;

    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="tagSection1")
     */
    private $sectionsHomes1;

    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="tagSection2")
     */
    private $sectionsHomes2;

    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="tagSection3")
     */
    private $sectionsHomes3;

    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="tagSection4")
     */
    private $sectionsHomes4;

    public function __construct()
    {
        $this->visible = false;
        $this->created_at = new \DateTime();
        $this->products = new ArrayCollection();
        $this->principal = false;
        $this->sectionsHomes1 = new ArrayCollection();
        $this->sectionsHomes2 = new ArrayCollection();
        $this->sectionsHomes3 = new ArrayCollection();
        $this->sectionsHomes4 = new ArrayCollection();
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
    public function setName(string $name): Tag
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

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
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
            $product->setTag($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getTag() === $this) {
                $product->setTag(null);
            }
        }

        return $this;
    }

    public function getPrincipal(): ?bool
    {
        return $this->principal;
    }

    public function setPrincipal(bool $principal): self
    {
        $this->principal = $principal;

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes1(): Collection
    {
        return $this->sectionsHomes1;
    }

    public function addSectionsHome1(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes1->contains($sectionsHome)) {
            $this->sectionsHomes1[] = $sectionsHome;
            $sectionsHome->setTagSection1($this);
        }

        return $this;
    }

    public function removeSectionsHome1(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes1->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getTagSection1() === $this) {
                $sectionsHome->setTagSection1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes2(): Collection
    {
        return $this->sectionsHomes2;
    }

    public function addSectionsHome2(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes2->contains($sectionsHome)) {
            $this->sectionsHomes2[] = $sectionsHome;
            $sectionsHome->setTagSection2($this);
        }

        return $this;
    }

    public function removeSectionsHome2(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes2->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getTagSection2() === $this) {
                $sectionsHome->setTagSection2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes3(): Collection
    {
        return $this->sectionsHomes3;
    }

    public function addSectionsHome3(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes3->contains($sectionsHome)) {
            $this->sectionsHomes3[] = $sectionsHome;
            $sectionsHome->setTagSection3($this);
        }

        return $this;
    }

    public function removeSectionsHome3(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes3->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getTagSection3() === $this) {
                $sectionsHome->setTagSection3(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes4(): Collection
    {
        return $this->sectionsHomes4;
    }

    public function addSectionsHome(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes4->contains($sectionsHome)) {
            $this->sectionsHomes4[] = $sectionsHome;
            $sectionsHome->setTagSection4($this);
        }

        return $this;
    }

    public function removeSectionsHome4(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes4->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getTagSection4() === $this) {
                $sectionsHome->setTagSection4(null);
            }
        }

        return $this;
    }
}
