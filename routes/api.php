<?php

use App\Http\Controllers\Admin\CourseController;
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
Route::get('/aboutUs',[UserController::class,"aboutUs"]);
Route::get('/faqs',[UserController::class,"faqs"]);
Route::post("/stripePost", CourseController::class, "stripePost");
Route::middleware('auth:api')->group( function () {
    Route::get('courses',[CourseController::class,'courses']);
    Route::prefix('course')->group(function(){
        Route::post('/purchase',[CourseController::class,'purchaseCourse']);
    });
    Route::prefix('user')->group(function(){
        Route::post('wishListCourses',[CourseController::class,'userWishListCourses']);
        Route::post('wishListCourse/add',[CourseController::class,'addUserWishListCourses']);
        Route::post('contactUs',[UserController::class,'contactUs']);
        Route::post('changePassword',[UserController::class,'changePassword']);
        Route::post('purchaseHistory',[CourseController::class,'purchaseHistory']);
        Route::post('subscriptions',[CourseController::class,'subscriptions']);
        Route::post('updateProfile',[UserController::class,'updateProfile']);
    });

});

