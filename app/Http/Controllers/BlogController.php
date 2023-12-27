<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class BlogController extends Controller
{
    public function __construct() {
        return $this->middleware('auth')->except(['index', 'show']);
    }

    public function index() {
        $posts = Post::latest()->get();
        return view('blogPosts.blog', compact('posts')); // compact creates array from variable names
        // Before '.' is the parent directory
    }

    public function show(Post $post) { // Post is received from passed slug by Route Model Biding
        // $post = Post::where('slug', $slug)->first(); // (Without route model binding)
        return view('blogPosts.post', compact('post'));
    }

    public function edit(Post $post) { // using route model binding
        // Authorize user for action
        if(auth()->user()->id !== $post->user->id) {
            abort(403);
        }
        return view('blogPosts.edit-blog-post', compact('post'));
    }

    public function create() {
        return view('blogPosts.create-blog-post');
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'image' => 'required | image', // Has to satisfy both (AND)
            'body' => 'required'
        ]);

        $title = $request->input('title');

        $postId = Post::latest()->take(1)->first()->id + 1;
        $slug = Str::slug($title, '-') . '-' . $postId; // To lower case and joins with second parameter.
        // TODO: Check if mariadb only uses id of increments on the last stored it
        $user_id = Auth::user()->id;
        $body = $request->input('body');

        // File upload
        $imagePath = 'storage/' . $request->file('image')->store('postsImages', 'public');
        // store method first parameter is the directory name in `storage/app/public`
        // second parameter is the file system disk from `config/filesystems.php` {local, public, s3}
            // As laravel exposes data in `public`, create a symbolic link to there
        
        $post = new Post();
        $post->title = $title;
        $post->slug = $slug;
        $post->user_id = $user_id;
        $post->body = $body;
        $post->imagePath = $imagePath;
        $post->save();

        return redirect()->back()->with('status', 'Post Created Successfully');
        // with stores key value pair in the session and sends it with the next response
    }

    public function update(Request $request, Post $post) {
        // Authorize user for action
        if(auth()->user()->id !== $post->user->id) {
            abort(403); // Client error -> Forbidden
        }

        $request->validate([
            'title' => 'required',
            'image' => 'required | image', // Has to satisfy both (AND)
            'body' => 'required'
        ]);

        $title = $request->input('title');

        $postId = $post->id;
        $slug = Str::slug($title, '-') . '-' . $postId; // To lower case and joins with second parameter.
        $body = $request->input('body');

        // File upload
        $imagePath = 'storage/' . $request->file('image')->store('postsImages', 'public');
        // store method first parameter is the directory name in `storage/app/public`
        // second parameter is the file system disk from `config/filesystems.php` {local, public, s3}
            // As laravel exposes data in `public`, create a symbolic link to there
        
        $post->title = $title;
        $post->slug = $slug;
        $post->body = $body;
        $post->imagePath = $imagePath;
        $post->save();

        return redirect()->back()->with('status', 'Post Edited Successfully');
        // with stores key value pair in the session and sends it with the next response
    }

    public function delete(Post $post) {
        $post->delete();
        return redirect()->back()->with('status', 'Post Deleted Successfully');
    }
}
