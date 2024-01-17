<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\Category;

class ValidateCategoryOwnership
{
    protected $validateCategoryRepo;

    public function __construct(Category $validateCategoryRepo)
    {
        $this->validateCategoryRepo = $validateCategoryRepo;
    }

    public function validate($categoryId, $userId)
    {
        $isOwner = $this->validateCategoryRepo
            ->where('id', '=', $categoryId)
            ->where('user_id', '=', $userId)
            ->first();

        if (!$isOwner) {
            throw new \Exception('Category not found');
        }
    }
}
