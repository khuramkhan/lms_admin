<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[HomeController::class,'index']);
Route::post('/',[HomeController::class,'loginProcess']);

Route::middleware('isLogin')->group(function(){
    Route::get('users',[UserController::class,'index']);
    Route::get('course/add',[CourseController::class,'addCourse']);
    Route::post('course/add',[CourseController::class,'addCourse']);


    Route::get('courses',[CourseController::class,"index"]);
    Route::prefix('course')->as('course.')->group(function(){
        // Route::post('topic/save',[CourseController::class,'addTopic']);
        Route::prefix('/{courseID}/topic')->group(function(){
            Route::get('/add',[CourseController::class,'addTopic'])->name('addTopic');
            Route::post('/add',[CourseController::class,'addTopic']);
            Route::get('/addQuiz',[CourseController::class,"addQuiz"])->name('addQuiz');
            Route::post('/addQuiz',[CourseController::class,"addQuiz"])->name('addQuiz.save');
            // Route::match(['get','post'], '/addQuiz',[CourseController::class,'addQuiz']);
        });
    });
    Route::get('aboutUs',[HomeController::class,'aboutUs'])->name('about-us');
    Route::post('aboutUs',[HomeController::class,'aboutUs'])->name('about-us');
    Route::get('contactUs',[HomeController::class,'contactUs'])->name('contact-us');
    Route::post('contactUs',[HomeController::class,'contactUs'])->name('contact-us');
    Route::get('/logout',[HomeController::class,'logout']);
});
