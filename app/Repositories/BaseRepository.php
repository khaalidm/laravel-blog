<?php

namespace App\Repositories;

abstract class BaseRepository
{
    protected $model;

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getAll()
    {
        return $this->model->all();
    }
}
