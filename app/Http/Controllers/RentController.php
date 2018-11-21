<?php

namespace App\Http\Controllers;
use App\Post;
use App\Comment;
use App\Rent;
use App\User;
use Illuminate\Http\Request;

class RentController extends Controller
{

    public function addRent(Request $request, $id)
    {
        $request->validate([
            'color' => 'required',
            'date' => 'required',
            'user_id' => 'required',
        ]);

        $rent = new Rent();
        $rent->color = $request->get('color');
        $rent->date = $request->get('date');
        $rent->post_id = $id;
        $rent->user_id = $request->get('user_id');

        $rent->save();
        return response()->json([
            'message' => 'Successfully created rent!',
            'project' => $rent
        ],200);
    }
    public function removeRent(Request $request, $id )
    {

        $rent = Rent::where('post_id',$id)
            ->where('user_id',$request->get('user_id'))
            ->get();

        if($rent == null || count($rent) == 0){
            return response()->json([
                'message' => 'No such rent',
            ]);
        }
        $rent->first()->delete();
        return response()->json([
            'message' => 'Successfully remove rent!',
        ], 200);
    }
}
