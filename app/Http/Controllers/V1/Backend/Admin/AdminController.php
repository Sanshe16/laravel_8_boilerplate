<?php

namespace App\Http\Controllers\V1\Backend\Admin;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Modules\Page\Entities\Page;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Services\AdminServices\AdminService;
use App\Services\AdminServices\PageRequestService;
use App\Http\Requests\AdminRequests\AdminProfileUpdateRequest;
use App\Http\Requests\AdminRequests\AdminGeneralSettingRequest;
use App\Traits\ApiResponse;

class AdminController extends Controller
{
    use ApiResponse;

    protected $adminService;


    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    

    public function settings()
    {
        try
        {
            $this->payload = $this->adminService->adminSetting();
        }
        catch(GeneralException $e)
        {
            throw new GeneralException($e->getMessage(), $e->getCode());
        }
        return view('backend.settings.general_setting', $this->payload);
    }


    public function getStates(Request $request)
    {
        $request->validate([ 'country_id' => 'required']); 
        try
        {
            $this->states = $this->adminService->getStates($request->country_id);
        }
        catch(GeneralException $e)
        {
            throw new GeneralException($e->getMessage(), $e->getCode());
        }
        return $this->success(trans('admin.GET_COUNTRY_STATE'), ['success' => true, 'data' => ['states' => $this->states]]);
    }


    public function updateGeneralSettings(AdminGeneralSettingRequest $request)
    {
        try
        {
            $this->adminService->updateGeneralSettings($request);
        }
        catch(GeneralException $e)
        {
            throw new GeneralException($e->getMessage(), $e->getCode());
        }

        return $this->success(trans('admin.UPDATE_GENERAL_SETTING'), ['success' => true, 'data' => null]);     
    }


    public function updateBusinessSettings(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'business_type' => 'required',
        ]);
        try
        {
            $this->adminService->updateBusinessSettings($request);
        }
        catch(GeneralException $e)
        {
            throw new GeneralException($e->getMessage(), $e->getCode());
        }

        return $this->success(trans('admin.UPDATE_BUSINESS_SETTING'), ['success' => true, 'data' => null]);     
    }


    // public function adminProfile()
    // {
    //     try
    //     {
    //         $this->user = $this->adminService->getAdminProfile();
    //     }
    //     catch(GeneralException $e)
    //     {
    //         throw new GeneralException($e->getMessage(), $e->getCode());
    //         // throw new GeneralException(trans('api.ERROR_UNKNOWN'), 500);
    //     }

    //     return view('backend.content.admin.profile.admin_profile', $this->params);
    // }

    // public function saveProfile(AdminProfileUpdateRequest $request)
    // {
    //     DB::beginTransaction();
    //     try 
    //     {
    //         $this->user = $this->adminService->saveProfile($request);
    //     }
    //     catch(\Exception $e)
    //     {
    //         DB::rollBack();
    //         throw new GeneralException($e->getMessage(), $e->getCode());

    //         // throw new GeneralException(trans('api.ERROR_UNKNOWN'), 500);
    //     }
        
    //     DB::commit();

    //     $request->session()->flash('alert-success', 'Profile data Saved!');
    //     return redirect()->route('admin.adminProfile');
    // }



}
