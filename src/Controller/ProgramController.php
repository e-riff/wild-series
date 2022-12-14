<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Season;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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

#[route('/program', name: "program_")]
class ProgramController extends AbstractController
{
    #[Route("/", name: 'index')]
    public function index(ProgramRepository $programRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs, 'categories' => $categories
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, MailerInterface $mailer, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?

        if ($form->isSubmitted() && $form->isValid()) {

            //Slugging
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);

            //Saving
            $programRepository->save($program, true);

            //Sending notification
            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));
            $mailer->send($email);

            // flash message
            $this->addFlash('success', 'The new program has been created');

            return $this->redirectToRoute("program_showDetail", ["program_slug" => $program->getSlug()]);
        }

        // Render the form (best practice)
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{program_slug}', name: 'showDetail', methods: ['GET', 'POST'])]
    #[Entity('program', options: ['mapping' => ['program_slug' => 'slug']])]
    public function show(Request $request, Program $program, ProgramDuration $programDuration, CommentRepository $commentRepository): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with this id.'
            );
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        // Get data from HTTP request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Slugging
            $comment->setProgram($program);
            $comment->setAuthor($this->getUser());
            $comment->setCreatedAt(new \DateTime("now"));
            //Saving
            $commentRepository->save($comment, true);

            // flash message
            $this->addFlash('success', 'New comment has been created');

            return $this->redirectToRoute("program_showDetail", [
                "program_slug" => $program->getSlug()
            ]);
        }

        return $this->renderForm('program/show.html.twig', [
            'form' => $form,
            'program' => $program,
            'programDuration' => $programDuration->calculate($program)
        ]);

    }




    #[Route('/{program_slug}/season/{season_number}', name: 'season_show', methods: ['GET'])]
    #[Entity('program', options: ['mapping' => ['program_slug' => 'slug']])]
    #[Entity('season', options: ['mapping' => ['season_number' => 'number']])]
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season
        ]);
    }

    #[Route('/{program_slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[Entity('program', options: ['mapping' => ['program_slug' => 'slug']])]
    public function edit(Request $request, MailerInterface $mailer, Program $program, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $programRepository->save($program, true);
            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('nem.nem@nem.com')
                ->subject('Une série vient d\'être modifiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));
            $mailer->send($email);
            $this->addFlash('success', "La série " . $program->getTitle() . " à bien été modifiée");

            return $this->redirectToRoute('program_showDetail', ["program_slug" => $program->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{program_slug}/season/{season}/episode/{episode_slug}', name: 'episode_show', methods: ['GET', 'POST'])]
    #[Entity('program', options: ['mapping' => ['program_slug' => 'slug']])]
    #[Entity('episode', options: ['mapping' => ['episode_slug' => 'slug']])]
    public function showEpidode(Request $request, Program $program, Season $season, Episode $episode, CommentRepository $commentRepository)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        // Get data from HTTP request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Slugging
            $comment->setEpisode($episode);
            $comment->setAuthor($this->getUser());
            $comment->setCreatedAt(new \DateTime("now"));
            //Saving
            $commentRepository->save($comment, true);


            // flash message
            $this->addFlash('success', 'New comment has been created');

            return $this->redirectToRoute("program_episode_show", [
                "program_slug" => $program->getSlug(),
                "season"=>$season->getNumber(),
                "episode_slug" => $episode->getSlug()
            ]);
        }

        return $this->renderForm('program/episode.html.twig', [
            'form' => $form,
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);

    }

    #[Route('/{program_slug}', name: 'delete', methods: ['POST'])]
    #[Entity('program', options: ['mapping' => ['program_slug' => 'slug']])]
    public function delete(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        $progamName = $program->getTitle();

        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $programRepository->remove($program, true);
        }
        $this->addFlash('danger', "$progamName à bien été supprimé");
        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }
}
