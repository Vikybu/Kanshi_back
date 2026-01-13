<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\FinalProductController;
use App\Http\Controllers\CalculationController;

Route::post('/login', [LoginController::class, 'authenticate']);

Route::post('/machine/create', [MachineController::class, 'addAMachine']);

Route::get('/admin/machine', [MachineController::class, 'getInfosAllMachines']);

Route::get('/admin/raw_material', [RawMaterialController::class, 'getAllRawMaterial']);

Route::get('/admin/final_product', [FinalProductController::class, 'getAllFinalProduct']);

Route::post('/admin/productionOrder/simuler', [CalculationController::class, 'calculation']);
