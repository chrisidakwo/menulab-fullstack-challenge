<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', [UserController::class, 'index'])->name('index');
Route::get('/{user}/weather', [UserController::class, 'getUserWeatherDetails'])->name('detail');


// - Location
//- Temperature (day and night) whichever is the current should be highlighted (also show high and low if possible)
//- Precipitation
//- Humidity
//(The higher the humidity the greater the water vapour, and the more rain we're likely to see)
// Humidity of > 70% forms mist, and > 90% rains
//- Wind
//- Visibility

//- Summary (Mostly Sunny)
