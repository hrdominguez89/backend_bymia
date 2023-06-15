<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecificationRepository")
 * @ORM\Table("mia_specification")
 * @UniqueEntity(
 *      fields={"name","specification_type"},
 *      errorPath="name",
 *      message="La especificación indicada ya existe."
 * )
 */
class Specification
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
     * @ORM\Column(name="name",nullable=false, type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=SpecificationTypes::class, inversedBy="specifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $specification_type;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $colorHexadecimal;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="model")
     */
    private $products_model;
    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="color")
     */
    private $products_color;
    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="screen_resolution")
     */
    private $products_screen_resolution;
    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="screen_size")
     */
    private $products_screen_size;
    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="cpu")
     */
    private $products_cpu;
    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="gpu")
     */
    private $products_gpu;
    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="memory")
     */
    private $products_memory;
    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="storage")
     */
    private $products_storage;
    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="op_sys")
     */
    private $products_op_sys;
    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="conditium")
     */
    private $products_conditium;


    public function __construct()
    {
        $this->active = true;
        $this->products_model = new ArrayCollection();
        $this->products_color = new ArrayCollection();
        $this->products_screen_resolution = new ArrayCollection();
        $this->products_screen_size = new ArrayCollection();
        $this->products_cpu = new ArrayCollection();
        $this->products_gpu = new ArrayCollection();
        $this->products_memory = new ArrayCollection();
        $this->products_storage = new ArrayCollection();
        $this->products_op_sys = new ArrayCollection();
        $this->products_conditium = new ArrayCollection();
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
    public function setName(string $name): Specification
    {
        $this->name = $name;

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

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active): Specification
    {
        $this->active = $active;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function getSpecificationType(): ?SpecificationTypes
    {
        return $this->specification_type;
    }

    public function setSpecificationType(?SpecificationTypes $specification_type): self
    {
        $this->specification_type = $specification_type;

        return $this;
    }

    public function getColorHexadecimal(): ?string
    {
        return $this->colorHexadecimal;
    }

    public function setColorHexadecimal(?string $colorHexadecimal): self
    {
        $this->colorHexadecimal = $colorHexadecimal;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProductsModel(): Collection
    {
        return $this->products_model;
    }

    public function addProductModel(Product $product): self
    {
        if (!$this->products_model->contains($product)) {
            $this->products_model[] = $product;
            $product->setModel($this);
        }

        return $this;
    }

    public function removeProductModel(Product $product): self
    {
        if ($this->products_model->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getModel() === $this) {
                $product->setModel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProductsColor(): Collection
    {
        return $this->products_color;
    }

    public function addProductColor(Product $product): self
    {
        if (!$this->products_color->contains($product)) {
            $this->products_color[] = $product;
            $product->setColor($this);
        }

        return $this;
    }

    public function removeProductColor(Product $product): self
    {
        if ($this->products_color->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getColor() === $this) {
                $product->setColor(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Product>
     */
    public function getProductsScreenResolution(): Collection
    {
        return $this->products_screen_resolution;
    }

    public function addProductScreenResolution(Product $product): self
    {
        if (!$this->products_screen_resolution->contains($product)) {
            $this->products_screen_resolution[] = $product;
            $product->setScreenResolution($this);
        }

        return $this;
    }

    public function removeProductScreenResolution(Product $product): self
    {
        if ($this->products_screen_resolution->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getScreenResolution() === $this) {
                $product->setScreenResolution(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Product>
     */
    public function getProductsScreenSize(): Collection
    {
        return $this->products_screen_size;
    }

    public function addProductScreenSize(Product $product): self
    {
        if (!$this->products_screen_size->contains($product)) {
            $this->products_screen_size[] = $product;
            $product->setScreenSize($this);
        }

        return $this;
    }

    public function removeProductScreenSize(Product $product): self
    {
        if ($this->products_screen_size->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getScreenSize() === $this) {
                $product->setScreenSize(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Product>
     */
    public function getProductsCpu(): Collection
    {
        return $this->products_cpu;
    }

    public function addProductCpu(Product $product): self
    {
        if (!$this->products_cpu->contains($product)) {
            $this->products_cpu[] = $product;
            $product->setCpu($this);
        }

        return $this;
    }

    public function removeProductCpu(Product $product): self
    {
        if ($this->products_cpu->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCpu() === $this) {
                $product->setCpu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProductsGpu(): Collection
    {
        return $this->products_gpu;
    }

    public function addProductGpu(Product $product): self
    {
        if (!$this->products_gpu->contains($product)) {
            $this->products_gpu[] = $product;
            $product->setGpu($this);
        }

        return $this;
    }

    public function removeProductGpu(Product $product): self
    {
        if ($this->products_gpu->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getGpu() === $this) {
                $product->setGpu(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Product>
     */
    public function getProductsMemory(): Collection
    {
        return $this->products_memory;
    }

    public function addProductMemory(Product $product): self
    {
        if (!$this->products_memory->contains($product)) {
            $this->products_memory[] = $product;
            $product->setMemory($this);
        }

        return $this;
    }

    public function removeProductMemory(Product $product): self
    {
        if ($this->products_memory->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getMemory() === $this) {
                $product->setMemory(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, Product>
     */
    public function getProductsStorage(): Collection
    {
        return $this->products_storage;
    }

    public function addProductStorage(Product $product): self
    {
        if (!$this->products_storage->contains($product)) {
            $this->products_storage[] = $product;
            $product->setStorage($this);
        }

        return $this;
    }

    public function removeProductStorage(Product $product): self
    {
        if ($this->products_storage->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getStorage() === $this) {
                $product->setStorage(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Product>
     */
    public function getProductsOpSys(): Collection
    {
        return $this->products_op_sys;
    }

    public function addProductOpSys(Product $product): self
    {
        if (!$this->products_op_sys->contains($product)) {
            $this->products_op_sys[] = $product;
            $product->setOpSys($this);
        }

        return $this;
    }

    public function removeProductOpSys(Product $product): self
    {
        if ($this->products_op_sys->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getOpSys() === $this) {
                $product->setOpSys(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, Product>
     */
    public function getProductsConditium(): Collection
    {
        return $this->products_conditium;
    }

    public function addProductConditium(Product $product): self
    {
        if (!$this->products_conditium->contains($product)) {
            $this->products_conditium[] = $product;
            $product->setConditium($this);
        }

        return $this;
    }

    public function removeProductConditium(Product $product): self
    {
        if ($this->products_conditium->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getConditium() === $this) {
                $product->setConditium(null);
            }
        }

        return $this;
    }
}
