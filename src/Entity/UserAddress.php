<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserAddressRepository")
 */
class UserAddress
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $town;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $complement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userAddresses")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $for_command;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserCommands", mappedBy="user_address")
     */
    private $userCommands;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $for_billing;

    public function __construct()
    {
        $this->userCommands = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;

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

    public function getForCommand(): ?bool
    {
        return $this->for_command;
    }

    public function setForCommand(?bool $for_command): self
    {
        $this->for_command = $for_command;

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
            $userCommand->setUserAddress($this);
        }

        return $this;
    }

    public function removeUserCommand(UserCommands $userCommand): self
    {
        if ($this->userCommands->contains($userCommand)) {
            $this->userCommands->removeElement($userCommand);
            // set the owning side to null (unless already changed)
            if ($userCommand->getUserAddress() === $this) {
                $userCommand->setUserAddress(null);
            }
        }

        return $this;
    }

    public function getForBilling(): ?bool
    {
        return $this->for_billing;
    }

    public function setForBilling(?bool $for_billing): self
    {
        $this->for_billing = $for_billing;

        return $this;
    }
}
