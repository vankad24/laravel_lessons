<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

// Роут для отображения формы (GET-запрос)
Route::get('/', [DataController::class, 'showForm'])->name('data.form');

// Роут для обработки отправки формы (POST-запрос)
Route::post('/save-data', [DataController::class, 'saveData'])->name('data.save');

// Роут для отображения всех сохраненных данных в таблице (GET-запрос)
Route::get('/data-table', [DataController::class, 'showTable'])->name('data.table');
