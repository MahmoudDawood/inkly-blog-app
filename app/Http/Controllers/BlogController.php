<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'image' => 'required | image', // Has to be both (AND)
            'body' => 'required'
        ]);
    }
}
