<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Entity\Program;

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
    public function show(Program $program): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with this id.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    #[Route('/{id<\d+>}/season/{season<\d+>}', name: 'season_show', methods: ['GET'])]
    public function showSeason(Program $program, int $season, SeasonRepository $seasonRepository): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' =>$seasonRepository->findOneBy(['id'=> $season])
        ]);

    }
}
