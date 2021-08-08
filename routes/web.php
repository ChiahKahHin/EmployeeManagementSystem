<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'store']);

Route::get('/logout', [LogoutController::class, 'index'])->name('logout');

Route::get('/adminDashboard', [AdminDashboardController::class, 'index'])->name('adminDashboard');

Route::get('/addAdmin', [AdminController::class, 'addAdminForm'])->name('addAdmin');
Route::post('/addAdmin', [AdminController::class, 'addAdmin']);
Route::get('/manageAdmin', [AdminController::class, 'manageAdmin'])->name('manageAdmin');