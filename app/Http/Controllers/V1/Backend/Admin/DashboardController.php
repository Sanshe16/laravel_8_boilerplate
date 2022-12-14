<?php

namespace App\Http\Controllers\V1\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function adminDashboard()
    {
        return view('backend.index');
    }
    
}
