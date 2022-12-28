<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}/vacancies', [EventController::class, 'getPlaces'])->name('event.places');
Route::get('/events/', [EventController::class, 'getItems'])->name('events.items');

Route::post('/reservation/reserve', [ReservationController::class, 'reserve'])->name('reservation.reserve');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
