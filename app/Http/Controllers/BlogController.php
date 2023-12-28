<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class BlogController extends Controller
{
    public function __construct() {
        return $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request) {
        if($request->search) { 
            $posts = Post::where('title', 'like', '%' . $request->search . '%')
                ->orWhere('body', 'like', '%' . $request->search . '%')->latest()->paginate(4);
        } elseif ($request->category) {
            $posts = Category::where('name', $request->category)->firstOrFail()->posts()
                ->paginate(4)->withQueryString();
        } else {
            $posts = Post::latest()->paginate(4);
        }

        $categories = Category::latest()->get();
        return view('blogPosts.blog', compact('posts', 'categories')); // compact creates array from variable names
        // Before '.' is the parent directory in views
    }

    public function show(Post $post) { // Post is received from passed slug by Route Model Biding
        // $post = Post::where('slug', $slug)->first(); // (Without route model binding)
        $category = $post->category;
        $relatedPosts = $category->posts()->where('id', '!=', $post->id)
            ->latest()->take(3)->get();
        return view('blogPosts.post', compact('post', 'relatedPosts'));
    }

    public function edit(Post $post) { // using route model binding
        // Authorize user for action
        if(auth()->user()->id !== $post->user->id) {
            abort(403);
        }
        return view('blogPosts.edit-blog-post', compact('post'));
    }

    public function create() {
        $categories = Category::latest()->get();
        return view('blogPosts.create-blog-post', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'image' => 'required | image', // Has to satisfy both (AND)
            'body' => 'required',
            'category_id' => 'required' // custom validator in /lang/en/validation.php
        ]);

        $title = $request->input('title');
        $category_id = $request->input('category_id');

        if(Post::latest()->first() !== null) {
            $postId = Post::latest()->first()->id + 1;
        }
        else {
            $postId = 1;
        }
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
        $post->category_id = $category_id;
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

    public function destroy(Post $post) {
        $post->delete();
        return redirect()->back()->with('status', 'Post Deleted Successfully');
    }
}
