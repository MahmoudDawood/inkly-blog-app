<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create-category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // TODO: restrict categories creation
        $request->validate([
            'name' => 'required | unique:categories', // Specifying the table it's unique across
        ]);

        $name = $request->input('name');

        $category = new Category();
        $category->name = $name;
        $category->save();

        return redirect()->back()->with('status', 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}