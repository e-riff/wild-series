<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Season;
use App\Repository\ProgramRepository;
use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/new', name: 'new')]
    public function new(Request $request, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        // Create category and the form, linked with $category
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);

        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // For example : persiste & flush the entity
            $programRepository->save($program, true);
            // And redirect to a route that display the result

            // Once the form is submitted, valid and the data inserted in database, you can define the success flash message
            $this->addFlash('success', 'The new program has been created');

            return $this->redirectToRoute("program_showDetail", ["id"=>$program->getId()]);
        }

        // Render the form (best practice)
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
            'categories' => $categoryRepository->findAll(),
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

    #[Route('/{program<\d+>}/season/{season<\d+>}', name: 'season_show', methods: ['GET'])]
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' =>$season
        ]);
    }

    #[Route('/{program}/season/{season}/episode/{episode}', name:'episode_show',  methods: ['GET'])]
    public function showEpidode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode
        ]);
    }
}
