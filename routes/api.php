<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::post('registration', 'register');
    Route::get('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
    Route::put('{hashed_id}', 'activateProfile');
});
