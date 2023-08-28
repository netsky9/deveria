<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\File\File;

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
    private Collection $authors;

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
        $this->authors = new ArrayCollection();
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
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->authors->removeElement($author);

        return $this;
    }

    public function getCover(): ?File
    {
        if ($this->cover === null) {
            return null;
        }

        return new File($this->cover);
    }

    public function setCover(?File $cover): self
    {
        if ($cover !== null) {
            $this->cover = $cover->getPathname();
        } else {
            $this->cover = null;
        }

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
