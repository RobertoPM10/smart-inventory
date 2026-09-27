<?php

use App\Http\Controllers\InventoryWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InventoryWebController::class, 'index']);