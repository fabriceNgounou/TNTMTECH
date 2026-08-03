<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/services', [PublicController::class, 'services'])->name('services.index');
Route::get('/services/{service}', [PublicController::class, 'service'])->name('services.show');
Route::get('/formations', [PublicController::class, 'trainings'])->name('trainings.index');
Route::get('/formations/{training}', [PublicController::class, 'training'])->name('trainings.show');
Route::post('/formations/{training}/inscription', [PublicController::class, 'registerTraining'])->name('trainings.register')->middleware('throttle:5,1');
Route::get('/carrieres', [PublicController::class, 'jobs'])->name('jobs.index');
Route::get('/carrieres/{job}', [PublicController::class, 'job'])->name('jobs.show');
Route::post('/carrieres/{job?}/candidature', [PublicController::class, 'apply'])->name('jobs.apply')->middleware('throttle:5,1');
Route::get('/devis', [PublicController::class, 'quote'])->name('quote');
Route::post('/devis', [PublicController::class, 'storeQuote'])->name('quote.store')->middleware('throttle:5,1');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'storeContact'])->name('contact.store')->middleware('throttle:5,1');
Route::get('/informations', [PublicController::class, 'information'])->name('information');
Route::get('/informations/{page}', [PublicController::class, 'page'])->name('page');

Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login.store')->middleware('throttle:5,1');
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

Route::prefix('administration')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('services', ServiceController::class)->except('show');
    Route::resource('quotes', QuoteController::class)->only(['index', 'show', 'update']);
});
