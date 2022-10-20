<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserToken;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use App\Exceptions\UserExceptions\UserNotVerifiedException;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (! $request->user() || ($request->user() instanceof MustVerifyEmail && ! $request->user()->hasVerifiedEmail())) 
        {
            if($request->expectsJson())
            {
                throw new UserNotVerifiedException('You need to confirm your account. We have sent you an activation link and OTP to your email.', 403);
            }
            else
            {
                $user_token = UserToken::where('email', auth()->user()->email)->first();

                Redirect::route('verifyOTP')->with(['token'=> $user_token->token, 'email' => auth()->user()->email]);
            }
        }

        return $next($request);
    }
}
