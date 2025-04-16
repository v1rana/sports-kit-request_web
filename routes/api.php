<?php

use App\Http\Controllers\Job\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('job.login');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hosp', function () {
    return response()->json([
        ['id' => 1, 'title' => 'Software Engineer'],
        ['id' => 2, 'title' => 'Product Manager'],
    ]);
});