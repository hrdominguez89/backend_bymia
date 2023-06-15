<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table("mia_category")
 * 
 * @UniqueEntity(fields="name", message="La categoría indicada ya se encuentra registrada.")
 * @UniqueEntity(fields="nomenclature", message="La nomenclatura indicada ya se encuentra registrada, por favor intente con otra.")
 * 
 */
class Category
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
     * @ORM\Column(name="name",nullable=false, type="string", length=255, unique=true)
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
     * @var string|null
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="category")
     */
    private $products;

    /**
     * @ORM\Column(name="description_es", type="text", nullable=true)
     */
    private $descriptionEs;

    /**
     * @ORM\Column(type="string", length=3, nullable=true, unique=true)
     */
    private $nomenclature;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;


    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $visible = false;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $principal = false;

    /**
     * @ORM\Column(name="description_en",type="text",nullable=true)
     */
    private $descriptionEn;

    /**
     * @ORM\ManyToOne(targetEntity=CommunicationStatesBetweenPlatforms::class, inversedBy="categories")
     * @ORM\JoinColumn(nullable=false, options={"default":1})
     */
    private $status_sent_3pl;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     */
    private $attempts_send_3pl;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $error_message_3pl;

    /**
     * @ORM\OneToMany(targetEntity=Subcategory::class, mappedBy="category")
     */
    private $subcategories;

    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category1Section1")
     */
    private $sectionsHomes11;

    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category2Section1")
     */
    private $sectionsHomes21;

    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category3Section1")
     */
    private $sectionsHomes31;

    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category1Section2")
     */
    private $sectionsHomes12;

    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category2Section2")
     */
    private $sectionsHomes22;

    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category3Section2")
     */
    private $sectionsHomes32;


    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category1Section3")
     */
    private $sectionsHomes13;


    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category2Section3")
     */
    private $sectionsHomes23;


    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category3Section3")
     */
    private $sectionsHomes33;

    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category1Section4")
     */
    private $sectionsHomes14;


    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category2Section4")
     */
    private $sectionsHomes24;


    /**
     * @ORM\OneToMany(targetEntity=SectionsHome::class, mappedBy="category3Section4")
     */
    private $sectionsHomes34;



    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->visible = false;
        $this->principal = false;
        $this->attempts_send_3pl = 0;
        $this->subcategories = new ArrayCollection();
        $this->sectionsHomes11 = new ArrayCollection();
        $this->sectionsHomes12 = new ArrayCollection();
        $this->sectionsHomes13 = new ArrayCollection();
        $this->sectionsHomes21 = new ArrayCollection();
        $this->sectionsHomes22 = new ArrayCollection();
        $this->sectionsHomes23 = new ArrayCollection();
        $this->sectionsHomes31 = new ArrayCollection();
        $this->sectionsHomes32 = new ArrayCollection();
        $this->sectionsHomes33 = new ArrayCollection();
        $this->sectionsHomes14 = new ArrayCollection();
        $this->sectionsHomes24 = new ArrayCollection();
        $this->sectionsHomes34 = new ArrayCollection();
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
    public function setImage(?string $image): self
    {
        $this->image = $image;

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
            "image" => $this->getImage(),
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
            "category" => [
                "id" => $this->getId(),
                "name" => $this->getName(),
                "slug" => $this->getSlug(),
                "image" => $this->getImage(),
                "customFields" => [],
                "parents" => null,
                "children" => null,
            ],
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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    public function getDescriptionEs(): ?string
    {
        return $this->descriptionEs;
    }

    public function setDescriptionEs(?string $descriptionEs): self
    {
        $this->descriptionEs = $descriptionEs;

        return $this;
    }

    public function getNomenclature(): ?string
    {
        return strtoupper($this->nomenclature);
    }

    public function setNomenclature(?string $nomenclature): self
    {
        $this->nomenclature = strtoupper($nomenclature);

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

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    public function setDescriptionEn(string $descriptionEn): self
    {
        $this->descriptionEn = $descriptionEn;

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

    public function incrementAttemptsToSendCategoryTo3pl()
    {
        $this->setAttemptsSend3pl($this->attempts_send_3pl + 1); //you can access your entity values directly
    }

    /**
     * @return Collection<int, Subcategory>
     */
    public function getSubcategories(): Collection
    {
        return $this->subcategories;
    }

    public function addSubcategory(Subcategory $subcategory): self
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories[] = $subcategory;
            $subcategory->setCategory($this);
        }

        return $this;
    }

    public function removeSubcategory(Subcategory $subcategory): self
    {
        if ($this->subcategories->removeElement($subcategory)) {
            // set the owning side to null (unless already changed)
            if ($subcategory->getCategory() === $this) {
                $subcategory->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes11(): Collection
    {
        return $this->sectionsHomes11;
    }

    public function addSectionsHome11(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes11->contains($sectionsHome)) {
            $this->sectionsHomes11[] = $sectionsHome;
            $sectionsHome->setCategory1Section1($this);
        }

        return $this;
    }

    public function removeSectionsHome11(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes11->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory1Section1() === $this) {
                $sectionsHome->setCategory1Section1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes21(): Collection
    {
        return $this->sectionsHomes21;
    }

    public function addSectionsHome21(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes21->contains($sectionsHome)) {
            $this->sectionsHomes21[] = $sectionsHome;
            $sectionsHome->setCategory2Section1($this);
        }

        return $this;
    }

    public function removeSectionsHome21(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes21->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory2Section1() === $this) {
                $sectionsHome->setCategory2Section1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes31(): Collection
    {
        return $this->sectionsHomes31;
    }

    public function addSectionsHome31(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes31->contains($sectionsHome)) {
            $this->sectionsHomes31[] = $sectionsHome;
            $sectionsHome->setCategory3Section1($this);
        }

        return $this;
    }

    public function removeSectionsHome31(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes31->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory3Section1() === $this) {
                $sectionsHome->setCategory3Section1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes12(): Collection
    {
        return $this->sectionsHomes12;
    }

    public function addSectionsHome12(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes12->contains($sectionsHome)) {
            $this->sectionsHomes12[] = $sectionsHome;
            $sectionsHome->setCategory1Section3($this);
        }

        return $this;
    }

    public function removeSectionsHome12(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes12->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory1Section2() === $this) {
                $sectionsHome->setCategory1Section2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes22(): Collection
    {
        return $this->sectionsHomes22;
    }

    public function addSectionsHome22(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes22->contains($sectionsHome)) {
            $this->sectionsHomes22[] = $sectionsHome;
            $sectionsHome->setCategory2Section2($this);
        }

        return $this;
    }

    public function removeSectionsHome22(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes22->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory2Section2() === $this) {
                $sectionsHome->setCategory2Section2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes32(): Collection
    {
        return $this->sectionsHomes32;
    }

    public function addSectionsHome32(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes32->contains($sectionsHome)) {
            $this->sectionsHomes32[] = $sectionsHome;
            $sectionsHome->setCategory3Section2($this);
        }

        return $this;
    }

    public function removeSectionsHome32(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes32->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory3Section2() === $this) {
                $sectionsHome->setCategory3Section2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes13(): Collection
    {
        return $this->sectionsHomes13;
    }

    public function addSectionsHome13(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes13->contains($sectionsHome)) {
            $this->sectionsHomes13[] = $sectionsHome;
            $sectionsHome->setCategory1Section3($this);
        }

        return $this;
    }

    public function removeSectionsHome13(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes13->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory1Section3() === $this) {
                $sectionsHome->setCategory1Section3(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes23(): Collection
    {
        return $this->sectionsHomes23;
    }

    public function addSectionsHome23(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes23->contains($sectionsHome)) {
            $this->sectionsHomes23[] = $sectionsHome;
            $sectionsHome->setCategory2Section3($this);
        }

        return $this;
    }

    public function removeSectionsHome23(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes23->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory2Section3() === $this) {
                $sectionsHome->setCategory2Section3(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes33(): Collection
    {
        return $this->sectionsHomes33;
    }

    public function addSectionsHome33(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes33->contains($sectionsHome)) {
            $this->sectionsHomes33[] = $sectionsHome;
            $sectionsHome->setCategory3Section3($this);
        }

        return $this;
    }

    public function removeSectionsHome33(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes33->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory3Section3() === $this) {
                $sectionsHome->setCategory3Section3(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes14(): Collection
    {
        return $this->sectionsHomes14;
    }

    public function addSectionsHome14(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes14->contains($sectionsHome)) {
            $this->sectionsHomes14[] = $sectionsHome;
            $sectionsHome->setCategory1Section4($this);
        }

        return $this;
    }

    public function removeSectionsHome14(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes14->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory1Section4() === $this) {
                $sectionsHome->setCategory1Section4(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes24(): Collection
    {
        return $this->sectionsHomes24;
    }

    public function addSectionsHome24(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes24->contains($sectionsHome)) {
            $this->sectionsHomes24[] = $sectionsHome;
            $sectionsHome->setCategory2Section4($this);
        }

        return $this;
    }

    public function removeSectionsHome24(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes24->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory2Section4() === $this) {
                $sectionsHome->setCategory2Section4(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionsHome>
     */
    public function getSectionsHomes34(): Collection
    {
        return $this->sectionsHomes34;
    }

    public function addSectionsHome34(SectionsHome $sectionsHome): self
    {
        if (!$this->sectionsHomes34->contains($sectionsHome)) {
            $this->sectionsHomes34[] = $sectionsHome;
            $sectionsHome->setCategory3Section4($this);
        }

        return $this;
    }

    public function removeSectionsHome34(SectionsHome $sectionsHome): self
    {
        if ($this->sectionsHomes34->removeElement($sectionsHome)) {
            // set the owning side to null (unless already changed)
            if ($sectionsHome->getCategory3Section4() === $this) {
                $sectionsHome->setCategory3Section4(null);
            }
        }

        return $this;
    }
}
