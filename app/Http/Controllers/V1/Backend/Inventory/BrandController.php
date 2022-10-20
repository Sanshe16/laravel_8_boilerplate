<?php

namespace App\Http\Controllers\V1\Backend\Inventory;

use App\DataTables\BrandsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InventoryRequests\StoreBrandRequest;
use App\Traits\ApiResponse;
use App\Models\Brand;
use Illuminate\Http\Response;
use App\Services\InventoryServices\BrandService;


class BrandController extends Controller
{
    use ApiResponse;

    protected $brandsService;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BrandService $brandsService)
    {
        $this->middleware('auth');
        $this->brandsService = $brandsService;
    }

    public function index(BrandsDataTable $dataTable)
    {
        return $dataTable->render('backend.brands.index');
    }

    public function create()
    {
        return view('backend.brands.create');
    }

    public function store(StoreBrandRequest $request)
    {
        $this->brandsService->store($request);
        return $this->success(trans('admin.CREATE_BRAND'), ['success' => true, 'data' => null]);
    }

    public function show($id)
    {
        $brands = Brand::find($id);
        return view('backend.brands.show', compact('brands'));
    }

    
    public function edit($id)
    {
        $brands = Brand::find($id);
        return view('backend.brands.edit', compact('brands'));
    }

   
    public function update(StoreBrandRequest $request, $id)
    {
        $this->brandsService->update($request, $id);
        return $this->success(trans('admin.UPDATE_BRANDS'), ['success' => true, 'data' => null]);
    }

   
    public function destroy($brandId)
    {
        try 
        {
            $this->brandsService->destroy($brandId);
            return $this->success(trans('admin.DELETE_BRAND'), ['success' => true, 'data' => null]);
        }
        catch (\Throwable $th) 
        {
            return $this->error('Record not found', Response::HTTP_NOT_FOUND, ['success' => false, 'data' => null]);
        }
    }
}
