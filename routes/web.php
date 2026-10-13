<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/upload', [ItemController::class, 'create'])->name('items.create');
Route::post('/upload', [ItemController::class, 'store'])->name('items.store');
