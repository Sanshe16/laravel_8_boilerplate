<?php

namespace App\Services\AdminServices;

use App\Repositories\AdminRepositories\AdminRepository;

class AdminService
{
    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }
    
    public function adminSetting()
    {
        $user =  $this->adminRepository->authUser();
        $business_types =  $this->adminRepository->fetchPublishedBusinessTypes();
        $countries =  $this->adminRepository->fetchCountries();
        $states =  $this->adminRepository->getStates($user->country_id);
        return compact('user', 'business_types', 'countries', 'states');
    }
    
    public function getStates($country_id)
    {
        return $this->adminRepository->getStates($country_id);
    }

    public function updateGeneralSettings($request)
    {
        return $this->adminRepository->updateGeneralSettings($request);
    }

    public function updateBusinessSettings($request)
    {
        return $this->adminRepository->updateBusinessSettings($request);
    }

    public function getAdminProfile()
    {
        return $this->adminRepository->getAdminProfile();
    }

    public function saveProfile($request)
    {
        return $this->adminRepository->saveProfile($request);
    }
}
