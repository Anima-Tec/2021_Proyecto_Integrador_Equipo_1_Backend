<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::get('/person/{id}', [PersonController::class, 'show']);
  Route::put('/person/{id}', [PersonController::class, 'update']);
  Route::delete('/person/{id}', [PersonController::class, 'delete']);
  // Route::get('/get/persons', [PersonController::class, 'getAllPersons']);

  Route::get('/places', [PlaceController::class, 'getPlaces']);
  Route::post('/place', [PlaceController::class, 'createPlace']);
  // Route::get('/get/place/{id}', [PlaceController::class, 'getPlace']);
  // Route::post('/update/place/{id}', [PlaceController::class, 'updatePlace']);

  Route::get('/reports', [ReportController::class, 'getAllReports']);
  Route::get('/report/{id}', [ReportController::class, 'getReport']);
  Route::post('/report', [ReportController::class, 'createReport']);
  Route::put('/report/{id}', [ReportController::class, 'updateReport']);
  Route::delete('/report/{id}', [ReportController::class, 'deleteReport']);

  Route::put('/admin/report/{id}', [AdminController::class, 'save']);
  Route::delete('/admin/report/{id}', [AdminController::class, 'delete']);

  Route::post('/logout', [AuthController::class, 'logout']);
});
