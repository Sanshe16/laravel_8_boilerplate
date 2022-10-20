<?php

namespace App\Http\Controllers\V1\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\InventoryServices\HelpCenterService;
use App\DataTables\HelpCenterDataTable;
use App\Models\HelpCenter;
use Illuminate\Http\Response;
use App\Http\Requests\InventoryRequests\StoreHelpCenterRequest;

class HelpCenterController extends Controller
{
    use ApiResponse;
    protected $helpCenterService;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(HelpCenterService $helpCenterService)
    {
        $this->middleware('auth');
        $this->helpCenterService = $helpCenterService;
    }

    public function index(HelpCenterDataTable $dataTable)
    {
        return $dataTable->render('backend.helpCenter.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.helpCenter.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHelpCenterRequest $request)
    {
        $this->helpCenterService->store($request);
        return $this->success(trans('admin.CREATE_HELPCENTER'), ['success' => true, 'data' => null]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $helpCenter = HelpCenter::find($id);
        return view('backend.helpCenter.show', compact('helpCenter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $helpcenter = HelpCenter::find($id);
        return view('backend.helpCenter.edit', compact('helpcenter'));
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
        $request->validate([
            'name' => 'required|min:2',
            'description' => 'required|min:2',
        ]);
        $this->helpCenterService->update($request, $id);
        return $this->success(trans('admin.UPDATE_HELPCENTER'), ['success' => true, 'data' => null]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $this->helpCenterService->destroy($id);
            return $this->success(trans('admin.DELETE_HELPCENTER'), ['success' => true, 'data' => null]);
        }
        catch (\Throwable $th) {
            return $this->error('Record not found', Response::HTTP_NOT_FOUND, ['success' => false, 'data' => null]);
        }
    }
}
