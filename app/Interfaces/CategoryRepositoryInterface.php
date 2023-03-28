<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    public function createCategory(array $categoryDetails);

    public function updateCategory(array $newDetails);
}
