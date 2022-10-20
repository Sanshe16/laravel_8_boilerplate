<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::user()->status == 'blocked')  {
            // toastr()->warning('You are not authenticated please try again valid credential');
            return redirect()->back();
        }
        else if(Auth::user()->status == 'unverified')  {
            // toastr()->warning('You are not authenticated please try again valid credential');
            return redirect()->back();
        }
        
        return $next($request);
    }
}
