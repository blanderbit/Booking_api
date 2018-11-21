<?php

namespace App\Http\Controllers;
use App\Post;
use App\Comment;
use App\Rent;
use App\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function getPosts(Request $request)
    {
        $old_posts = Post::all();

        return response()->json([$old_posts]);
    }


    public function getPost(Request $request, $id)
    {
        $post = Post::with(['rent', 'comment'])->find($id);
        unset($post->comment_count);
        unset($post->rent_count);
//        $rent = $post->rent;
//        $comment = Post::with('comment');
//        if($post == null || count($post) == 0){
//            return response()->json([
//                'message' => 'No such post',
//            ]);
//        }
//        dd($post);
        return response()->json([$post],200);
//        compact('post', 'reviews', 'rents')

    }
    public function addPost(Request $request)
    {
        $request->validate([
            'office_name' => 'required',
            'address' => 'required',
        ]);

//        $user = \Auth::user();

        $post = new Post();
        $post->user_id = $request->get('user_id');
        $post->office_name = $request->get('office_name');
        $post->about_office = $request->get('about_office');
        $post->address = $request->get('address');

        $post->save();
        return response()->json([
            'message' => 'Successfully created post!',
            'project' => $post
        ],200);
    }
    public function updatePost(Request $request, $id)
    {
        $post = Post::find($id);
        if($post == null ){
            return response()->json([
                'message' => 'No such post',
            ]);
        }
        if($post->user_id != $request->get('user_id')){
            return response()->json([
                'message' => "You don't update post",
            ]);
        }

        $post->user_id = $request->get('user_id');
        $post->office_name = $request->get('office_name');
        $post->about_office = $request->get('about_office');
        $post->address = $request->get('address');
        $post->update();
        return response()->json([
            'message' => 'Successfully updated post!',
            "post" => $post
        ], 200);
    }
    public function removePost(Request $request, $id )
    {

        $post = Post::find($id);
        if($post == null ){
            return response()->json([
                'message' => 'No such post',
            ]);
        }
        if($post->user_id != $request->get('user_id')){
            return response()->json([
                'message' => "You don't update post",
            ]);
        }
        $reviews = Comment::where('post_id', $id);
        $rents = Rent::where('post_id', $id);
        $reviews->delete();
        $rents->delete();
        $post->delete();
        return response()->json([
            'message' => 'Successfully remove post with all data!',
        ], 200);
    }
}
