<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;

#[route('/program', name:"program_")]
class ProgramController extends AbstractController
{
    #[Route("/", name: 'index')]
    public function index(ProgramRepository $programRepository, CategoryRepository $categoryRepository):Response
    {
        $categories = $categoryRepository->findAll();
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs, 'categories'=>$categories
        ]);
    }

    #[Route('/{id<\d+>}', name: 'showDetail', methods: ['GET'])]
    public function show(int $id = 1, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }
}
