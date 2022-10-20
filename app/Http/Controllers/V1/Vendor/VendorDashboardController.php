<?php

namespace App\Http\Controllers\V1\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('is_verified');
    }

    public function vendorDashboard()
    {
        return view('vendor.index');
    }
   

    public function vendorProfile()
    {
        return ('<h2>Wellcome to Vendore Profile</h2>');
    }
}
