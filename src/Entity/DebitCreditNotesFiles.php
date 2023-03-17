<?php

namespace App\Entity;

use App\Repository\DebitCreditNotesFilesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DebitCreditNotesFilesRepository::class)
 */
class DebitCreditNotesFiles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Orders::class, inversedBy="debitCreditNotesFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $number_order;

    /**
     * @ORM\Column(type="text")
     */
    private $debit_credit_note_file;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

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

    public function getDebitCreditNoteFile(): ?string
    {
        return $this->debit_credit_note_file;
    }

    public function setDebitCreditNoteFile(string $debit_credit_note_file): self
    {
        $this->debit_credit_note_file = $debit_credit_note_file;

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
}
