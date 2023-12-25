<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// To Welcome page
Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');

// To Blog page
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show'); // Show post
    // The passed object is sent to path, laravel by default uses it's id -overwritten to slug-
    // Controller receives the post which include this slug ((Route Model Binding))
Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create'); // Create post form
Route::post('/blog', [BlogController::class, 'store'])->name('blog.store'); // Store post


// About page (Using closure -anonymous function- for simple logic)
Route::get('/about', function () {
    return view('about');
})->name('about');

// Contact Page
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
