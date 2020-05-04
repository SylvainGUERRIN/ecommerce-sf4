<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductImageRepository")
 * @Vich\Uploadable()
 */
class ProductImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/svg"}
     * )
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="url_image")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url_image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlImage(): ?string
    {
        return $this->url_image;
    }

    public function setUrlImage(?string $url_image): self
    {
        $this->url_image = $url_image;

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

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

//    /**
//     * @param File|UploadedFile $imageFile
//     * @return ProductImage
//     * @throws \Exception
//     */
//    public function setImageFile(?File $imageFile = null): ProductImage
//    {
//        $this->imageFile = $imageFile;
//        if(null !== $imageFile){
//            $this->updated_at = new \DateTime('now');
//        }
//        return $this;
//    }

    /**
     * @param File|UploadedFile $imageFile
     * @return ProductImage
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile = null): ProductImage
    {
        $this->imageFile = $imageFile;

        if($this->imageFile instanceof UploadedFile){
            $this->updated_at = new \DateTime('now');
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getUrlImage();
    }
}
