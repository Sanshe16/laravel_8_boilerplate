<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\UserExceptions\UserNotFoundException;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Requests\AuthRequests\ForgotPasswordRequest;
use App\Traits\ApiResponse;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails, ApiResponse;

    public function forgotPassword(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
        ]);
        
        $email = $request->email;

        if($email_user = User::getUserByEmail($email)->first())
        {
            $username = $email_user->username;
            $title = "Forgot Password";
            $content = "This is Forgot Password Email.";
            $otp = rand(100000, 999999);
            $otp_url =  url("/customer/resetPassword?pin={$otp}&email={$email}");
            $OTPTime = date('Y-m-d h:i:s', strtotime("+15 minutes", strtotime(now())));
            try 
            {
                Mail::send('emails.forgot_email', ['name' => $username, 'email' => $email, 'title' => $title, 'content' => $content, 'otp' => $otp, 'otp_url' => $otp_url], function ($message) use ($email) 
                {
                    $message->to($email)->subject('Forgot Password Request!');
                });
            }
            catch (\Exception $e) 
            {
                throw new GeneralException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR); 
            }

            $email_user->otp_pin = $otp;
            $email_user->otp_datetime = $OTPTime;
            $email_user->save();

            return $this->success(trans('auth.CHECK_PASSWORD_RESET_EMAIL'), ['email' => $email]);
        }
        else
        {
            throw new UserNotFoundException(trans('auth.USER_NOT_FOUND_EMAIL'));
        }

    }
}
