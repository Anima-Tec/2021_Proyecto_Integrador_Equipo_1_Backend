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
  Route::get('/get/persons', [PersonController::class, 'getAllPersons']);
  Route::get('/get/person/{id}', [PersonController::class, 'getPerson']);
  Route::post('/update/person/{id}', [PersonController::class, 'updatePerson']);
  Route::get('/delete/person/{id}', [PersonController::class, 'deletePerson']);

  Route::get('/get/places', [PlaceController::class, 'getPlaces']);
  Route::get('/get/place/{id}', [PlaceController::class, 'getPlace']);
  Route::post('/create/place', [PlaceController::class, 'createPlace']);
  Route::post('/update/place/{id}', [PlaceController::class, 'updatePlace']);

  Route::get('/get/reports', [ReportController::class, 'getAllReports']);
  Route::get('/get/report/{id}', [ReportController::class, 'getReport']);
  Route::post('/create/report', [ReportController::class, 'createReport']);
  Route::post('/update/{id}', [ReportController::class, 'updateReport']);
  Route::get('/delete/report/{id}', [ReportController::class, 'deleteReport']);

  Route::get('/admin/save/{id}', [AdminController::class, 'save']);
  Route::get('/admin/delete/{id}', [AdminController::class, 'delete']);

  Route::post('/logout', [AuthController::class, 'logout']);
});
