<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;

Route::apiResource('users', UserController::class);
Route::get('addresses', [AddressController::class, 'index']);