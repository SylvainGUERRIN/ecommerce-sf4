<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *     fields={"title"},
 *     message="Un autre article posséde déjà ce titre, merci de le modifier"
 * )
 * @Vich\Uploadable()
 */
class Post
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/svg"}
     * )
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="img_url")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img_url;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $excerpt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $post_created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $post_modified_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ref_description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getImgUrl(): ?string
    {
        return $this->img_url;
    }

    public function setImgUrl(?string $img_url): self
    {
        $this->img_url = $img_url;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(?string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getPostCreatedAt(): ?\DateTimeInterface
    {
        return $this->post_created_at;
    }

    public function setPostCreatedAt(\DateTimeInterface $post_created_at): self
    {
        $this->post_created_at = $post_created_at;

        return $this;
    }

    public function getPostModifiedAt(): ?\DateTimeInterface
    {
        return $this->post_modified_at;
    }

    public function setPostModifiedAt(?\DateTimeInterface $post_modified_at): self
    {
        $this->post_modified_at = $post_modified_at;

        return $this;
    }

    public function getRefDescription(): ?string
    {
        return $this->ref_description;
    }

    public function setRefDescription(?string $ref_description): self
    {
        $this->ref_description = $ref_description;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Post
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): Post
    {
        $this->imageFile = $imageFile;
        if($this->imageFile instanceof UploadedFile){
            $this->post_modified_at = new \DateTime('now');
        }
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
            $this->slug = $slugify->slugify($this->title);
        }
        if(!empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }
}
