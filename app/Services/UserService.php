<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $repository;

    public function __construct(User $model)
    {
        $this->repository = $model;
    }

    public function register($data)
    {
        return $this->repository->create([
            'email' => $data['email'],
            'name' => $data['name'],
            'password' => Hash::make($data['password'])
        ]);
    }

}
