<?php

namespace App\Http\Controllers;
use App\Profile;
use App\User;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProfilsController extends Controller
{

    public function getProfile (Request $request, $id)
    {

        $profile = Profile::find($id)
             ->getOriginal();
        return response()
             ->json($profile, 200);
    }


    public function removeProfile (Request $request, $id)
    {
        $user = Auth::user();
        $profile = Profile::find($id);
        $profile->delete();
        $user->delete();
        $request->user()->token()->revoke();
        return response()->json([
            "message" => "User delete successfully"
        ],200);

    }

    public function updateProfile (Request $request, $id)
    {
        $user = Auth::user();
        $old_profile = Profile::find($user->id);

        $request->validate([
            'email' => 'string|email|required',
            'password' => 'string|required',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Wrong password'
            ], 401);
        }
        $pattern = '/^\+380\d{3}\d{2}\d{2}\d{2}$/';

        if($request->get('number') != null){
            $number_pattern = preg_match($pattern, $request->get('number'));
            if($number_pattern == 0){
                return response()->json([
                    "message" => 'Wrong number'
                ]);
            }
        }

        $data = $request->only(['nickname', 'email', 'password']);
        $data['password'] = bcrypt($data['password']);

        if ( $user->update($data) ) {
            $user->profile->name= $request->name ?? $old_profile->name;
            $user->profile->number= $request->number ?? $old_profile->number ;
            $user->profile->surname= $request->surname ?? $old_profile->surname ;
            $user->profile->last_name= $request->last_name ?? $old_profile->last_name ;
            $user->profile->email= $request->email == null ?? $old_profile->email ;
            $user->profile->save();
            return response()->json([
                'message' => 'Successfully updated user!',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Something went wrong on update user',
                'user' => $user
            ], 500);
        }
    }
}
