<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pass;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $token_created_at;

    /**
     * @ORM\Column(type="json")
     */
    private $role = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserCommands", mappedBy="user")
     */
    private $userCommands;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserAddress", mappedBy="user")
     */
    private $userAddresses;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\LostCart", mappedBy="user", cascade={"persist", "remove"})
     */
    private $lostCart;

    public function __construct()
    {
        $this->userCommands = new ArrayCollection();
        $this->userAddresses = new ArrayCollection();
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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function setPass(string $pass): self
    {
        $this->pass = $pass;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenCreatedAt(): ?\DateTimeInterface
    {
        return $this->token_created_at;
    }

    public function setTokenCreatedAt(?\DateTimeInterface $token_created_at): self
    {
        $this->token_created_at = $token_created_at;

        return $this;
    }

    public function getRole(): ?array
    {
        return $this->role;
    }

    public function setRole(array $role): self
    {
        $this->role = $role;

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
            $userCommand->setUser($this);
        }

        return $this;
    }

    public function removeUserCommand(UserCommands $userCommand): self
    {
        if ($this->userCommands->contains($userCommand)) {
            $this->userCommands->removeElement($userCommand);
            // set the owning side to null (unless already changed)
            if ($userCommand->getUser() === $this) {
                $userCommand->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserAddress[]
     */
    public function getUserAddresses(): Collection
    {
        return $this->userAddresses;
    }

    public function addUserAddress(UserAddress $userAddress): self
    {
        if (!$this->userAddresses->contains($userAddress)) {
            $this->userAddresses[] = $userAddress;
            $userAddress->setUser($this);
        }

        return $this;
    }

    public function removeUserAddress(UserAddress $userAddress): self
    {
        if ($this->userAddresses->contains($userAddress)) {
            $this->userAddresses->removeElement($userAddress);
            // set the owning side to null (unless already changed)
            if ($userAddress->getUser() === $this) {
                $userAddress->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function serialize():string
    {
        return serialize([$this->id, $this->firstname, $this->lastname, $this->mail, $this->pass]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->firstname, $this->lastname, $this->mail, $this->pass] = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getRoles(): ?array
    {
        return $this->role;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->pass;
    }

    /**
     * @inheritDoc
     * @return string|void|null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string|void
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->mail;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->firstname;
    }

    public function getLostCart(): ?LostCart
    {
        return $this->lostCart;
    }

    public function setLostCart(?LostCart $lostCart): self
    {
        $this->lostCart = $lostCart;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $lostCart ? null : $this;
        if ($lostCart->getUser() !== $newUser) {
            $lostCart->setUser($newUser);
        }

        return $this;
    }
}
