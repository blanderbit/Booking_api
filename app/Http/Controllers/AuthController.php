<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Profile;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        $user = new User([
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();

        $profile = new Profile([
            "nickname" => $user->nickname,
            "email" => $user->email,
            "user_id" => $user->id,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at,
        ]);
        $profile->save();

//        if ($request->ajax() || $request->wantsJson()) {
//            $user->sendEmailVerificationNotification();
//        }

        return response()->json([
            'message' => 'Successfully created user!',
            'profile' => $profile
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)){
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me){
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        return response()->json([
             'message'=> 'you are logged in',
             'user_id' => $user->id,
             'inf_token' => [
                 'access_token' => $tokenResult->accessToken,
                 'token_type' => 'Bearer',
                 'expires_at' => Carbon::parse($tokenResult
                     ->token
                     ->expires_at)
             ->toDateTimeString()]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
