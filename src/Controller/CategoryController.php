<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/{name}', name: 'show')]
    public function show(Category $category, CategoryRepository $categoryRepository, ProgramRepository $programRepository)
    {
        //$category = $categoryRepository->findOneBy(['name' => $categoryName]);
        if (!$category) {
            throw $this->createNotFoundException(
                "No program with name : $category found in program's table."
            );
        } else {
            $programs = $programRepository->findBy(['category' => $category->getId()], limit: 3);
            return $this->render('category/show.html.twig', [
                'category' => $category,
                'programs' => $programs,
                'categories' => $categoryRepository->findAll(),
            ]);
        }
    }
}

