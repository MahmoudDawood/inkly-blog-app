<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class BlogController extends Controller
{
    public function index() {
        return view('blogPosts.blog');
    }

    public function show() {
        return view('blogPosts.post');
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
        $slug = Str::slug($title, '-'); // To lower case and joins with second parameter.
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
}
