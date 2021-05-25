<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
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

Route::get('/', [EventController::class, 'index'])->name('index');
Route::get('/events/create', [EventController::class, 'create'])->middleware('auth')->name('events.create');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::delete('/events/{id}', [EventController::class, 'destroy'])->middleware('auth')->name('events.destroy');
route::get('/events/edit/{id}', [EventController::class, 'edit'])->middleware('auth')->name('events.edit');
route::put('/events/{id]', [EventController::class, 'update'])->middleware('auth')->name('events.update');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
