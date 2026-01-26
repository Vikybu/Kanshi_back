<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\FinalProductController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\UserProductionOrderController;
use App\Http\Controllers\DowntimeReasonController;
use App\Http\Controllers\DowntimeReasonMachineController;
use App\Http\Controllers\DowntimeReasonProductionOrderController;

Route::post('/login', [LoginController::class, 'authenticate']);

Route::post('/machine/create', [MachineController::class, 'addAMachine']);

Route::get('/admin/machine', [MachineController::class, 'getInfosAllMachines']);

Route::get('/admin/raw_material', [RawMaterialController::class, 'getAllRawMaterial']);

Route::get('/admin/final_product', [FinalProductController::class, 'getAllFinalProduct']);

Route::get('/admin/productionOrder/get', [ProductionOrderController::class, 'getInfosAllProductionOrder']);

Route::get('/user/production/get/{id}', [ProductionOrderController::class, 'getInfosOneProductionOrder']);

Route::post('/admin/productionOrder/simuler', [CalculationController::class, 'calculation']);

Route::post('/admin/productionOrder/create', [ProductionOrderController::class, 'addANewProductionOrder']);

Route::post('/admin/productionOrder/checkConflict', [ProductionOrderController::class, 'checkConflict']);

Route::put('/user/fo/modify', [ProductionOrderController::class, 'addRealStartTime']);

Route::put('/user/fo/modify-quantity', [ProductionOrderController::class, 'addQuantityProduct']);

Route::post('/user/production-order/active', [UserProductionOrderController::class, 'getActiveProductionOrder']);

Route::get('/user/downtime-reason/', [DowntimeReasonController::class, 'getTypeDowntimeReason']);

Route::get('/user/downtime-reason/{type}', [DowntimeReasonController::class, 'getDowntimeReason']);

Route::post('/user/downtime-reason/create', [DowntimeReasonMachineController::class, 'addDowntimeReasonMachine']);

Route::post('/user/downtime-reason/add', [DowntimeReasonProductionOrderController::class, 'addDowntimeReasonProductionOrder']);


