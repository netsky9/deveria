<?php

namespace App\Command;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use DateTime;

class BookFakerCommand extends Command
{
    protected static $defaultName = 'app:book-faker';
    private EntityManagerInterface $entityManager;
    private BookRepository $bookRepository;
    private AuthorRepository $authorRepository;
    private array $books = [
        [
            'title' => 'Harry Potter and the Philosopher’s Stone',
            'cover' => '',
            'publish_at' => 2001,
            'authors' => [
                [
                    'name' => 'J.K. Rowling',
                    'description' => 'Joanne Rowling CH OBE FRSL best known by her pen name J. K. Rowling, is a British author and philanthropist. She wrote Harry Pott...',
                ]
            ]
        ],
        [
            'title' => 'Harry Potter and the Chamber of Secrets',
            'cover' => '',
            'publish_at' => 2003,
            'authors' => [
                [
                    'name' => 'J.K. Rowling',
                    'description' => 'Joanne Rowling CH OBE FRSL best known by her pen name J. K. Rowling, is a British author and philanthropist. She wrote Harry Pott...',
                ]
            ]
        ],
        [
            'title' => 'The Green Mile',
            'cover' => '',
            'publish_at' => 1996,
            'authors' => [
                [
                    'name' => 'Stephen King',
                    'description' => '',
                ]
            ]
        ],
        [
            'title' => 'War and Peace',
            'cover' => '',
            'publish_at' => 1869,
            'authors' => [
                [
                    'name' => 'Leo Tolstoy',
                    'description' => '',
                ]
            ]
        ],
        [
            'title' => 'The Lord of the Rings',
            'cover' => '',
            'publish_at' => 1954,
            'authors' => [
                [
                    'name' => 'J.R.R. Tolkien',
                    'description' => '',
                ]
            ]
        ],
        [
            'title' => 'Hamlet',
            'cover' => "",
            'publish_at' => 1603,
            'authors' => [
                [
                    'name' => 'William Shakespeare',
                    'description' => '',
                ]
            ]
        ],
    ];

    public function __construct(
        EntityManagerInterface $entityManager,
        BookRepository $bookRepository,
        AuthorRepository $authorRepository)
    {
        $this->entityManager = $entityManager;
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->clearBooks();

        foreach ($this->books as $book) {
            $bookEntity = new Book();
            $bookEntity->setTitle($book['title']);
//            $bookEntity->setCover($book['cover']);
            $bookEntity->setPublishAt($book['publish_at']);
            $this->entityManager->persist($bookEntity);
            $this->entityManager->flush();

            foreach ($book['authors'] as $author) {
                $authorEntity = $this->authorRepository->findOneBy(['name' => $author['name']]);

                if (!$authorEntity) {
                    $authorEntity = new Author();
                    $authorEntity->setName($author['name']);
                    $authorEntity->setDescription($author['description']);
                }

                $authorEntity->addBook($bookEntity);
                $this->entityManager->persist($authorEntity);
                $this->entityManager->flush();
            }
        }

        $io->success('Books was added!');

        return 0;
    }

    private function clearBooks()
    {
        $authors = $this->authorRepository->findAll();

        foreach ($authors as $author) {
            $this->entityManager->remove($author);
        }

        $books = $this->bookRepository->findAll();

        foreach ($books as $book) {
            $this->entityManager->remove($book);
        }
    }
}
