<?php

namespace App\Http\Controllers;
use App\User;
use App\Comment;
use App\Rent;
use App\Post;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class ProjectController extends Controller
{
    public function getPosts(Request $request, $id)
    {
        $old_posts = Post::all();
        $posts = [];
        $count = count($old_posts);
        for ($i = 0; $i < $count; $i++){
            $data = $old_posts[$i];
            $reviews = Comment::where('post_id', $old_posts[$i]->id)->get();
            $data->reviews = count($reviews);
            array_push($posts, $data);
        }
        return response()->json([
            'data' => $posts
        ], 200);
    }
    public function getPost(Request $request, $id)
    {
        $post = Post::where('id', $id)->get();
        if($post == null){
            return response()->json([
                'message' => 'No such list',
            ]);
        }
        $reviews = Comment::where('post_id', $id)->get();
        $rents = Rent::where('post_id', $id)->get();
        $post->reviews = $reviews;
        $post->rents = $rents;
        return response()->json([
            'post' => $post
        ],200);
    }
    public function addPost(Request $request, $id)
    {
        $request->validate([
            'office_name' => 'required',
            'address' => 'required',
        ]);

        $post = new Post();
        $post->user_id = $request->get('user_id');
        $post->project_name = $request->get('office_name');
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
        $post = Post::where('id', $request->get('$id'))->get();
        if($post->user_id != $request->get('user_id')){
            return response()->json([
                'message' => "You don't update post",
            ]);
        }

        if($post == null){
            return response()->json([
                'message' => 'No such post',
            ]);
        }
        $post->user_id = $request->get('user_id');
        $post->project_name = $request->get('office_name');
        $post->about_office = $request->get('about_office');
        $post->address = $request->get('address');
        $post->update();
        return response()->json([
            'message' => 'Successfully updated post!',
            "post" => $post
        ], 200);
    }
    public function removePost(Request $request, $id ,$id_project)
    {
        $post = Post::where('id', $request->get('$id'))->get();
        if($post->user_id != $request->get('user_id')){
            return response()->json([
                'message' => "You don't update post",
            ]);
        }

        if($post == null){
            return response()->json([
                'message' => 'No such post',
            ]);
        }

        $reviews = Comment::where('post_id', $id)->get();
        $rents = Rent::where('post_id', $id)->get();
        $reviews->delete();
        $rents->delete();
        $post->delete();
        return response()->json([
            'message' => 'Successfully remove post with all data!',
        ], 200);
    }

}
