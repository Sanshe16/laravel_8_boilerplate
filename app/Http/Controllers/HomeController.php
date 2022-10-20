<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('verified');
        // $this->middleware('verified_by_admin');
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // User role
        $role = Auth::user()->roles->first();

        // Check user role
        if($role->id) 
        {
            switch ($role->id) 
            {
                case 1:
                    return redirect(route('admin.dashboard'));
                    break;
                case 2:
                    return redirect(route('vendor.dashboard'));
                    break;
                case 3:
                    flash(trans('auth.ACCOUNT_VERIFIED'), 'success');
                    return redirect(route('login'))->with(Auth::logout());
                    break;
                default:
                    return redirect(route('login'));
                    break;
            }
        }
        else
        {
            return redirect(route('login'));
        }
       
    }
}
