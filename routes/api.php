<?php

use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TicketStatisticsController;
use Illuminate\Support\Facades\Route;

Route::post('/tickets', [TicketController::class, 'store']);
Route::get('/tickets/statistics', TicketStatisticsController::class);
