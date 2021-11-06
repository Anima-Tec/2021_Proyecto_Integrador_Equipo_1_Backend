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
  // routes for person
  Route::get('/person/{id}', [PersonController::class, 'show']);
  Route::patch('/person/{id}', [PersonController::class, 'update']);
  Route::delete('/person/{id}', [PersonController::class, 'delete']);
  Route::patch('/person/report', [PersonController::class, 'report']);

  // routes for reports
  Route::get('/place/reports/{address}', [ReportController::class, 'indexPlace']);
  Route::get('/person/reports/{id}', [ReportController::class, 'indexPerson']);
  Route::get('/reports', [ReportController::class, 'index']);
  Route::get('/report/{id}', [ReportController::class, 'show']);
  Route::post('/report', [ReportController::class, 'store']);
  Route::patch('/report/{id}', [ReportController::class, 'update']);
  Route::delete('/report/{id}', [ReportController::class, 'delete']);

  // routes for admin
  Route::get('/admin/view-reports', [ReportController::class, 'indexAdmin']);
  Route::patch('/admin/report/{id}', [AdminController::class, 'save']);
  Route::delete('/admin/report/{id}', [AdminController::class, 'delete']);

  // routes for place
  Route::get('/place/{address}', [PlaceController::class, 'show']);

  Route::post('/logout', [AuthController::class, 'logout']);
});
