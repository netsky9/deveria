<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)(repositoryClass=BookRepository::class)
 */
class Book extends BaseEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="books")
     */
    private Collection $author;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private ?string $cover;

    /**
     * @ORM\Column(type="integer")
     */
    private int $publishAt;

    public function __construct()
    {
        $this->author = new ArrayCollection();
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

    /**
     * @return Collection<int, Author>
     */
    public function getAuthor(): Collection
    {
        return $this->author;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->author->contains($author)) {
            $this->author[] = $author;
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->author->removeElement($author);

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getPublishAt(): ?int
    {
        return $this->publishAt;
    }

    public function setPublishAt(int $publishAt): self
    {
        $this->publishAt = $publishAt;

        return $this;
    }

    public function __toString() {
        return $this->title;
    }
}
