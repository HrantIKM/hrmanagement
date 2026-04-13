<?php

use App\Http\Controllers\Website\CareerController;
use App\Http\Controllers\Website\HomeController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes(['register' => false, 'reset' => false]);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{vacancy}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/apply', [CareerController::class, 'apply'])->name('careers.apply');
