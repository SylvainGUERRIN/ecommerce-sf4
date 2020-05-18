<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PromoRepository")
 */
class Promo
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
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $percent;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="promo")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserCommands", mappedBy="promo")
     */
    private $userCommands;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->userCommands = new ArrayCollection();
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

    public function getPercent(): ?int
    {
        return $this->percent;
    }

    public function setPercent(int $percent): self
    {
        $this->percent = $percent;

        return $this;
    }

    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
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
            $product->setPromo($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getPromo() === $this) {
                $product->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserCommands[]
     */
    public function getUserCommands(): Collection
    {
        return $this->userCommands;
    }

    public function addUserCommand(UserCommands $userCommand): self
    {
        if (!$this->userCommands->contains($userCommand)) {
            $this->userCommands[] = $userCommand;
            $userCommand->setPromo($this);
        }

        return $this;
    }

    public function removeUserCommand(UserCommands $userCommand): self
    {
        if ($this->userCommands->contains($userCommand)) {
            $this->userCommands->removeElement($userCommand);
            // set the owning side to null (unless already changed)
            if ($userCommand->getPromo() === $this) {
                $userCommand->setPromo(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }
}
