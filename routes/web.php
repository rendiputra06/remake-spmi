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

Route::redirect('/login', '/admin/login')->name('login');

// Routes untuk survei publik
Route::get('/surveys', [App\Http\Controllers\SurveyController::class, 'index'])->name('surveys.index');
Route::get('/surveys/{survey}', [App\Http\Controllers\SurveyController::class, 'show'])->name('surveys.show');
Route::post('/surveys/{survey}/submit', [App\Http\Controllers\SurveyController::class, 'submit'])->name('surveys.submit');
Route::get('/surveys/{survey}/thankyou', [App\Http\Controllers\SurveyController::class, 'thankYou'])->name('surveys.thank-you');
Route::get('/surveys/{survey}/report', [App\Http\Controllers\SurveyController::class, 'report'])->name('surveys.report');
Route::get('/surveys/{survey}/analytics', [App\Http\Controllers\SurveyAnalyticsController::class, 'show'])->name('surveys.analytics');
Route::get('/surveys/{survey}/export-excel', [App\Http\Controllers\SurveyController::class, 'exportExcel'])->name('surveys.export-excel');

// Survey Analytics Routes
Route::get('survey-analytics/{id}', [App\Http\Controllers\SurveyAnalyticsController::class, 'show'])->name('survey-analytics.show');
Route::get('survey-analytics/{id}/export-excel', [App\Http\Controllers\SurveyAnalyticsController::class, 'exportExcel'])->name('survey-analytics.export-excel');
Route::get('survey-analytics/{id}/export-pdf', [App\Http\Controllers\SurveyAnalyticsController::class, 'exportPdf'])->name('survey-analytics.export-pdf');

// Survey Management Routes
Route::get('admin/resources/surveys/{record}/publish', [\App\Http\Controllers\SurveyManagementController::class, 'publish'])
    ->middleware(['auth'])
    ->name('filament.admin.resources.surveys.publish');
Route::get('admin/resources/surveys/{record}/close', [\App\Http\Controllers\SurveyManagementController::class, 'close'])
    ->middleware(['auth'])
    ->name('filament.admin.resources.surveys.close');

// Routes for Audit Report Distribution
Route::get('audits/{audit}/distribute', [App\Http\Controllers\AuditReportController::class, 'showDistributionForm'])->name('audits.distribute.form');
Route::post('audits/{audit}/distribute', [App\Http\Controllers\AuditReportController::class, 'distribute'])->name('audits.distribute');
Route::get('audits/{audit}/report-pdf', [App\Http\Controllers\AuditReportController::class, 'generatePDF'])->name('audits.report.pdf');
