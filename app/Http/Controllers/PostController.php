<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post as Post;
use App\Profile as Profile;
use Auth;

class PostController extends Controller
{
    /**
     * Set Auth for this Contorller
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index()
    {
        // Pull all of the posts associated with a specific user and sort by newest
        $posts = Post::where('user_id', Auth::user()->id)
                ->orderBy('created_at', 'DESC')
                ->get();

        $profile = Profile::where('user_id', Auth::user()->id)->first();
        // return the view while passing the posts array
    	return view('home', compact('posts', 'profile'));
    }

    function create()
    {
        // Pass current posts
        $posts = Post::where('user_id', Auth::user()->id)
                ->orderBy('created_at', 'DESC')
                ->get();
        // return the view while passing the posts array
        return view('post.create', compact('posts'));
    }

    function edit(Post $post){
        // Pass current posts
        $posts = Post::where('user_id', Auth::user()->id)
                ->orderBy('created_at', 'DESC')
                ->get();
        // return the view while passing the posts array
        return view('post.update', compact('post','posts'));
    }


    function update(Post $post){
        if(Auth::user()->id != $post->user_id)
          exit('Sneaky Batchi');

        // Validate that everything havs been submitted
        $validator = $this->validate(request(), [
            'title'     => 'required',
            'body'      => 'required'
        ]);

        $post->title = request('title');
        $post->body = request('body');
        $post->is_public = request()->has('public');

        $post->save();

        return redirect('/home');
    }

    public function store()
    {
        // Validate that everything havs been submitted
        $validator = $this->validate(request(), [
            'title'     => 'required',
            'body'      => 'required'
        ]);

        // If validation passes, create new post and insert into the DB
        $post = new Post([
            'user_id'       => Auth::user()->id,
            'title'         => request('title'),
            'body'          => request('body'),
            'is_public'     => request()->has('public')
        ]);

        $post->save();

        return redirect('/home');
    }

    protected function delete(Post $post)
    {
        // validate that the user is correct
        if( auth()->user()->id != $post->user_id ):
            return back();
        endif;

        // delete the post
        $post->delete();
        return back();
    }

}
