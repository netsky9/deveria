<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Error\Error;

class MutationService
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createAuthor(array $authorDetails): Author
    {
        $author = new Author();

        $author->setName($authorDetails['name']);

        if (isset($authorDetails['description'])) {
            $author->setDescription($authorDetails['description']);
        }

        $this->entityManager->persist($author);
        $this->entityManager->flush();

        return $author;
    }

    public function createBook(array $bookDetails): Book
    {
        $book = new Book();

        $book->setTitle($bookDetails['title']);

        if (isset($bookDetails['cover'])) {
            $book->setCover($bookDetails['cover']);
        }

        $book->setPublishAt($bookDetails['publishAt']);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }

    public function updateBook(int $bookId, array $bookDetails): Book
    {
        $book = $this->entityManager->getRepository(Book::class)->find($bookId);

        if (!$book) {
            throw new \Exception("Could not find book for specified ID");
        }

        $book->setTitle($bookDetails['title']);

        if (isset($bookDetails['publishAt'])) {
            $book->setPublishAt($bookDetails['publishAt']);
        }

        if (isset($bookDetails['cover'])) {
            $book->setCover($bookDetails['cover']);
        }

        $book->setPublishAt($bookDetails['publishAt']);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }

    public function updateAuthor(int $authorId, array $authorDetails): Author
    {
        $author = $this->entityManager->getRepository(Author::class)->find($authorId);

        if (!$author) {
            throw new \Exception("Could not find author for specified ID");
        }

        $author->setName($authorDetails['name']);

        if (isset($authorDetails['description'])) {
            $author->setDescription($authorDetails['description']);
        }

        $this->entityManager->persist($author);
        $this->entityManager->flush();

        return $author;
    }

    public function deleteAuthor(int $authorId): int
    {
        $author = $this->entityManager->getRepository(Author::class)->find($authorId);

        $this->entityManager->remove($author);
        $this->entityManager->flush();

        return $authorId;
    }

    public function deleteBook(int $bookId): int
    {
        $book = $this->entityManager->getRepository(Book::class)->find($bookId);

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return $bookId;
    }
}