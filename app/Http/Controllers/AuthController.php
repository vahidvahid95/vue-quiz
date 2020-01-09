<?php

namespace App\Http\Controllers;

use App\User;
use GuzzleHttp\Exception\BadResponseException;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AuthController extends Controller
{

    use ThrottlesLogins;

    public function username()
    {
        return 'username';
    }

    public function login(Request $request){
        $h = new Client;
        try{
            $response = $h->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->username,
                    'password' => $request->password,
                    'scope' => '',
                ],
            ]);
            $this->clearLoginAttempts($request);
            return $response->getBody();
        }catch (BadResponseException $e){
            if($e->getCode() === 400){

                if ($this->hasTooManyLoginAttempts($request)) {
                    $this->fireLockoutEvent($request);
                    $this->sendLockoutResponse($request);
//                    response()->json('kheili dari talash mikoni', 429);
                }
                $this->incrementLoginAttempts($request);
                return response()->json('user o pass dastan dare.', $e->getCode());
            }
            return response()->json('DB Connection Problem', $e->getCode());
        }

    }


    public function logout(){
        auth()->user()->tokens->each(function ($token, $key){
            $token->revoke();
        });
        return response()->json('logged out', 200);
    }
}
