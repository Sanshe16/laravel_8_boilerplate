<?php

namespace App\Http\Controllers\V1\Backend\Inventory;

use App\Models\Product;
use App\Traits\ApiResponse;
use App\Models\ProductMedia;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Services\InventoryServices\ProductService;
use App\Services\InventoryServices\CategoryService;
use App\Http\Requests\InventoryRequests\StoreProductRequest;

class ProductController extends Controller
{
    use ApiResponse;

    protected $productsService;
    protected $categoryService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductService $productsService, CategoryService $categoryService)
    {
        $this->middleware('auth');
        $this->productsService = $productsService;
        $this->categoryService = $categoryService;
    }

    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('backend.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Role::whereId(2)->first();
        $vendors = $role->users()->select('users.id', 'username', 'first_name', 'last_name', 'email', 'company_name')->get();
        $categories = $this->categoryService->activeCategoriesWithLevel(0)->get();
        $shipping_types = $this->productsService->fetchAllShippingTypes();
        return view('backend.products.create',  compact('categories', 'shipping_types', 'vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $this->productsService->store($request);
        return $this->success(trans('admin.CREATE_PRODUCT'), ['success' => true, 'data' => null]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->productsService->show($id);
        $shipping_type = $this->productsService->shippingType($product->shipping_type_id)->select('id','name', 'min_shipping_days', 'max_shipping_days', 'shipping_cost')->first();
        return view('backend.products.show', compact('product', 'shipping_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::whereId(2)->first();
        $vendors = $role->users()->select('users.id', 'username', 'first_name', 'last_name', 'email')->get();
        $product = $this->productsService->edit($id);
        $categories = $this->categoryService->activeCategoriesWithLevel(0)->get();
        $shipping_types = $this->productsService->fetchAllShippingTypes();
        return view('backend.products.edit', compact('product', 'categories', 'shipping_types', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductRequest $request, $id)
    {
        $this->productsService->update($request, $id);
        return $this->success(trans('admin.PRODUCT_UPDATE'), ['success' => true, 'data' => null]);
    }

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
    
    public function getShippingType($id)
    {
        return $this->productsService->shippingType($id)->select('shipping_cost')->first();
    }
}
