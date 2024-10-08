<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::post('/inventory', [InventoryController::class, 'store']);
Route::get('/inventory', [InventoryController::class, 'list']);
Route::put('/inventory/{id}', [InventoryController::class, 'update']);
Route::get('/inventory-not-final-phase', [InventoryController::class, 'listNotFinalPhaseStatus']);
