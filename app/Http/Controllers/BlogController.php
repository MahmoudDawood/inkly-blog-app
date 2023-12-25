<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class BlogController extends Controller
{
    public function index() {
        $posts = Post::latest()->get();
        return view('blogPosts.blog', compact('posts')); // compact creates array from variable names
        // Before '.' is the parent directory
    }

    // Using Route Model Binding as it receives the corresponding post of the passed slug
    public function show(Post $post) {
        // $post = Post::where('slug', $slug)->first(); // (Without route model binding)
        return view('blogPosts.post', compact('post'));
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
