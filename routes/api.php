<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});


Route::resource('category',CategoryController::class); 

Route::resource('post',PostController::class); 
Route::get('/personal_posts',[PostController::class,'personal_posts']);

Route::controller(CommentController::class)->group(function () {
    Route::post('comment/{id}', 'add');
    Route::put('comment/{id}', 'update');
    Route::delete('comment/{id}', 'destroy');
});





