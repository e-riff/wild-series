<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Season;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\ProgramRepository;
use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProgramDuration;

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
    public function new(Request $request, CategoryRepository $categoryRepository, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);

        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // For example : persiste & flush the entity
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $programRepository->save($program, true);
            // And redirect to a route that display the result

            // Once the form is submitted, valid and the data inserted in database, you can define the success flash message
            $this->addFlash('success', 'The new program has been created');

            return $this->redirectToRoute("program_showDetail", ["program_slug"=>$program->getSlug()]);
        }

        // Render the form (best practice)
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
            'categories' => $categoryRepository->findAll(),
        ]);
    }


    #[Route('/{program_slug}', name: 'showDetail', methods: ['GET'])]
    #[Entity('program', options: ['mapping' => ['program_slug' => 'slug']])]
    public function show(Program $program, ProgramDuration $programDuration): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with this id.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program)
        ]);
    }

    #[Route('/{program_slug}/season/{season}}', name: 'season_show', methods: ['GET'])]
    #[Entity('program', options: ['mapping' => ['program_slug' => 'slug']])]
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' =>$season
        ]);
    }

    #[Route('/{program_slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[Entity('program', options: ['mapping' => ['program_slug' => 'slug']])]
    public function edit(Request $request, Program $program, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $programRepository->save($program, true);
            $this->addFlash('success', "La série " . $program->getTitle() . " à bien été modifiée");

            return $this->redirectToRoute('program_showDetail', ["id" => $program->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{program_slug}/season/{season}/episode/{episode_slug}', name:'episode_show',  methods: ['GET'])]
    #[Entity('program', options: ['mapping' => ['program_slug' => 'slug']])]
    #[Entity('episode', options: ['mapping' => ['episode_slug' => 'slug']])]
    public function showEpidode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode
        ]);
    }

    #[Route('/{program_slug}', name: 'delete', methods: ['POST'])]
    #[Entity('program', options: ['mapping' => ['program_slug' => 'slug']])]
    public function delete(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        $progamName = $program->getTitle();

        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $programRepository->remove($program, true);
        }
        $this->addFlash('danger', "$progamName à bien été supprimé");
        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }
}
