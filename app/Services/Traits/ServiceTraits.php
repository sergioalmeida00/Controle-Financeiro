<?php

namespace App\Services\Traits;
use App\Models\User;

trait ServiceTraits
{
    public function getUserAuth()
    {
        return auth()->user()->id;
    }
}
