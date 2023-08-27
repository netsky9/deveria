<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorType;
use App\Form\BookFilterType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Service\BookService;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SimpleCrudBookController extends AbstractController
{
    private BookRepository $bookRepository;
    private EntityManagerInterface $entityManager;
    private BookService $bookService;
    private FileService $fileService;
    private Filesystem $filesystem;

    /**
     * @param BookRepository $bookRepository
     * @param EntityManagerInterface $entityManager
     * @param BookService $bookService
     * @param FileService $fileService
     * @param Filesystem $filesystem
     */
    public function __construct(
        BookRepository $bookRepository,
        EntityManagerInterface $entityManager,
        BookService $bookService,
        FileService $fileService,
        Filesystem $filesystem
    ) {
        $this->bookRepository = $bookRepository;
        $this->entityManager = $entityManager;
        $this->bookService = $bookService;
        $this->fileService = $fileService;
        $this->filesystem = $filesystem;
    }

    /**
     * @Route("/simple/crud/book", name="simple_crud_book")
     */
    public function index(Request $request): Response
    {
        $book = new Book();
        $bookForm = $this->createForm(BookType::class, $book);
        $bookForm->handleRequest($request);

        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            $this->saveBook($bookForm, $book);
            $this->addFlash('success', 'Book was added');

            return $this->redirectToRoute('simple_crud_book');
        }

        $bookFilterForm = $this->createForm(BookFilterType::class);
        $bookFilterForm->handleRequest($request);

        if ($bookFilterForm->isSubmitted() && $bookFilterForm->isValid()) {
            $books = $this->bookRepository->getAllWithAuthors($bookFilterForm);
        } else {
            $books = $this->bookRepository->getAllWithAuthors();
        }

        $books = $this->bookService->prepareImageLinks($books);

        return $this->render('simple_crud_book/index.html.twig', [
            'books' => $books,
            'bookForm' => $bookForm->createView(),
            'bookFilterForm' => $bookFilterForm->createView(),
        ]);
    }

    /**
     * @Route("/simple/crud/book/edit/{id}", name="simple_crud_book_edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $book = $this->bookRepository->getOneByid($id);

        if (!$book) {
            $this->addFlash('error', 'Book was not found');

            return $this->redirectToRoute('simple_crud_book');
        }

        $bookForm = $this->createForm(BookType::class, $book);
        $bookForm->handleRequest($request);

        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            $this->saveBook($bookForm, $book);
            $this->addFlash('success', 'Book was added');

            return $this->redirectToRoute('simple_crud_book');
        }

        $books = $this->bookService->prepareImageLinks(
            $this->bookRepository->getAllWithAuthors()
        );

        return $this->render('simple_crud_book/edit.html.twig', [
            'books' => $books,
            'bookForm' => $bookForm->createView(),
            'bookId' => $id
        ]);
    }

    /**
     * @Route("/simple/crud/book/delete/{id}", name="simple_crud_book_delete")
     */
    public function delete(Request $request, int $id): Response
    {
        $book = $this->bookRepository->getOneByid($id);

        if (!$book) {
            $this->addFlash('error', 'Book was not found');
        } else {
            if (!empty($book->getCover())) {
                $this->filesystem->remove($book->getCover());
            }

            $this->entityManager->remove($book);
            $this->entityManager->flush();

            $this->addFlash('success', 'Book was deleted successful');
        }

        return $this->redirectToRoute('simple_crud_book');
    }

    private function saveBook($bookForm, Book $book): void
    {
        $data = $bookForm->getData();
        $authors = $data->getAuthor();

        $imageFile = $bookForm->get('cover')->getData();

        if ($imageFile) {
            $newFilename = $this->fileService->getFileName($imageFile);

            try {
                $imageFile->move(
                    $this->getParameter('root_public_path') .
                    $this->getParameter('book_covers_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                $this->addFlash('error', 'Cover was not added because ' . $e->getMessage());

                $this->redirectToRoute('simple_crud_book');
                return;
            }

            $book->setCover(new File($this->getParameter('root_public_path') . $this->getParameter('book_covers_directory') . '/' . $newFilename));
        }

        foreach ($authors as $author) {
            $author->addBook($book);

            if (!$this->entityManager->contains($author)) {
                $this->entityManager->persist($author);
            }
        }

        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }
}
