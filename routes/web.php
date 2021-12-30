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
Route::get('users',[UserController::class,'index']);
Route::get('course/add',[CourseController::class,'addCourse']);
Route::post('course/add',[CourseController::class,'addCourse']);


Route::get('courses',[CourseController::class,"index"]);
Route::prefix('course')->group(function(){
    Route::post('topic/save',[CourseController::class,'addTopic']);
});

