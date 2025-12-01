<?php

use App\Http\Controllers\DemoScrapeController;
use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });




// Optional: scrape and save data (can be triggered manually)
Route::get('/scraper', [ScraperController::class, 'scraper'])->name('scraper');

// Show the scraped testimonials
Route::get('/testimonials', [ScraperController::class, 'showTestimonials'])->name('testimonials');


Route::get('/', [DemoScrapeController::class, 'scrape'])->name('demo.scrape');
Route::get('/toscrape', [DemoScrapeController::class, 'index'])->name('demo.index');


// Quick CSV  export file

Route::get('/demo-export-csv', [DemoScrapeController::class, 'exportCsv'])->name('demo.export.csv');

