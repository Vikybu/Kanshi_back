<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MachineController;

Route::post('/login', [LoginController::class, 'authenticate']);

Route::post('/machine/create', [MachineController::class, 'addAMachine']);
