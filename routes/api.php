<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExercisesController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckStudentLimit;


Route::middleware('auth:sanctum')->group(function () {
    // rotas privadas
    Route::get('/dashboard', [DashboardController::class,'index']);

    Route::post('/exercises', [ExercisesController::class,'store']);
    Route::get('/exercises', [ExercisesController::class,'index']);
    Route::delete('exercises/{id}', [ExercisesController::class,'destroy']);

    Route::post('/student', [StudentController::class,'store'])->middleware(CheckStudentLimit::class);
    Route::get('/student', [StudentController::class, 'index']);
    Route::delete('/student/{id}', [StudentController::class,'destroy']);
    Route::put('/student/{id}', [StudentController::class,'update']);
    Route::get('/student/{id}', [StudentController::class,'show']);
    Route::get('/students/{id}/workouts', [StudentController::class,'indexByStudent']);
    Route::get('/students/export', [StudentController::class,'exportPdfWorkout']);

    Route::post('/workouts', [WorkoutController::class, 'store']);

});

//rotas publicas

Route::post('/users',[UserController::class,'store']);

Route::post('/login', [LoginController::class, 'login']);

