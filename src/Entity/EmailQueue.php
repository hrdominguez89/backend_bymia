<?php

namespace App\Entity;

use App\Repository\EmailQueueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmailQueueRepository::class)
 */
class EmailQueue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=EmailTypes::class, inversedBy="emailQueues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $email_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $email_to;

    /**
     * @ORM\Column(type="json", nullable=false)
     */
    private $parameters = [];

    /**
     * @ORM\Column(type="smallint", nullable=false, options={"default":0})
     */
    private $send_attempts = 0;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sent_on;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $error_message;

    /**
     * @ORM\ManyToOne(targetEntity=EmailStatusType::class, inversedBy="emailQueues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $email_status;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailType(): ?EmailTypes
    {
        return $this->email_type;
    }

    public function setEmailType(?EmailTypes $email_type): self
    {
        $this->email_type = $email_type;

        return $this;
    }

    public function getEmailTo(): ?string
    {
        return $this->email_to;
    }

    public function setEmailTo(string $email_to): self
    {
        $this->email_to = $email_to;

        return $this;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getSendAttempts(): ?int
    {
        return $this->send_attempts;
    }

    public function setSendAttempts(int $send_attempts): self
    {
        $this->send_attempts = $send_attempts;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getSentOn(): ?\DateTimeInterface
    {
        return $this->sent_on;
    }

    public function setSentOn(\DateTimeInterface $sent_on): self
    {
        $this->sent_on = $sent_on;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->error_message;
    }

    public function setErrorMessage(?string $error_message): self
    {
        $this->error_message = $error_message;

        return $this;
    }

    public function getEmailStatus(): ?EmailStatusType
    {
        return $this->email_status;
    }

    public function setEmailStatus(?EmailStatusType $email_status): self
    {
        $this->email_status = $email_status;

        return $this;
    }

    public function incrementAttempts()
    {
        $this->setSendAttempts($this->send_attempts + 1); //you can access your entity values directly
    }
}
