<?php

namespace App\Http\Controllers\V1\Backend\Inventory;

use App\DataTables\BannerDataTable;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use App\Models\Banner;
use App\Services\InventoryServices\BannerService;
use App\Http\Requests\InventoryRequests\StoreBannerRequest;
use App\Http\Requests\InventoryRequests\UpdateBannerRequest;

class BannerController extends Controller
{
    use ApiResponse;
    protected $bannerService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(BannerService $bannerService)
    {
        $this->middleware('auth');
        $this->bannerService = $bannerService;
    }

    public function index(BannerDataTable $dataTable)
    {
        return $dataTable->render('backend.banners.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreBannerRequest $request)
    {
        $this->bannerService->store($request);
        return $this->success(trans('admin.CREATE_BANNER'), ['success' => true, 'data' => null]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return view('backend.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('backend.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBannerRequest $request, $id)
    {
        $this->bannerService->update($request, $id);
        return $this->success(trans('admin.UPDATE_BANNER'), ['success' => true, 'data' => null]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy($banner_id)
    {
        try
        {
            $this->bannerService->destroy($banner_id);
            return $this->success(trans('admin.DELETE_BANNER'), ['success' => true, 'data' => null]);
        }
        catch (\Throwable $th) {
            return $this->error('Record not found', Response::HTTP_NOT_FOUND, ['success' => false, 'data' => null]);
        }
    }
}
