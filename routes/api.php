<?php

use App\Http\Controllers\hosp\AuthController;
use App\Http\Controllers\hosp\HospController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('user.login');
Route::middleware('auth:sanctum')->post('/hosp/event', [HospController::class, 'store']);
Route::middleware('auth:sanctum')->get('/event-details', [HospController::class, 'getEventData']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hosp', function () {
    return response()->json([
        ['id' => 1, 'title' => 'Software Engineer'],
        ['id' => 2, 'title' => 'Product Manager'],
    ]);
});