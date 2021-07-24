<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::resource('/person', PersonController::class);

Route::resource('/place', PlaceController::class);

Route::resource('/report', ReportController::class);
