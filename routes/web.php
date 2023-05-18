<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StocksController;

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

Route::get('/', [StocksController::class, 'index'])->name('stocks.index');
Route::post('/stocks', [StocksController::class, 'store'])->name('stocks.store');
Route::get('/quotes', function () {
    return view('quotes');
});
