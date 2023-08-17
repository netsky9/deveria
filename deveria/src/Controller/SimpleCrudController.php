<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SimpleCrudController extends AbstractController
{
    /**
     * @Route("/simple/crud", name="app_simple_crud")
     */
    public function index(): Response
    {
        return $this->render('simple_crud/index.html.twig', [
            'controller_name' => 'SimpleCrudController',
        ]);
    }
}
