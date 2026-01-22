<?php

use App\Http\Controllers\Api\ReservaApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->post('/reservations', [ReservaApiController::class, 'store']);

// Route::post('/reservations', function () {
//     return response()->json(['ok' => true]);
// });