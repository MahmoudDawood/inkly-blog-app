<?php

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BlogController;
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
Route::get('/', [WelcomeController::class, 'index']);

// To Blog page
Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/post', [BlogController::class, 'show']);

// About page
Route::get('/about', function () {
    return view('about');
});


// Contact Page
Route::get('/contact', function () {
    return view('contact');
});