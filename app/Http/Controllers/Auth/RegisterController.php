<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Support\Facades\Validator;
use Flasher\Laravel\FlasherServiceProvider;
use App\Http\Requests\AuthRequests\VendorRegisterRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\AuthRequests\VerifyOTPRequest;

class RegisterController extends Controller
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

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     * 
     */
    protected $redirectTo = '/verify/email';

    protected $ROLE_VENDOR;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->ROLE_VENDOR = config()->get('roles_config.roles.vendor');
    }

   
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function register(VendorRegisterRequest $request)
    {

        DB::beginTransaction();
        
        do
        {
            $username = 'Gifter-'.generateKey(3, 3). '-'.generateKey(3, 3);
        } //check if the name already exists and if it does, try again
        while(User::where('username', $username)->first());

        $email = $request['email'];
        $title = "Registration Email";
        $content = "New Account Register";
        $otp = generateKey(6, 6, false, false);
        do 
        {
            //generate a random string using Laravel's str::random helper
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
        catch(\Exception $e){

            flash('smtp error cannot send message without sender address', 'error');
            return redirect()->back();
        }
        $OTPTime = date('Y-m-d h:i:s', strtotime("+15 minutes", strtotime(now())));

        try
        {
            $newUser =  User::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'username' => $username,
                'email' => $request['email'],
                'vendor_account_type' => $request['vendor_account_type'],
                'password' => Hash::make($request['password']),
                'phone_number' => $request['phone_number'],
                'status' => 'inactive',
                'otp_pin' => $otp,
                'otp_datetime' => $OTPTime,
                 // 'country_id' => $request['country'],
                // 'store_name' => $request['store_name'],

            ]);

            $newUser->roles()->attach(2);
            $token = UserToken::create([
                'email' => $newUser->email,
                'token' => $token,
                'user_id' => $newUser->id,
                'created_at'   =>   Carbon::now(),
                'updated_at'   =>   Carbon::now(),
            ]);
            DB::commit();

        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e);
        }
        flash(trans('auth.CHECK_OTP'), 'success');
        return Redirect::route('verifyEmail')->with(['token'=> $token->token, 'email' => $newUser->email]);
    }


    public function verifyEmail()
    {
        $user = User::where('email', session()->get('email'))->first();
        if($user)
        {
            return view('auth.verify_email',['token'=> session()->get('token'), 'email' => session()->get('email'), 'user' => !is_null($user) ? $user : null]);
        }
        else
        {
            flash(trans('auth.USER_ROUTE_UNAUTHORIZED'), 'error');
            return redirect(route('login'));
        }
    }


    public function verifyOTP(Request $request)
    {
        try
        {
            if(isset($request->email) && isset($request->token))
            {
                $email = $request->email;
                $token = $request->token;

                $user = User::where('email', $email)->first();
                $user_token = UserToken::where('token', $token)->first();

                if(isset($user->email) && $user->email == $email &&  isset($user_token->token) && $user_token->token ==  $token)
                {
                    if(config()->get('roles_config.should_admin_verify_vendor'))
                    {
                        $user->update(array('status' => 'unverified'));
                    }
                    else
                    {
                        $user->update(array('status' => 'active'));
                    }
                
                    $user->update(array('email_verified_at' => Carbon::now()));
                    $user_token->delete();
                    $this->guard()->login($user);
                    return redirect()->route('home');
                }
                else
                {
                    flash(trans('auth.INVALID_TOKEN'), 'error');
                    return redirect(route('login'));
                }
            }
                
            else
            {
                flash(trans('auth.USER_ROUTE_UNAUTHORIZED'), 'error');
                return redirect(route('login'));
            }
        }
        catch(\Exception $e)
        {
            dd($e);
        } 
    }


    public function check_otp(Request $request)
    {
        if (isset($request->email) && isset($request->otp_token))
        {
            $otp = $request->otp;
            if (empty($otp) && is_null($otp))
            {
                $__user = User::where('email', $request->email)->first();
                $_otp_time_handle = null;
                if (!is_null($__user->otp_time) && Carbon::parse($__user->otp_time) <= Carbon::now()) 
                {
                    $__user->otp_time = null;
                    $__user->otp_attempts = 3;
                    $__user->save();
                }
                if (is_null($__user->otp_time)) 
                {
                    $__user->otp_attempts = $__user->otp_attempts - 1;
                    $__user->save();
                    if ($__user->otp_attempts == 0) 
                    {
                        $__user->otp_time = Carbon::now()->addMinutes(30);
                        $__user->otp_attempts = '3';
                        $__user->save();
                        $_otp_time_handle = Carbon::parse($__user->otp_time)->diffInSeconds(Carbon::now());
                    }

                    $message = ['status' => 'otp wrong', 'message' => 'OTP wrong, Please enter valid OTP.', '__user' => $__user, '_otp_time_handle' => $_otp_time_handle];
                    return response()->json($message);
                } 
                else
                {
                    $_otp_time_handle = Carbon::parse($__user->otp_time)->diffInSeconds(Carbon::now());
                    $message = ['status' => 'otp time', 'message' => 'Please wait for '.  Carbon::parse($__user->otp_time)->diffForHumans(null, true), '__user' => $__user, '_otp_time_handle' => $_otp_time_handle];
                    return response()->json($message);
                }
            }
            $check_otp = User::where('otp_pin', $otp)->with('tokens')->first();
            if ($check_otp && $check_otp->status !== 'active' && $check_otp->tokens->count() >0) 
            {
                $check = $check_otp->tokens;
                if ($check['token'] === $request->otp_token) 
                {
                    $user = $check_otp;

                    if(config()->get('roles_config.should_admin_verify_vendor'))
                    {
                        $check_otp = User::where('otp_pin', $otp)->update(array('status' => 'unverified'));
                    }
                    else
                    {
                        $check_otp = User::where('otp_pin', $otp)->update(array('status' => 'active'));
                    }
                    if ($check_otp) 
                    {
                        $token = $this->token($user->email);
                        if (!empty($token) && !is_null($token)) 
                        {
                            $user_otp_reset = User::where('otp_pin', $otp)->with('tokens')->first();
                            //reset otp time and count
                            $user_otp_reset->otp_time = null;
                            $user_otp_reset->otp_resend_time = null;
                            $user_otp_reset->otp_attempts = 3;
                            $user_otp_reset->save();
                            $token->delete();
                        }
                        $user->update(array('email_verified_at' => Carbon::now()));
                        $this->guard()->login($user);
                        $message = ['status' => 'active', 'message' => 'Thank You for activating you email.'];
                        return response()->json($message);
                    }
                }
                else 
                {
                    $message = ['status' => 'otp wrong', 'message' => 'OTP wrong, Please enter valid OTP.'];
                    return response()->json($message);
                }
            }
            else 
            {
                $__user = User::where('email', $request->email)->first();
                $_otp_time_handle = null;
                if (!is_null($__user->otp_time) && Carbon::parse($__user->otp_time) <= Carbon::now()) 
                {
                    $__user->otp_time = null;
                    $__user->otp_attempts = 3;
                    $__user->save();
                }
                if (is_null($__user->otp_time)) 
                {
                    $__user->otp_attempts = $__user->otp_attempts - 1;
                    $__user->save();
                    if ($__user->otp_attempts == 0) 
                    {
                        $__user->otp_time = Carbon::now()->addMinutes(30);
                        $__user->otp_attempts = '3';
                        $__user->save();
                        $_otp_time_handle = Carbon::parse($__user->otp_time)->diffInSeconds(Carbon::now());
                    }

                    $message = ['status' => 'otp wrong', 'message' => 'OTP wrong, Please enter valid OTP.', '__user' => $__user, '_otp_time_handle' => $_otp_time_handle];
                    return response()->json($message);
                } 
                else 
                {
                    $_otp_time_handle = Carbon::parse($__user->otp_time)->diffInSeconds(Carbon::now());
                    $message = ['status' => 'otp time', 'message' => 'Please wait for '.  Carbon::parse($__user->otp_time)->diffForHumans(null, true), '__user' => $__user, '_otp_time_handle' => $_otp_time_handle];
                    return response()->json($message);
                }
            }
        } 
        else
        {
            $message = ['status' => 'no_user', 'message' => 'You are no authorize! Please follow appropriate steps to visit this page.'];
            return response()->json($message);
        }
    }


    public function forgot_password(Request $request)
    {
        $email = $request->email;
        $email_user = User::withoutGlobalScope('account_status')->where('email', $email)->first();
        if (!$email_user) 
        {
            $message = ['status' => 'error', 'message' => 'This email does not exist in our system.'];
            return response()->json($message);
        }
        $name = $email_user->first_name.' '. $email_user->last_name;
        $title = "Forgot Password";
        $content = "This is Forgot Password Email.";
        $otp = rand(10, 999999);
        $otp_url =  url("/reset_password?pin={$otp}");
        $OTPTime = date('Y-m-d h:i:s', strtotime("+15 minutes", strtotime(now())));
        try 
        {
            Mail::send('emails.forgot_email', ['name' => $name, 'email' => $email, 'title' => $title, 'content' => $content, 'otp' => $otp, 'otp_url' => $otp_url], function ($message) use ($email) {
                $message->to($email)->subject('Forgot Password Request!');
            });
        } 
        catch (\Exception $e) 
        {
            dd("test e", $e);
        }

        $email_user->otp_pin = $otp;
        $email_user->otp_datetime = $OTPTime;
        $email_user->save();
        
        $message = ['status' => 'success', 'email'=> $email, 'message' => 'Forgot password Email sent, Please check your email.'];
        return response()->json($message);
    }


    public function save_resend_otp(Request $request)
    {
        $email = $request->email;
        $email_user = User::where('email', $email)->first();
        $name = $email_user->name;
        $title = "Resend OTP Email";
        $content = "This is resend Email.";
        $otp = rand(10, 999999);
        $otp_url =  url("/verify_otp?otp={$otp}");
        $OTPTime = date('Y-m-d h:i:s', strtotime("+15 minutes", strtotime(now())));
        try 
        {
            Mail::send('emails.registration_email', ['name' => $name, 'email' => $email, 'title' => $title, 'content' => $content, 'otp' => $otp, 'otp_url' => $otp_url], function ($message) use ($email) {
                $message->to($email)->subject('Resend OTP Request!');
            });
        } 
        catch (\Exception $e) 
        {
            dd("Email send error", $e);
        }

        $email_user->status = 'inactive';
        $email_user->otp_pin = $otp;
        $email_user->otp_datetime = $OTPTime;
        $email_user->otp_resend_time = Carbon::now()->addSeconds(60);
        $email_user->save();
        $_opt_resend_time_handle =  (!is_null($email_user->otp_resend_time) ? Carbon::parse($email_user->otp_resend_time)->diffInSeconds(Carbon::now()) : '0');
       
        $message = ['status' => 'success', 'message' => 'OTP sent, Please check your email.', 'user' => $email_user, '_opt_resend_time_handle' => $_opt_resend_time_handle];
        return response()->json($message);
    }
    

    public function reset_password(Request $request)
    {
        $email = $request->email;
        $pin = $request->pin;
        return view('auth.reset_password', compact('pin', 'email'));
    }


    public function token($email)
    {
        return UserToken::whereEmail($email)->first();
    }
 

    public function save_reset_password(Request $request)
    {
        if (!isset($request->password)) 
        {
            $message = ['status' => 'error', 'message' => 'Please enter valid Password.'];
            return response()->json($message);
        }
        if (strlen($request->password) < 8) 
        {
            $message = ['status' => 'error', 'message' => 'Please enter Password 8 characters long.'];
            return response()->json($message);
        }

        $pin = $request->pin;
        $password = $request->password;
        $passwordconfirm = $request->passwordconfirm;
        $user = User::where('otp_pin', $pin)->first();
        if (!$user) 
        {
            $message = ['status' => 'error', 'message' => 'Please enter valid email and PIN Code.'];
            return response()->json($message);
        }

        if ($password != $passwordconfirm) 
        {
            $message = ['status' => 'error', 'message' => 'Password does not match.'];
            return response()->json($message);
        }
        $user->password = Hash::make($password);
        $user->save();
        $this->guard()->login($user);
       
        $message = ['status' => 'success', 'message' => 'Your password has been changed.'];
        return response()->json($message);
    }
}
