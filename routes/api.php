<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth teacher
Route::prefix('/teacher/')->group(function () {
    Route::post('login', 'App\Http\Controllers\Teacher\TeacherAuthController@login');
    Route::post('register', 'App\Http\Controllers\Teacher\TeacherAuthController@register');
});

//Auth Student
Route::post('/login', 'App\Http\Controllers\StudentAuthController@login');
Route::post('/register', 'App\Http\Controllers\StudentAuthController@register');

//teachers routes
Route::group(['middleware' => ['auth:teachers,api_teachers']], function () {
    Route::post('/teacher/add-course/{teacher_id}', 'App\Http\Controllers\Teacher\TeacherController@addCourse');
});

//students routes
Route::group(['middleware' => ['auth:users,api_users']], function () {
    Route::get('/courses', 'App\Http\Controllers\CourseController@index');
    Route::post('/subscribe', 'App\Http\Controllers\CourseController@subscribe');
});
