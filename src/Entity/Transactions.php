<?php

namespace App\Entity;

use App\Repository\TransactionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionsRepository::class)
 */
class Transactions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Orders::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $number_order;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="float")
     */
    private $tax;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=StatusTypeTransaction::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $error_message;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $session;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $session_key;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $AuthorizationCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $TxToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ResponseCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CreditcardNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $RetrivalReferenceNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $RemoteResponseCode;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberOrder(): ?Orders
    {
        return $this->number_order;
    }

    public function setNumberOrder(?Orders $number_order): self
    {
        $this->number_order = $number_order;

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

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(float $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStatus(): ?StatusTypeTransaction
    {
        return $this->status;
    }

    public function setStatus(?StatusTypeTransaction $status): self
    {
        $this->status = $status;

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

    public function getSession(): ?string
    {
        return $this->session;
    }

    public function setSession(?string $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getSessionKey(): ?string
    {
        return $this->session_key;
    }

    public function setSessionKey(?string $session_key): self
    {
        $this->session_key = $session_key;

        return $this;
    }

    public function getAuthorizationCode(): ?string
    {
        return $this->AuthorizationCode;
    }

    public function setAuthorizationCode(?string $AuthorizationCode): self
    {
        $this->AuthorizationCode = $AuthorizationCode;

        return $this;
    }

    public function getTxToken(): ?string
    {
        return $this->TxToken;
    }

    public function setTxToken(?string $TxToken): self
    {
        $this->TxToken = $TxToken;

        return $this;
    }

    public function getResponseCode(): ?string
    {
        return $this->ResponseCode;
    }

    public function setResponseCode(?string $ResponseCode): self
    {
        $this->ResponseCode = $ResponseCode;

        return $this;
    }

    public function getCreditcardNumber(): ?string
    {
        return $this->CreditcardNumber;
    }

    public function setCreditcardNumber(?string $CreditcardNumber): self
    {
        $this->CreditcardNumber = $CreditcardNumber;

        return $this;
    }

    public function getRetrivalReferenceNumber(): ?string
    {
        return $this->RetrivalReferenceNumber;
    }

    public function setRetrivalReferenceNumber(?string $RetrivalReferenceNumber): self
    {
        $this->RetrivalReferenceNumber = $RetrivalReferenceNumber;

        return $this;
    }

    public function getRemoteResponseCode(): ?string
    {
        return $this->RemoteResponseCode;
    }

    public function setRemoteResponseCode(string $RemoteResponseCode): self
    {
        $this->RemoteResponseCode = $RemoteResponseCode;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
