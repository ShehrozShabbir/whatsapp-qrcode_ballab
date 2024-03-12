<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QrCode;
use App\Http\Controllers\LoginController;

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
})->name('login');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout/session', [LoginController::class, 'logoutSession'])->name('logoutSession');
Route::post('/login-user', [LoginController::class, 'login'])->name('login');


Route::get('/qr-code/{qr_key}', [QrCode::class, 'index'])->name('qrcode.index')->middleware('auth');
Route::post('/update-qrcode', [QrCode::class, 'qrcode'])->name('qrcode.update')->middleware('auth');


