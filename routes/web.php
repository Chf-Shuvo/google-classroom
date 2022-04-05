<?php

use App\Http\Controllers\GoogleController;
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

Route::controller(GoogleController::class)->group(function () {
    Route::get("/", "auth_initiate");
    Route::get("auth/google/callback", "auth_callback");
    Route::get("course-details/{courseID}", "course_details");
    Route::get("course/{courseID}/students", "enrolled_students");
    Route::get("course/{courseID}/grades", "course_grades");
});
