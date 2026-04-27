<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
}); //->middleware('auth:sanctum');

Route::post('survey', [App\Http\Controllers\SurveyController::class, 'store']);
Route::get('survey', [App\Http\Controllers\SurveyController::class, 'index']);
