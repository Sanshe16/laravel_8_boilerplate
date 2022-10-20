<?php

namespace App\Services\InventoryServices;

use App\Repositories\InventoryRepositories\CategoryRepository;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function allCategories()
    {
        return $this->categoryRepository->allCategories();
    }

    public function activeCategoriesWithLevel($level)
    {
        return $this->categoryRepository->activeCategoriesWithLevel($level);
    }

    public function store($request)
    {
        $imagePath = $this->categoryRepository->storeImage($request);
        return $this->categoryRepository->store($request, $imagePath);
    }

    public function edit($categoryId)
    {
        return $this->categoryRepository->show($categoryId);
    }

    public function update($category, $request)
    {
        $imagePath = $this->categoryRepository->storeImage($request);
        return $this->categoryRepository->update($category, $request, $imagePath);
    }

    public function destroy($categoryId)
    {
        return $this->categoryRepository->destroy($categoryId);
    }

}
