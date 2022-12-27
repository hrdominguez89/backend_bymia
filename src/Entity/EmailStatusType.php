<?php

namespace App\Entity;

use App\Repository\EmailStatusTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmailStatusTypeRepository::class)
 */
class EmailStatusType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=9)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=EmailQueue::class, mappedBy="email_status")
     */
    private $emailQueues;

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
            $emailQueue->setEmailStatus($this);
        }

        return $this;
    }

    public function removeEmailQueue(EmailQueue $emailQueue): self
    {
        if ($this->emailQueues->removeElement($emailQueue)) {
            // set the owning side to null (unless already changed)
            if ($emailQueue->getEmailStatus() === $this) {
                $emailQueue->setEmailStatus(null);
            }
        }

        return $this;
    }
}
