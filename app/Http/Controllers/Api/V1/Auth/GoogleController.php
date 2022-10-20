<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;

class GoogleController extends Controller
{
    use ApiResponse;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {

            $google_user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $google_user->id)->first();

            if($finduser)
            {
                return $this->accountStatus($finduser, $google_user);
            }
            else
            {
                if($finduser = User::where('email', $google_user->email)->first())
                {
                    return $this->accountStatus($finduser, $google_user);
                }
                else
                {
                    do
                    {
                        $username = strtolower(str_replace(" ", "-", $google_user->name)) . '.' . mt_rand(10000000, 99999999);
                    } 
                    while(User::where('name', $username)->first()); //check if the name already exists and if it does, try again

                    $newUser = User::create([
                        'first_name' => $google_user->name,
                        'name' => $username,
                        'email' => $google_user->email,
                        'google_id'=> $google_user->id,
                        'password' => Hash::make(generateKey(8,10))
                    ]);
    
                    $newUser->roles()->attach(3);
    
                    $this->authAttempt($newUser);
                }
            }
        } 
        catch (\Exception $e) 
        {
            return $this->createJson($e->getMessage(), $e->getCode(), false, []);
        }
    }

    private function accountStatus($finduser, $facebook_user)
    {
        if($token = $this->token($finduser->email))
        {
            $token->delete();
        }

        $finduser->status = 'active';
        
        if(is_null($finduser->email_verified_at))
        {
            $finduser->email_verified_at = Carbon::now();
        }

        $finduser->facebook_id =  $facebook_user->id;
        $finduser->save();

        $this->authAttempt($finduser);
    }

    private function authAttempt($finduser)
    {
        if(Auth::login($finduser))
        {
            //store ip, lat, long
            saveUserGeoIpData();
            $tokenResult = auth()->user()->createToken('ApiUserAuthAccessToken');

            return $this->success(trans("auth.FACEBOOK_LOGIN"), [
                'accessToken' => $tokenResult->accessToken,
                'tokenType'   => 'Bearer',
                'expiresAt'   => Carbon::parse($tokenResult->token->expires_at)->toIso8601String(),
                'user' => $finduser,
            ]);
        }
        else
        {
            throw new GeneralException(trans('auth.UNABLE_TO_LOGIN'), 500);
        }
    }
}


