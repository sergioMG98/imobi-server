<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

Route::post('/register', [UserController::class , 'create_user']);
Route::post('/login' , [UserController::class , 'login']);

// a mettre sur midleware
Route::post('/addProduct', [ProductController::class , 'addProduct']);
Route::get('getProduct', [ProductController::class, 'getProduct']);
Route::post('getProductSpecific', [ProductController::class, 'getProductSpecific']);