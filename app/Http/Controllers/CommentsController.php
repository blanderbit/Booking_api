<?php

namespace App\Http\Controllers;
use App\Post;
use App\Comment;
use App\Rent;
use App\User;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required',
            'text_rating' => 'required',
            'user_id' => 'required',
        ]);

        $comment = new Comment();
        $comment->rating = $request->get('rating');
        $comment->text_rating = $request->get('text_rating');
        $comment->post_id = $id;
        $comment->date = $request->get('date');
        $comment->user_id = $request->get('user_id');
        $comment->save();
        return response()->json([
            'message' => 'Successfully created comment!',
            'project' => $comment
        ],200);
    }
    public function updateComment(Request $request, $id )
    {
//
//        $comment = Comment::where('post_id',$id)
//            ->where('user_id',$request->get('user_id'))
//            ->get();
//
//        if($comment == null || count($comment) == 0){
//            return response()->json([
//                'message' => 'No such rent',
//            ]);
//        }
//        $comment->first()->delete();
//        return response()->json([
//            'message' => 'Successfully remove rent!',
//        ], 200);
    }
    public function removeComment(Request $request, $id )
    {

        $comment = Comment::where('post_id',$id)
            ->where('user_id',$request->get('user_id'))
            ->get();

        if($comment == null || count($comment) == 0){
            return response()->json([
                'message' => 'No such rent',
            ]);
        }
        $comment->first()->delete();
        return response()->json([
            'message' => 'Successfully remove rent!',
        ], 200);
    }
}
