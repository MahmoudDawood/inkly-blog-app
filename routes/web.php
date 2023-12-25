<?php

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
Route::get('/blog/post', [BlogController::class, 'show'])->name('blog.show');

// About page (Using closure -anonymous function- for simple logic)
Route::get('/about', function () {
    return view('about');
})->name('about');

// Contact Page
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');