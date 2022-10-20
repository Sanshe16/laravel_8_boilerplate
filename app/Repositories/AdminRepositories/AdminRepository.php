<?php

namespace App\Repositories\AdminRepositories;

use App\Models\BusinessType;
use App\Models\User;
use App\Models\Country;
use App\Models\State;


class AdminRepository
{ 
       
    public function authUser()
    {
        return auth()->user();
    }

    public function fetchPublishedBusinessTypes()
    {
        return BusinessType::query()->status('published')->get();
    }

    // GET ALL COUNTRY 
    public function fetchCountries()
    { 
        return Country::all();
    }

    // GET SPECIFIC USER SELECTED COUNTRY 
    public function fetchCountry($id)
    {
        return Country::select('id', 'name')->where('id', $id)->first();
    }
    
    public function getStates($country_id)
    { 
        return State::select('id', 'name')->where("country_id", $country_id)->get();
    }

    public function fetchState($id)
    {
        return State::select('id', 'name')->where('id', $id)->first();
    }



    public function getAdminProfile()
    {
        return $user = auth()->user();
    }
     
    public function updateGeneralSettings($request)
    {
        $user = User::where('id', auth()->id())->first();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->country_id = $request->country;
        $user->state_id = $request->states;
        $user->city = $request->city;
        $user->phone_number = $request->phone_number;
        $user->fax = $request->fax;
        $user->zip_code = $request->zip_code;
        $user->address = $request->address;
        if($user->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public function updateBusinessSettings($request)
    { 
        $user = User::where('id', auth()->id())->first();
        $user->company_name = $request->company_name;
        $user->company_url = $request->company_url;
        $user->business_type_id = $request->business_type;
        if($user->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
