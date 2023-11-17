<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    const MAX_PER_PAGE_LANDING = 10;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name;

    #[ORM\Column(type: Types::STRING, length: 161)]
    private ?string $description;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $image;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $preview;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $created;

    #[ORM\ManyToOne(targetEntity: PostCategory::class, inversedBy: 'posts')]
    private ?PostCategory $category;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isActive;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(string $preview): self
    {
        $this->preview = $preview;

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

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCategory(): ?PostCategory
    {
        return $this->category;
    }

    public function setCategory(?PostCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
