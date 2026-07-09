<?php

use App\Http\Controllers\Api\RiskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user = Auth::user();
    $user->load('roles.permissions'); // Eager load roles and permissions

    return $user;
});

Route::middleware(['auth:sanctum', 'permission:view risks'])->group(function () {
    Route::apiResource('risks', RiskController::class);
});
