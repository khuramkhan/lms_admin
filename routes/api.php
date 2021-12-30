<?php

use App\Http\Controllers\Auth\UserController;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register',[UserController::class,'register']);
Route::post('/confirm-otp',[UserController::class,'confirmOtp']);
Route::post('/login',[UserController::class,'login']);
Route::post('/forgot-password',[UserController::class,'forgotPassword']);

Route::middleware('auth:api')->group( function () {
    Route::get('courses',function(){
        return Course::all();
    });
});

