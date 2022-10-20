<?php

namespace App\Http\Controllers\Api\V1;


use Illuminate\Support\Facades\Auth;


class HomeController extends ApiV1Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // User role
        $roles = Auth::user()->roles->first();

        session(['permissions' => $roles->permissions]);
        // Check user role
        if($roles->id) {
            switch ($roles->id) {
                case 1:
                    return 'super-admin/dashboard';
                    break;
                case 2:
                    return redirect(route('dashboard'));
                    break;
                case 3:
                    // Via the global "session" helper...
                    session(['secret' => md5(time()*rand(1, 99999))]);
                    return redirect('/news_feed');
                    break;
                default:
                    return redirect(route('landing_page'));
                    break;
            }
        }else{
            return redirect(route('landing_page'));
        }
    }


}
