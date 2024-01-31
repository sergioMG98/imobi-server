<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientController;

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

Route::get('/getUsers',[UserController::class , 'someInformationSeller']);

// a mettre sur midleware
Route::post('/addProduct', [DashboardController::class , 'addProduct']);
Route::get('/getProductSeller', [DashboardController::class , 'getProductSeller']);
Route::post('/updateProduct', [DashboardController::class , 'updateProduct']);

Route::get('getProduct', [ProductController::class, 'getProduct']);
Route::post('getProductSpecific', [ProductController::class, 'getProductSpecific']);
Route::post('getProductById', [ProductController::class, 'getProductById']);

Route::post('/newEvent' , [CalendarController::class, 'addEvents']);
Route::get('/allEvents' , [CalendarController::class, 'getEvents']);
Route::post('/updateEvent' , [CalendarController::class, 'updateEvents']);
Route::post('/deleteEvent', [CalendarController::class, 'deleteEvents']);

Route::get('/getAllCustomer', [ClientController::class, 'getAllCustomer']);
Route::post('/getProductOfCustomer', [ClientController::class, 'getProductOfCustomer']);