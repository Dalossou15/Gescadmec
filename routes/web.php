<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentNeedController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

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

// Auth routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');

    // Students Routes
    Route::resource('students', StudentController::class);

    // Inscriptions Routes
    Route::resource('inscriptions', InscriptionController::class);

    // Payments Routes
    Route::resource('payments', PaymentController::class);
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');

    // Student Needs Routes
    Route::resource('needs', StudentNeedController::class);
    Route::patch('/needs/{need}/mark-resolved', [StudentNeedController::class, 'markResolved'])->name('needs.mark-resolved');


    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/balance', [ReportController::class, 'balanceReport'])->name('balance');
        Route::get('/payments-by-level', [ReportController::class, 'paymentsByLevel'])->name('payments-by-level');
        Route::get('/needs', [ReportController::class, 'needsReport'])->name('needs');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');

    });
});