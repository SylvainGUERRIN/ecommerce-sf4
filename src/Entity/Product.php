<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Un autre produit posséde déjà ce titre, merci de le modifier car il doit être unique"
 * )
 *
 */
class Product
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dispo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ProductImage", cascade={"persist", "remove"})
     */
    private $product_image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductImages", mappedBy="product")
     */
    private $product_images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tva", inversedBy="products")
     */
    private $tva;

    public function __construct()
    {
        $this->product_images = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDispo(): ?bool
    {
        return $this->dispo;
    }

    public function setDispo(bool $dispo): self
    {
        $this->dispo = $dispo;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getProductImage(): ?ProductImage
    {
        return $this->product_image;
    }

    public function setProductImage(?ProductImage $product_image): self
    {
        $this->product_image = $product_image;

        return $this;
    }

    /**
     * @return Collection|ProductImages[]
     */
    public function getProductImages(): Collection
    {
        return $this->product_images;
    }

    public function addProductImage(ProductImages $productImage): self
    {
        if (!$this->product_images->contains($productImage)) {
            $this->product_images[] = $productImage;
            $productImage->setProduct($this);
        }

        return $this;
    }

    public function removeProductImage(ProductImages $productImage): self
    {
        if ($this->product_images->contains($productImage)) {
            $this->product_images->removeElement($productImage);
            // set the owning side to null (unless already changed)
            if ($productImage->getProduct() === $this) {
                $productImage->setProduct(null);
            }
        }

        return $this;
    }

    public function getTva(): ?Tva
    {
        return $this->tva;
    }

    public function setTva(?Tva $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Permet d'initialiser un slug
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     *
     * @return void
     */
    public function initializeSlug(): void
    {
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->name);
        }
        if(!empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->name);
        }
    }
}
