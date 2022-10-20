<?php

namespace App\Http\Controllers\V1\Backend\Inventory;

use App\DataTables\UnitsDatatable;
use App\Http\Controllers\Api\V1\ApiV1Controller;
use App\Http\Requests\InventoryRequests\StoreUnitRequest;
use App\Http\Requests\InventoryRequests\UpdateUnitRequest;
use App\Models\Unit;
use App\Services\InventoryServices\UnitService;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;

class UnitController extends ApiV1Controller
{
    use ApiResponse;
    protected $unitService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UnitService $unitService)
    {
        $this->middleware('auth');
        $this->unitService = $unitService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UnitsDatatable $dataTable)
    {
        return $dataTable->render('backend.units.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $units = $this->unitService->units()->get();
        return view('backend.units.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\InventoryRequests\StoreUnitRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUnitRequest $request)
    {
        $this->unitService->store($request);
        return $this->success(trans('admin.CREATE_UNIT'), ['success' => true, 'data' => null]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        return view('backend.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        return view('backend.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\InventoryRequests\UpdateUnitRequest  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $this->unitService->update($unit, $request);
        return $this->success(trans('admin.UPDATE_UNIT'), ['success' => true, 'data' => null]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy($unit_id)
    {
        try {
            $this->unitService->destroy($unit_id);
            return $this->success(trans('admin.DELETE_UNIT'), ['success' => true, 'data' => null]);
        } catch (\Throwable $th) {
            return $this->error('Unit not found', Response::HTTP_NOT_FOUND, ['success' => false, 'data' => null]);
        }
    }
}
