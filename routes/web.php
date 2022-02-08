<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
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
    Route::prefix('user')->group(function(){
        Route::post('/action',[UserController::class,'userAction'])->name('user.action');
    });



    Route::get('courses',[CourseController::class,"index"]);
    Route::prefix('course')->as('course.')->group(function(){

        Route::get('add',[CourseController::class,'addCourse']);
        Route::post('add',[CourseController::class,'addCourse']);
        Route::get('edit/{id}',[CourseController::class,'editCourse'])->name('edit');
        Route::post('edit/{id}',[CourseController::class,'editCourse'])->name('edit');
        Route::get('view/{id}',[CourseController::class,'viewCourse'])->name('view');

        Route::prefix('/{courseID}/topic')->group(function(){
            Route::get('/add',[CourseController::class,'addTopic'])->name('addTopic');
            Route::post('/add',[CourseController::class,'addTopic']);
            Route::get('/addQuiz',[CourseController::class,"addQuiz"])->name('addQuiz');
            Route::post('/addQuiz',[CourseController::class,"addQuiz"])->name('addQuiz.save');
        });
    });


    Route::prefix('topic')->as('topic.')->group(function(){
        Route::get('{id}/edit',[CourseController::class,'editTopic'])->name('edit');
        Route::post('{id}/edit',[CourseController::class,'editTopic']);
    });

    Route::get('aboutUs',[HomeController::class,'aboutUs'])->name('about-us');
    Route::post('aboutUs',[HomeController::class,'aboutUs'])->name('about-us');
    Route::get('faqs',[HomeController::class,'faqs'])->name('faqs');
    Route::post('faqs',[HomeController::class,'faqs'])->name('faqs');
    Route::get('contactUs',[HomeController::class,'contactUs'])->name('contact-us');
    Route::post('contactUs',[HomeController::class,'contactUs'])->name('contact-us');
    Route::get('/logout',[HomeController::class,'logout']);

    // Route::prefix('settings')->group(function(){
    //     Route::get('stripe',[SettingController::class,'stripe']);
    // });
});
