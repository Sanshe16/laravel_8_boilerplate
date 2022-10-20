<?php

namespace App\Http\Controllers\V1\Backend\Inventory;

use App\DataTables\VendorsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequests\StoreVendorRequest;
use App\Http\Requests\InventoryRequests\UpdateVendorRequest;
use App\Traits\ApiResponse;
use App\Services\InventoryServices\VendorService;
use App\Services\AdminServices\AdminService;
use App\Repositories\AdminRepositories\AdminRepository;
use Illuminate\Http\Response;
use App\Models\User;


class VendorController extends Controller
{
    use ApiResponse;
    protected $vendorService;
    protected $adminRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VendorService $vendorService, AdminRepository $adminRepository)
    {
        $this->middleware('auth');
        $this->vendorService = $vendorService;
        $this->adminRepository = $adminRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VendorsDataTable $dataTable)
    {
        return $dataTable->render('backend.vendors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries =  $this->adminRepository->fetchCountries();
        $business_types =  $this->adminRepository->fetchPublishedBusinessTypes();
        return view('backend.vendors.create', compact('countries', 'business_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\InventoryRequests\StoreUnitRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVendorRequest $request)
    {
        $this->vendorService->store($request);
        return $this->success(trans('admin.CREATE_VENDOR'), ['success' => true, 'data' => null]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $unit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendors = User::findOrFail($id);
        $country =  $this->adminRepository->fetchCountry($vendors->country_id);
        $state =  $this->adminRepository->fetchState($vendors->state_id);
        return view('backend.vendors.show', compact('vendors', 'country', 'state'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendor = User::findOrFail($id);
        $countries =  $this->adminRepository->fetchCountries();
        $business_types =  $this->adminRepository->fetchPublishedBusinessTypes();
        $states =  $this->adminRepository->getStates($vendor->country_id);
        return view('backend.vendors.edit', compact('vendor', 'countries', 'business_types', 'states'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\InventoryRequests\StoreVendorRequest  $request
     * @param  \App\Models\Vendor 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVendorRequest $request)
    {
        $this->vendorService->updateVendor($request);
        return $this->success(trans('admin.UPDATE_VENDOR'), ['success' => true, 'data' => null]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $Vendor
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
