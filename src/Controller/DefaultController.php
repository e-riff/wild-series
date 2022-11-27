<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route("/", name: 'app_index')]
    public function index()
    {
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
        ]);
    }
}