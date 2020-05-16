<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserCommandsRepository")
 */
class UserCommands
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $validate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $command_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userCommands")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserAddress", inversedBy="userCommands")
     */
    private $user_address;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sent;

    /**
     * @ORM\Column(type="float")
     */
    private $total_amount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sent_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Promo", inversedBy="userCommands")
     */
    private $promo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValidate(): ?bool
    {
        return $this->validate;
    }

    public function setValidate(?bool $validate): self
    {
        $this->validate = $validate;

        return $this;
    }

    public function getCommandAt(): ?\DateTimeInterface
    {
        return $this->command_at;
    }

    public function setCommandAt(\DateTimeInterface $command_at): self
    {
        $this->command_at = $command_at;

        return $this;
    }

    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(int $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getProducts(): ?string
    {
        return $this->products;
    }

    public function setProducts(string $products): self
    {
        $this->products = $products;

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

    public function getUserAddress(): ?UserAddress
    {
        return $this->user_address;
    }

    public function setUserAddress(?UserAddress $user_address): self
    {
        $this->user_address = $user_address;

        return $this;
    }

    public function getSent(): ?bool
    {
        return $this->sent;
    }

    public function setSent(?bool $sent): self
    {
        $this->sent = $sent;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->total_amount;
    }

    public function setTotalAmount(float $total_amount): self
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sent_at;
    }

    public function setSentAt(?\DateTimeInterface $sent_at): self
    {
        $this->sent_at = $sent_at;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }
}
