<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait UuidTraits
{
    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
