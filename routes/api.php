<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskTitleController;
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
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
    Route::get('activation/{hashed_id}', 'activateProfile');
});

Route::prefix('task-titles')->controller(TaskTitleController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('', 'create');
    Route::get('', 'getList');
    Route::get('{title_id}', 'get');
    Route::put('{title_id}', 'update');
    Route::delete('{title_id}', 'delete')->middleware('auth:sanctum');
});


Route::prefix('categories')->middleware('auth:sanctum')->controller(CategoryController::class)->group(function () {
    Route::post('', 'create');
    Route::get('', 'getList');
    Route::get('{category_id}', 'get');
    Route::put('{category_id}', 'update');
    Route::delete('{category_id}', 'delete');
});
