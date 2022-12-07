<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name:'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {

        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        // Create category and the form, linked with $category
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // For example : persiste & flush the entity
            $categoryRepository->save($category, true);
            // And redirect to a route that display the result
            return $this->redirectToRoute('category_index');
        }

        // Render the form (best practice)
        return $this->renderForm('category/new.html.twig', [
            'form' => $form,
            'categories' => $categoryRepository->findAll(),
        ]);

        // Alternative
        // return $this->render('category/new.html.twig', [
        //   'form' => $form->createView(),
        // ]);
    }

    #[Route('/{name}', name: 'show')]
    public function show(Category $category, CategoryRepository $categoryRepository, ProgramRepository $programRepository)
    {
        //$category = $categoryRepository->findOneBy(['name' => $categoryName]);
        if (!$category) {
            throw $this->createNotFoundException(
                "No program with name : $category found in program's table."
            );
        } else {
            return $this->render('category/show.html.twig', [
                'category' => $category,
                'categories' => $categoryRepository->findAll(),
            ]);
        }
    }
}

