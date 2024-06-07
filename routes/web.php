<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ProsesController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::post('/store-rekor', [HomeController::class, 'store'])->name('store-rekor');
    Route::get('/search-rekor', [HomeController::class, 'search'])->name('search-rekor');
    Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
    Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
    Route::get('/get-rekaman', [UploadController::class, 'getRekaman'])->name('get-rekaman');
    Route::post('/olah/{id}', [UploadController::class, 'olah'])->name('olah');
    Route::post('/process-data', [ProsesController::class, 'process'])->name('data.process');
    Route::get('/hasil', [ProsesController::class, 'showResults'])->name('data.hasil');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
