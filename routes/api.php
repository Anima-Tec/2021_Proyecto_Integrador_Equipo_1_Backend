<?php

use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;

Route::get('/persons', [PersonController::class, 'getAllPersons']);
Route::get('/person/{id}', [PersonController::class, 'getPerson']);
Route::post('/create', [PersonController::class, 'createPerson']);
Route::post('/update/{id}', [PersonController::class, 'updatePerson']);
Route::get('/delete/{id}', [PersonController::class, 'deletePerson']);