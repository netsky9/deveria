<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SimpleCrudAuthorController extends AbstractController
{
    private AuthorRepository $authorRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param AuthorRepository $authorRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        AuthorRepository $authorRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->authorRepository = $authorRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/simple/crud/author", name="simple_crud_author")
     */
    public function index(Request $request): Response
    {
        $author = new Author();
        $authorForm = $this->createForm(AuthorType::class, $author);

        $authorForm->handleRequest($request);

        if ($authorForm->isSubmitted() && $authorForm->isValid()) {
            $this->saveAuthor($authorForm, $author);
            $this->addFlash('success', 'Author was added');

            return $this->redirectToRoute('simple_crud_author');
        }

        $authors = $this->authorRepository->getAllWithBooks();

        return $this->render('simple_crud_author/index.html.twig', [
            'authors' => $authors,
            'authorForm' => $authorForm->createView(),
        ]);
    }

    /**
     * @Route("/simple/crud/author/edit/{id}", name="simple_crud_author_edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $author = $this->authorRepository->getOneByid($id);
        if (!$author) {
            $this->addFlash('error', 'Author was not found');

            return $this->redirectToRoute('simple_crud_author');
        }
        $authorForm = $this->createForm(AuthorType::class, $author);

        $authorForm->handleRequest($request);

        if ($authorForm->isSubmitted() && $authorForm->isValid()) {
            $this->saveAuthor($authorForm, $author);
            $this->addFlash('success', 'Author was added');

            return $this->redirectToRoute('simple_crud_author');
        }

        $authors = $this->authorRepository->getAllWithBooks();

        return $this->render('simple_crud_author/edit.html.twig', [
            'authors' => $authors,
            'authorForm' => $authorForm->createView(),
            'authorId' => $id
        ]);
    }

    /**
     * @Route("/simple/crud/author/delete/{id}", name="simple_crud_author_delete")
     */
    public function delete(Request $request, int $id): Response
    {
        $author = $this->authorRepository->getOneByid($id);

        if (!$author) {
            $this->addFlash('error', 'Author was not found');
        } else {
            $this->entityManager->remove($author);
            $this->entityManager->flush();

            $this->addFlash('success', 'Author was deleted successful');
        }

        return $this->redirectToRoute('simple_crud_author');
    }

    private function saveAuthor($authorForm, Author $author)
    {
        $data = $authorForm->getData();
        $books = $data->getBooks();

        foreach ($books as $book) {
            $book->addAuthor($author);

            if (!$this->entityManager->contains($book)) {
                $this->entityManager->persist($book);
            }
        }

        $this->entityManager->persist($author);
        $this->entityManager->flush();
    }

}
