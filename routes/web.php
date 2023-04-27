<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Models\Siswa;
use App\Http\Controllers\PembayaranController;
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

Route::inertia('/', 'Test', ['title' => 'Dashboard'])->middleware('auth');
Route::inertia('/login', 'Auth/Login')->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/pembayaran', [PembayaranController::class, 'pembayaran'])->middleware('auth');
Route::get('/pembayaran/history', [PembayaranController::class, 'history'])->middleware('auth');
Route::post('/pembayaran/bayar', [PembayaranController::class, 'bayar'])->middleware('auth');
Route::resource('/siswa', SiswaController::class)->middleware('auth');
Route::post('/siswa/delete', [SiswaController::class, 'delete'])->middleware('auth');
// Route::get('/siswa', [SiswaController::class, 'index'])->middleware('auth');
// Route::inertia('/siswa', 'Siswa/Index', ['data' => Siswa::with('kelas')->get()->paginate(10)])->middleware('auth');
