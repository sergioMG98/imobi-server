<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\MailController;
use App\Mail\MyEmail;
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

Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/addProduct', [DashboardController::class , 'addProduct']);
    Route::get('/getProductSeller', [DashboardController::class , 'getProductSeller']);
    Route::post('/updateProduct', [DashboardController::class , 'updateProduct']);
    Route::get('/getMessage', [DashboardController::class, 'getMessage']);
    Route::get('/getProfil', [DashboardController::class, 'getProfil']);
    Route::post('/updateProfil', [DashboardController::class, 'updateProfil']);
    Route::get('/getAllCustomer', [DashboardController::class, 'getAllCustomer']);

    Route::post('/newEvent' , [CalendarController::class, 'addEvents']);
    Route::get('/allEvents' , [CalendarController::class, 'getEvents']);
    Route::post('/updateEvent' , [CalendarController::class, 'updateEvents']);
    Route::post('/deleteEvent', [CalendarController::class, 'deleteEvents']);

    Route::post('/getProductOfCustomer', [ClientController::class, 'getProductOfCustomer']);
    
    Route::get('/logout' , [UserController::class , 'logout']);
    Route::get('/deleteUser', [DashboardController::class, 'deleteUser']);
    
});

Route::post('/register', [UserController::class , 'create_user']);
Route::post('/login' , [UserController::class , 'login']);

Route::get('/getUsers',[UserController::class , 'someInformationSeller']);

Route::post('/setMessage' , [MessageController::class , 'setMessage']);

Route::post('getProductById', [ProductController::class, 'getProductById']); 

Route::get('getProduct', [ProductController::class, 'getProduct']);
Route::post('getProductSpecific', [ProductController::class, 'getProductSpecific']);
// reinitialisation mot de passe
Route::post('/verifyExist', [MailController::class, 'store']);
Route::post('/resetPasswordLink', [MailController::class, 'resetPassword']);