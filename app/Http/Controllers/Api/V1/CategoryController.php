<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryDetailResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\InventoryServices\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends ApiV1Controller
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
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = limitPerPage($request);
        $categories = $this->categoryService->activeCategoriesWithLevel(0)
                            ->with('subCategories')
                            ->searchByName($request->key)
                            ->paginate($limit);
        return $this->success(
            'Categories List Response',
            new CategoryCollection($categories)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Category $category)
    {
        return $this->success(
            "Single Category Response",
            ['category' => new CategoryDetailResource($category)]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
