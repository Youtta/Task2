<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Profile CRUD------------------
Route::get('/profile/datatable', [ProfileController::class,'datatable'])->name('profile.datatable');
Route::get('/profiles', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
Route::post('/profile/store', [ProfileController::class, 'store'])->name('profile.store');
Route::get('/profile/{profile}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/networks/{profile}/update', [ProfileController::class, 'update'])->name('profile.update');
Route::patch('/profile/status', [ProfileController::class, 'status'])->name('profile.status');
Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');



// Stripe Payment---------------
Route::post('/charge', [CheckoutController::class, 'charge'])->name('charge');


