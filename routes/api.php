<?php

use App\Http\Controllers\Api\OTPController;
use App\Http\Controllers\UserController;
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
Route::post('otp/shoot', [OTPController::class, 'shoot']);
Route::post('otp/validate', [OTPController::class, 'checkValidity']);
Route::post('register', [UserController::class, 'registration'])
    ->name('register');
Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function (){
    Route::get('user-details', [UserController::class, 'show']);

    Route::group(['prefix' => 'quiz'], function () {
//        Route::get('/dashboard', [\App\Http\Controllers\UserController::class, 'dashboard']);
//        Route::get('/point-history', [\App\Http\Controllers\UserController::class, 'point_history']);
//        Route::get('/quiz/history', [\App\Http\Controllers\UserController::class, 'history']);

//        Route::get('/courses', [\App\Http\Controllers\Api\MasterMechanicCourseController::class, 'index']);
//
//        Route::get('/questions', [\App\Http\Controllers\Api\MasterMechanicCourseLevelQuestionController::class, 'index']);
//        Route::post('/submit/answer', [\App\Http\Controllers\Api\MasterMechanicCourseLevelQuestionController::class, 'submitAnswer']);
//
//        Route::post('/video/seen', [\App\Http\Controllers\Api\MasterMechanicCourseQuizAttemptController::class, 'videoSeen']);
//        Route::get('/video', [\App\Http\Controllers\Api\MasterMechanicCourseQuizAttemptController::class, 'index']);
//
//        Route::get('/leaderboard', [\App\Http\Controllers\Api\MasterMechanicRewardHistoryController::class, 'index']);
//        Route::get('/reward/history', [\App\Http\Controllers\Api\MasterMechanicRewardHistoryController::class, 'rewardHistory']);
//
//        Route::get('/check/retailer', [\App\Http\Controllers\Api\UserController::class, 'checkRetailer']);
    });
});
