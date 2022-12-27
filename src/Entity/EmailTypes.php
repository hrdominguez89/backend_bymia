<?php

namespace App\Entity;

use App\Repository\EmailTypesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmailTypesRepository::class)
 */
class EmailTypes
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=EmailQueue::class, mappedBy="email_type")
     */
    private $emailQueues;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $template_name;

    public function __construct()
    {
        $this->emailQueues = new ArrayCollection();
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
     * @return Collection<int, EmailQueue>
     */
    public function getEmailQueues(): Collection
    {
        return $this->emailQueues;
    }

    public function addEmailQueue(EmailQueue $emailQueue): self
    {
        if (!$this->emailQueues->contains($emailQueue)) {
            $this->emailQueues[] = $emailQueue;
            $emailQueue->setEmailType($this);
        }

        return $this;
    }

    public function removeEmailQueue(EmailQueue $emailQueue): self
    {
        if ($this->emailQueues->removeElement($emailQueue)) {
            // set the owning side to null (unless already changed)
            if ($emailQueue->getEmailType() === $this) {
                $emailQueue->setEmailType(null);
            }
        }

        return $this;
    }

    public function getTemplateName(): ?string
    {
        return $this->template_name;
    }

    public function setTemplateName(string $template_name): self
    {
        $this->template_name = $template_name;

        return $this;
    }
}
