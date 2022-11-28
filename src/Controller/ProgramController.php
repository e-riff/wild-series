<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[route('/program', name:"program_")]
class ProgramController extends AbstractController
{
    #[Route('/{id<\d+>}', name: 'showDetail', methods: ['GET'])]
    public function show(int $id = 2): Response
    {
        return $this->render('program/show.html.twig', [
            'id' => $id,
        ]);
    }
}
