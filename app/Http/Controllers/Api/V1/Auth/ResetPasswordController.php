<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UserExceptions\UserNotFoundException;
use App\Http\Requests\AuthRequests\ResetPasswordRequest;
use App\Http\Requests\AuthRequests\ForgotPasswordRequest;
use App\Traits\ApiResponse;

class ResetPasswordController extends Controller
{
    use ApiResponse;
    
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */


    public function changePassword(ForgotPasswordRequest $request)
    {
        $user = auth()->user();

        if (!Hash::check($request['old_password'], $user->password)) {
            throw new GeneralException(trans('auth.INVALID_CURRENT_PASSWORD')); 
        }

        if (Hash::check($request['password'], $user->password)) {
            throw new GeneralException(trans('auth.SAME_CURRENT_NEW_PASSWORD')); 
        }

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->password_changed_at = Carbon::now();
        $user->save();

        return $this->success(trans('auth.PASSWORD_RESET'), []);
    }
    
    public function resetPassword(ResetPasswordRequest $request)
    {
        $email = $request->email;
        $pin = $request->otp;
        $password = $request->password;

        if($user = User::getUserByEmail($email)->first())
        {
            // if($user->otp_pin == $pin && $user->otp_datetime >= Carbon::now())
            if($user->otp_pin == $pin)
            {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->otp_pin = rand(10, 999999);
                $user->password_changed_at = Carbon::now();
                $user->otp_datetime = Carbon::now()->subMinutes(30);
                $user->save();
                
                return $this->success(trans('auth.PASSWORD_RESET'), []);
            }
            else
            {
                throw new GeneralException(trans('auth.INVALID_TOKEN'));
            }
        }
        else
        {
            throw new UserNotFoundException(trans('auth.USER_NOT_FOUND_EMAIL'));
        }
    }
}
