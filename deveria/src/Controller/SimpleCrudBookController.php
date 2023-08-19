<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SimpleCrudBookController extends AbstractController
{
    private BookRepository $bookRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param BookRepository $bookRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        BookRepository $bookRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->bookRepository = $bookRepository;
        $this->entityManager = $entityManager;
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

        $books = $this->bookRepository->getAllWithAuthors();

        return $this->render('simple_crud_book/index.html.twig', [
            'books' => $books,
            'bookForm' => $bookForm->createView(),
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

        $books = $this->bookRepository->getAllWithAuthors();

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
            $this->entityManager->remove($book);
            $this->entityManager->flush();

            $this->addFlash('success', 'Book was deleted successful');
        }

        return $this->redirectToRoute('simple_crud_book');
    }

    private function saveBook($bookForm, Book $book)
    {
        $data = $bookForm->getData();
//        $books = $data->getBooks();
//
//        foreach ($books as $book) {
//            $book->addAuthor($book);
//
//            if (!$this->entityManager->contains($book)) {
//                $this->entityManager->persist($book);
//            }
//        }
//
//        $this->entityManager->persist($book);
//        $this->entityManager->flush();
    }

}
