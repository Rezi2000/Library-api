<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/store',[BookController::class,'store']);

Route::post('/add_author/book/{id}',[BookController::class,'add_author']);

Route::post('/add_book/author/{id}',[BookController::class,'add_book']);



Route::get('/books',[BookController::class,'index_books']);
Route::get('/book/{id}',[BookController::class,'show_authors']);

Route::get('/authors',[BookController::class,'index_authors']);
Route::get('/author/{id}',[BookController::class,'show_books']);

Route::get('/search/{key}',[BookController::class,'search']);

Route::delete('book/{id}',[BookController::class,'destroy_book']);
Route::delete('author/{id}',[BookController::class,'destroy_author']);


Route::put('book/{id}',[BookController::class,'update']);
