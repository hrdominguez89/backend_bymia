<?php

namespace App\Entity;

use App\Repository\SectionsHomeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SectionsHomeRepository::class)
 */
class SectionsHome
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleSection1;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes11")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category1Section1;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes21")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category2Section1;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes31")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category3Section1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleSection2;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes12")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category1Section2;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes22")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category2Section2;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes32")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category3Section2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleSection3;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes13")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category1Section3;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes23")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category2Section3;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes33")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category3Section3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleSection4;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes14")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category1Section4;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes24")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category2Section4;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sectionsHomes34")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category3Section4;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="sectionsHomes1")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tagSection1;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="sectionsHomes2")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tagSection2;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="sectionsHomes3")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tagSection3;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="sectionsHomes4")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tagSection4;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleSection1(): ?string
    {
        return $this->titleSection1;
    }

    public function setTitleSection1(string $titleSection1): self
    {
        $this->titleSection1 = $titleSection1;

        return $this;
    }

    public function getCategory1Section1(): ?Category
    {
        return $this->category1Section1;
    }

    public function setCategory1Section1(?Category $category1Section1): self
    {
        $this->category1Section1 = $category1Section1;

        return $this;
    }

    public function getCategory2Section1(): ?Category
    {
        return $this->category2Section1;
    }

    public function setCategory2Section1(?Category $category2Section1): self
    {
        $this->category2Section1 = $category2Section1;

        return $this;
    }

    public function getCategory3Section1(): ?Category
    {
        return $this->category3Section1;
    }

    public function setCategory3Section1(?Category $category3Section1): self
    {
        $this->category3Section1 = $category3Section1;

        return $this;
    }

    public function getTitleSection2(): ?string
    {
        return $this->titleSection2;
    }

    public function setTitleSection2(string $titleSection2): self
    {
        $this->titleSection2 = $titleSection2;

        return $this;
    }

    public function getCategory1Section2(): ?Category
    {
        return $this->category1Section2;
    }

    public function setCategory1Section2(?Category $category1Section2): self
    {
        $this->category1Section2 = $category1Section2;

        return $this;
    }

    public function getCategory2Section2(): ?Category
    {
        return $this->category2Section2;
    }

    public function setCategory2Section2(?Category $category2Section2): self
    {
        $this->category2Section2 = $category2Section2;

        return $this;
    }

    public function getCategory3Section2(): ?Category
    {
        return $this->category3Section2;
    }

    public function setCategory3Section2(?Category $category3Section2): self
    {
        $this->category3Section2 = $category3Section2;

        return $this;
    }

    public function getTitleSection3(): ?string
    {
        return $this->titleSection3;
    }

    public function setTitleSection3(string $titleSection3): self
    {
        $this->titleSection3 = $titleSection3;

        return $this;
    }

    public function getCategory1Section3(): ?Category
    {
        return $this->category1Section3;
    }

    public function setCategory1Section3(?Category $category1Section3): self
    {
        $this->category1Section3 = $category1Section3;

        return $this;
    }

    public function getCategory2Section3(): ?Category
    {
        return $this->category2Section3;
    }

    public function setCategory2Section3(?Category $category2Section3): self
    {
        $this->category2Section3 = $category2Section3;

        return $this;
    }

    public function getCategory3Section3(): ?Category
    {
        return $this->category3Section3;
    }

    public function setCategory3Section3(?Category $category3Section3): self
    {
        $this->category3Section3 = $category3Section3;

        return $this;
    }

    public function getTitleSection4(): ?string
    {
        return $this->titleSection4;
    }

    public function setTitleSection4(string $titleSection4): self
    {
        $this->titleSection4 = $titleSection4;

        return $this;
    }

    public function getCategory1Section4(): ?Category
    {
        return $this->category1Section4;
    }

    public function setCategory1Section4(?Category $category1Section4): self
    {
        $this->category1Section4 = $category1Section4;

        return $this;
    }

    public function getCategory2Section4(): ?Category
    {
        return $this->category2Section4;
    }

    public function setCategory2Section4(?Category $category2Section4): self
    {
        $this->category2Section4 = $category2Section4;

        return $this;
    }

    public function getCategory3Section4(): ?Category
    {
        return $this->category3Section4;
    }

    public function setCategory3Section4(?Category $category3Section4): self
    {
        $this->category3Section4 = $category3Section4;

        return $this;
    }

    public function getTagSection1(): ?Tag
    {
        return $this->tagSection1;
    }

    public function setTagSection1(?Tag $tagSection1): self
    {
        $this->tagSection1 = $tagSection1;

        return $this;
    }

    public function getTagSection2(): ?Tag
    {
        return $this->tagSection2;
    }

    public function setTagSection2(?Tag $tagSection2): self
    {
        $this->tagSection2 = $tagSection2;

        return $this;
    }

    public function getTagSection3(): ?Tag
    {
        return $this->tagSection3;
    }

    public function setTagSection3(?Tag $tagSection3): self
    {
        $this->tagSection3 = $tagSection3;

        return $this;
    }

    public function getTagSection4(): ?Tag
    {
        return $this->tagSection4;
    }

    public function setTagSection4(?Tag $tagSection4): self
    {
        $this->tagSection4 = $tagSection4;

        return $this;
    }
}
