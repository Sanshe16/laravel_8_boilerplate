<?php

namespace App\Http\Controllers\V1\Backend\Inventory;

use App\DataTables\CategoriesDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequests\StoreCategoryRequest;
use App\Http\Requests\InventoryRequests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\InventoryServices\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    use ApiResponse;
    protected $categoryService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('auth');
        $this->categoryService = $categoryService;
    }

    public function index(CategoriesDataTable $dataTable)
    {
        return $dataTable->render('backend.categories.index');
    }

    public function create(Request $request)
    {
        $categories = $this->categoryService->activeCategoriesWithLevel(0)->get();
        return view('backend.categories.create', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->store($request);
        return $this->success(trans('admin.CREATE_CATEGORY'), ['success' => true, 'data' => null]);
    }

    public function show(Category $category)
    {
        return view('backend.categories.show', compact('category'));
    }

    public function edit($categoryId)
    {
        $category = $this->categoryService->edit($categoryId);
        $categories = $this->categoryService->allCategories()->get();
        $parentCategories = $category->replicate();
        return view('backend.categories.edit', compact('category', 'categories', 'parentCategories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->categoryService->update($category, $request);
        return $this->success(trans('admin.UPDATE_CATEGORY'), ['success' => true, 'data' => null]);
    }

    public function destroy($id)
    {
        try {
            $this->categoryService->destroy($id);
            return $this->success(trans('admin.DELETE_CATEGORY'), ['success' => true, 'data' => null]);
        } catch (\Throwable $th) {
            return $this->error('Category not found', Response::HTTP_NOT_FOUND, ['success' => false, 'data' => null]);
        }
    }

    public function subCategories(Category $category)
    {
        $subCategories = $category->subCategories;
        return $this->success('', ['success' => true, 'data' => compact('subCategories')]);
    }
}
