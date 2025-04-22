<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Routes untuk survei publik
Route::get('/surveys', [App\Http\Controllers\SurveyController::class, 'index'])->name('surveys.index');
Route::get('/surveys/{id}', [App\Http\Controllers\SurveyController::class, 'show'])->name('surveys.show');
Route::post('/surveys/{id}/submit', [App\Http\Controllers\SurveyController::class, 'submit'])->name('surveys.submit');
Route::get('/surveys/{id}/thank-you', [App\Http\Controllers\SurveyController::class, 'thankYou'])->name('surveys.thank-you');

// Survey Analytics Routes
Route::get('survey-analytics/{id}', [App\Http\Controllers\SurveyAnalyticsController::class, 'show'])->name('survey-analytics.show');
Route::get('survey-analytics/{id}/export-excel', [App\Http\Controllers\SurveyAnalyticsController::class, 'exportExcel'])->name('survey-analytics.export-excel');
Route::get('survey-analytics/{id}/export-pdf', [App\Http\Controllers\SurveyAnalyticsController::class, 'exportPdf'])->name('survey-analytics.export-pdf');
