<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function createCategory(array $categoryDetails)
    {
        return Category::create($categoryDetails);
    }

    public function updateCategory(int $id, array $newDetails)
    {
        $user = $this->getById($id);

        return tap($user)->update($newDetails);
    }
}
