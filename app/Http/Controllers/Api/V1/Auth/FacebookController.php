<?php


namespace App\Http\Controllers\Api\V1\Auth;

use App\Exceptions\GeneralException;
use Exception;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;

class FacebookController extends Controller
{
    use ApiResponse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        try {

            $facebook_user = Socialite::driver('facebook')->user();

            $finduser = User::where('facebook_id', $facebook_user->id)->first();

            if($finduser)
            {                
                if(Auth::login($finduser))
                {
                    return $this->accountStatus($finduser, $facebook_user);
                }
            }
            else
            {
                if( $finduser = User::where('facebook_id', $facebook_user->id)->first())
                {
                    return $this->accountStatus($finduser, $facebook_user);
                }
                else
                {
                    do
                    {
                        $username = strtolower(str_replace(" ", "-", $facebook_user->name)) . '.' . mt_rand(10000000, 99999999);
                    } while(User::where('name', $username)->first()); //check if the name already exists and if it does, try again

                    $fullname = explode (" ", $facebook_user->name);
                    $first_name = isset($fullname[0]) ? $fullname[0] : null;
                    $last_name = isset($fullname[1]) ? $fullname[1] : null;

                    $newUser = User::create([
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'name' => $username,
                        'email' => $facebook_user->email,
                        'google_id'=> $facebook_user->id,
                        'password' => Hash::make(generateKey(8,10))
                    ]);
    
                    $newUser->roles()->attach(3);
    
                    $this->authAttempt($newUser);
                } 
            }
        } 
        catch (\Exception $e) 
        {
            return $this->createJson($e->getMessage(),$e->getCode(), false, []);
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
