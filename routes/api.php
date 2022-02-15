<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\TaskController;

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);
Route::get('', [ApiController::class, 'welcome']);


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('tasks', [TaskController::class, 'tasks']);
    Route::get('get_user', [TaskController::class, 'get_user']);
    Route::get('tasks/{id}', [TaskController::class, 'show']);
    Route::post('create', [TaskController::class, 'create']);
    Route::post('update/{task}',  [TaskController::class, 'update']);
    Route::delete('delete/{task}',  [TaskController::class, 'destroy']);
});
