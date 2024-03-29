<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function show()
    {
        $responseCategories = $this->categoryService->findAllCategory();

        return response()->json($responseCategories);
    }

    public function store(CategoryStoreRequest $request)
    {
        $dataCategory = $request->all();

        $responseCategory = $this->categoryService->register($dataCategory);

        return response()->json($responseCategory);
    }
}
