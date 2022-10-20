<?php

namespace App\Http\Controllers\Api\V1\Auth;


use Carbon\Carbon;
use App\Exceptions;
use App\Models\User;
use App\Models\PostTag;
use App\Models\UserToken;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FriendPostTag;
use App\Traits\UserQueryTrait;
use App\Models\UserFriendPostTag;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Users\RegisterRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\V1\ApiV1Controller;
use App\Http\Requests\AuthRequests\VerifyOTPRequest;
use App\Exceptions\UserExceptions\UserNotFoundException;
use App\Http\Requests\AuthRequests\ResetPasswordRequest;
use App\Http\Requests\AuthRequests\CustomerRegisterRequest;

class RegisterController extends ApiV1Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use ApiResponse;

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

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return \App\Models\User
     */
    protected function register(CustomerRegisterRequest $request)
    {
        if (User::query()->getUserByEmail($request['email'])->first()) 
        {
            throw new GeneralException(trans('api.USER_ALREADY_EXISTS'), Response::HTTP_CONFLICT);
        }


        DB::beginTransaction();
        
        do
        {
            $username = strtolower(str_replace(" ", "-", $request['last_name'])) . '.' . $otp = generateKey(6, 6, false, false);
            
        } //check if the name already exists and if it does, try again
        while(User::where('username', $username)->first());

        $email = $request['email'];
        $title = "Registration Email";
        $content = "New Account Register";
        $otp = generateKey(6, 6, false, false);

        do 
        {
            $token = Str::random(16);
        } //check if the token already exists and if it does, try again
        while (UserToken::where('token', $token)->first());

        $otp_url =  url("/verify_otp?token={$token}&email={$email}");

        try
        {
            Mail::send('emails.registration_email', ['name' => $request['first_name']." ".$request['last_name'], 'email' => $email, 'title' => $title, 'content' => $content, 'otp' => $otp, 'otp_url' => $otp_url], function ($message) use ($email) {
                $message->to($email)->subject('Registration Email!');
            });
        }
        catch(\Exception $e)
        {
            throw new GeneralException($e->getMessage(), 500);
        }

        $OTPTime = date('Y-m-d h:i:s', strtotime("+15 minutes", strtotime(now())));
       
        try
        {
            $newUser =  User::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'username' => $username,
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'status' => 'inactive',
                'otp_pin' => $otp,
                'otp_datetime' => $OTPTime,

            ]);
    
            $token = UserToken::create([
                'email' => $newUser->email,
                'token' => $token,
                'user_id' => $newUser->id,
                'created_at'   =>   Carbon::now(),
                'updated_at'   =>   Carbon::now(),
            ]);

            $newUser->roles()->attach($this->customerRoleId);

            DB::commit();

        }
        catch(\Exception $e)
        {
            DB::rollBack();
            throw new GeneralException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $this->success(trans('auth.CHECK_OTP'), ['user' => $newUser]);
    }

    public function verifyOTP(VerifyOTPRequest $request)
    {
        if($user = User::query()->getUserByEmail($request['email'])->first())
        {
            $otp = $request->otp;

            if(empty($otp) || is_null($otp)) 
            {
                throw new ValidationException(trans('validation.OTP_REQUIRED'));
            }

            if($user->otp_pin === $request['otp'])
            {
                // Look up the Token
                $token = UserToken::where('email', $request['email'])->first();

                if(!$token) 
                {
                    throw new GeneralException(trans('auth.OTP_ALREADY_VERIFIED'));
                }
                else
                {
                    $token->delete();
                }

                $user->update(
                [
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
                $user->save();

                if(!Auth::loginUsingId($user['id'])) 
                {
                    throw new GeneralException(trans('auth.UNABLE_TO_LOGIN'));
                }
                else
                {
                    $tokenResult = auth()->user()->createToken('ApiAuthAccessToken');
                    
                    return $this->success(trans('api.USER_LOGIN_SUCCESS'),
                    [
                        'accessToken' => $tokenResult->accessToken,
                        'tokenType'   => 'Bearer',
                        'expiresAt'   => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toIso8601String(),
                        'user' =>auth()->user()
                    ]);

                }
            }
            else
            {
                throw new GeneralException(trans('auth.INVALID_OTP')); 
            }
        }
        else
        {
            throw new UserNotFoundException(trans('auth.USER_NOT_FOUND_EMAIL'));
        }
        
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $email = $request->email;

        if($email_user = User::query()->getUserByEmail($email)->first())
        {
            if($email_user->status == 'inactive')
            {
                $username = $email_user['username'];
                $title = "Resend OTP Email";
                $content = "This is resend Email.";
                $otp = generateKey(6, 6, false, false);
                $otp_url =  url("/customer/verifyOTP?otp={$otp}");
                $OTPTime = date('Y-m-d h:i:s', strtotime("+15 minutes", strtotime(now())));

                try 
                {
                    Mail::send('emails.registration_email', ['name' => $username, 'email' => $email, 'title' => $title, 'content' => $content, 'otp' => $otp, 'otp_url' => $otp_url], function ($message) use ($email) {
                        $message->to($email)->subject('Resend OTP Request!');
                    });
                } 
                catch (\Exception $e) 
                {
                    throw new GeneralException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR); 
                }

                $email_user->status = 'inactive';
                $email_user->otp_pin = $otp;
                $email_user->otp_datetime = $OTPTime;
                $email_user->save();

                return $this->success(trans('auth.CHECK_OTP'), []);
            }
            else
            {
                throw new GeneralException(trans('auth.OTP_ALREADY_VERIFIED'));
            }
        }
        else
        {
            throw new UserNotFoundException(trans('auth.USER_NOT_FOUND_EMAIL'));
        }
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
