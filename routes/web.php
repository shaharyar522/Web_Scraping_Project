<?php

use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/scraper',[ScraperController::class, 'scraper'])->name('scraper');
