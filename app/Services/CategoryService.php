<?php

namespace App\Services;

use App\Models\Category;
use App\Services\Traits\ServiceTraits;

class CategoryService
{
    protected $repository;
    protected $userId;

    use ServiceTraits;

    public function __construct(Category $model)
    {
        $this->repository = $model;
        $this->userId = $this->getUserAuth();
    }

    public function findAllCategory()
    {
        return $this->repository
            ->where('user_id', '=', $this->userId)
            ->get();
    }

    public function register($data)
    {
        return $this->repository
            ->create([
                'user_id' => $this->userId,
                'name' => $data['name'],
                'icon' => $data['icon'],
                'type' => $data['type']
            ]);
    }
}
