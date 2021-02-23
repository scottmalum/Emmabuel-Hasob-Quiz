<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResultController;

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

Route::group([
    'prefix' => 'auth'
], function ($router) {

    Route::post('register', [RegisterController::class, 'registerUser']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

//Route available to both admin and user
Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('/questions', [QuizController::class, 'index']);
});

//Routes available for admin
Route::group(['middleware' => ['jwt.verify', 'admin']], function () {
    Route::post('/add-question', [QuizController::class, 'store']);
    Route::post('/delete-question/{questionId}', [QuizController::class, 'destroy']);
});


//Result Proccessing Route accessible to users only
Route::group(['middleware' => ['jwt.verify', 'user']], function () {
    Route::post('/submit-quiz', [ResultController::class, 'store']);
});
