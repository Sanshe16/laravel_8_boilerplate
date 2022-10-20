<?php

namespace App\Http\Controllers\V1\Backend\Inventory;

use App\Http\Controllers\Controller;
use App\DataTables\ShippingTypeDataTable;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\InventoryServices\ShippingTypeService;
use App\Http\Requests\InventoryRequests\StoreShippingTypeRequest;
use App\Models\ShippingType;
use Illuminate\Http\Response;

class ShippingTypeController extends Controller
{
    use ApiResponse;
    protected $shippingTypeService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ShippingTypeService $shippingTypeService)
    {
        $this->middleware('auth');
        $this->shippingTypeService = $shippingTypeService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ShippingTypeDataTable $dataTable)
    {
        return $dataTable->render('backend.shippingType.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.shippingType.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShippingTypeRequest $request)
    {
        $this->shippingTypeService->store($request);
        return $this->success(trans('admin.CREATE_SHIPPING_TYPE'), ['success' => true, 'data' => null]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shippingType = ShippingType::find($id);
        return view('backend.shippingType.show', compact('shippingType'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shippingType = ShippingType::find($id);
        return view('backend.shippingType.edit', compact('shippingType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreShippingTypeRequest $request, $id)
    {
        $this->shippingTypeService->update($request, $id);
        return $this->success(trans('admin.SHIPPING_TYPE_UPDATE'), ['success' => true, 'data' => null]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $shippingType = ShippingType::find($id);
            $shippingType->delete();
            return $this->success(trans('admin.DELETE_SHIPPING_TYPE'), ['success' => true, 'data' => null]);
        } catch (\Throwable $th) {
            return $this->error('Unit not found', Response::HTTP_NOT_FOUND, ['success' => false, 'data' => null]);
        }
    }
}
