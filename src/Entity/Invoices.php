<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoicesRepository")
 */
class Invoices
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\Column(type="datetime")
     */
    private $invoice_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="invoices")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getInvoiceAt(): ?\DateTimeInterface
    {
        return $this->invoice_at;
    }

    public function setInvoiceAt(\DateTimeInterface $invoice_at): self
    {
        $this->invoice_at = $invoice_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
