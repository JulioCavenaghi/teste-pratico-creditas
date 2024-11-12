<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SimulacaoCreditoController;
use Illuminate\Support\Facades\Route;

Route::post('/simularcredito', [SimulacaoCreditoController::class, 'simularCredito']);

