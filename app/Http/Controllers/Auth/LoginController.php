<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use App\Models\UserToken;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Exceptions\AuthExceptions\UserNotLoginable;
use App\Exceptions\AuthExceptions\UserAccountInactiveException;
use App\Http\Requests\AuthRequests\UserLoginRequest;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $adminRoleId;
    protected $vendorRoleId;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->adminRoleId = config()->get('roles_config.roles.admin');
        $this->vendorRoleId = config()->get('roles_config.roles.vendor');
    }

    public function login(UserLoginRequest $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) 
        {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) 
        {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    protected function attemptLogin(Request $request)
    {
        $check_user = User::where('email', $request['email'])->first();

        if($check_user && Hash::check($request['password'], $check_user->password))
        {
            if($check_user['status'] == config()->get('constant.user_account_status.inactive'))
            {
                $email = $check_user['email'];
                $token = UserToken::select('token')->where('email', $email)->first();
                $token = $token['token'];
           
                throw new UserAccountInactiveException(trans('auth.ACCOUNT_INACTIVE'), Response::HTTP_FORBIDDEN, compact('email', 'token'));
            }
            
            if($role = $check_user->roles->first())
            {
                if($role->id == $this->adminRoleId || $role->id == $this->vendorRoleId)
                {

                    if($this->guard()->attempt( $this->credentials( $request), $request->filled('remember'))) 
                    {
                        return true;
                    }
                }
                else
                {
                    throw new UserNotLoginable(trans('auth.USER_LOGIN_PERMISSION_WEB'), Response::HTTP_FORBIDDEN);
                }
            }
        
            return false;
        }
    
        return false;

    }

    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }
}
