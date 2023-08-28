<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\Collection;

class QueryService
{
    private AuthorRepository $authorRepository;
    private BookRepository $bookRepository;

    /**
     * @param AuthorRepository $authorRepository
     * @param BookRepository $bookRepository
     */
    public function __construct(AuthorRepository $authorRepository, BookRepository $bookRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
    }

    public function findAuthor(int $authorId): ?Author
    {
        return $this->authorRepository->find($authorId);
    }

    public function findAllAuthors(): array
    {
        return $this->authorRepository->findAll();
    }

    public function findAllBooks(): array
    {
        return $this->bookRepository->findAll();
    }

    public function findBookById(int $bookId): ?Book
    {
        return $this->bookRepository->find($bookId);
    }
}