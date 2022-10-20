<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use Symfony\Component\HttpFoundation\Response;
use App\Services\InventoryServices\ProductService;
use App\Http\Requests\InventoryRequests\StoreProductRequest;
use App\Http\Resources\ProductDetailResource;

class ProductController extends Controller
{
    use ApiResponse;
    protected $productService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = limitPerPage($request);
        $products = $this->productService->products()
                            ->with('media')
                            ->searchByName($request->key)
                            ->paginate($limit);
        return $this->success(
            'Products List Response',
            new ProductCollection($products)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\InventoryRequests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed  $productId
     * @return \Illuminate\Http\Response
     */
    public function show($productId)
    {
        $product = Product::where('id', $productId)->with('media', 'brand')->first();
        return $this->success(
            "Single Product Response",
            ['product' => new ProductDetailResource($product)]
        );
    }

    /**
     * Display all products with category and its children.
     *
     * @param  mixed  $productId
     * @return \Illuminate\Http\Response
     */
    public function showProductsWithCatgeory(Request $request, $categoryId)
    {
        $limit = limitPerPage($request);

        $products =  $this->productService->fetchProductsWithCategory($categoryId)->with('media')
        ->searchByName($request->key)
        ->paginate($limit);

        return $this->success(
            "Products With Category Response",
            ['product' => new ProductCollection($products)]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\InventoryRequests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $this->productsService->destroy($id);
            return $this->success(trans('admin.DELETE_PRODUCT'), ['success' => true, 'data' => null]);
        }
        catch (\Throwable $th) {
            return $this->error('Product not found', Response::HTTP_NOT_FOUND, ['success' => false, 'data' => null]);
        }
    }
}
