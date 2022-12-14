<?php

namespace App\Service;


use App\Repository\CategoryRepository;

class navigation
{
    public function __construct(private readonly CategoryRepository $categoryRepository)
    {
    }
    public function getCategories() {
        return $this->categoryRepository->findAll();
    }
}