<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Carbon\Carbon;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\V1\ApiV1Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Exceptions\AuthExceptions\UserNotLoginable;
use App\Http\Requests\AuthRequests\UserLoginRequest;
use App\Exceptions\AuthExceptions\BadLoginCredentialException;

class AuthController extends ApiV1Controller
{
	use AuthenticatesUsers, ApiResponse;

    protected $customerRoleId;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->customerRoleId = config()->get('roles_config.roles.customer');
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
            $tokenResult = auth()->user()->createToken('ApiUserAuthAccessToken');
            
            return $this->success(trans('api.USER_LOGIN_SUCCESS'),
            [
                'accessToken' => $tokenResult->accessToken,
                'tokenType'   => 'Bearer',
                'expiresAt'   => Carbon::parse($tokenResult->token->expires_at)->toIso8601String(),
                'user' => auth()->user()
            ]);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        throw new BadLoginCredentialException(trans('auth.CREDENTIALS_INVALID'));
        
    }

	
	public function logout(Request $request) 
    {
        $user = User::whereId(Auth::id())->first();
		auth()->user()->token()->revoke();
		// $user->fcm_token = null;
		$user->save();
        
        return $this->success(trans('API.USER_LOGOUT_SUCCESS'));
	}

    protected function attemptLogin(Request $request)
    {
        if($check_user = User::query()->getUserByEmail($request['email'])->first())
        {
            if(Hash::check($request['password'], $check_user['password']))
            {
                if($check_user['status'] == 'inactive')
                {
                    $inactive_user['email'] = $check_user['email'];
                    $inactive_user['status'] = $check_user['status'];

                    throw new GeneralException(trans('auth.ACCOUNT_INACTIVE'), Response::HTTP_UNAUTHORIZED, ['user' => $inactive_user]);
                }

                else if($role = $check_user->roles->first())
                {
                    if($role->id == $this->customerRoleId)
                    {
                        if($this->guard()->attempt( $this->credentials( $request), $request->filled('remember'))) 
                        {
                            return true;
                        }
                    }
                    else
                    {
                        throw new UserNotLoginable(trans('auth.USER_LOGIN_PERMISSION_API'), Response::HTTP_FORBIDDEN);
                    }
                    
                }
            
                return false;
            }
        }

        return false;
    }

    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

}
