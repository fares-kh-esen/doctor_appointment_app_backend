<?php

use App\Http\Controllers\GroomersController;
use App\Models\Appointments;
use Illuminate\Support\Carbon;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
});
Route::get('dashboard', [GroomersController::class, 'index'])->name('dashboard');
Route::get('test', function(){
    // return Carbon::now();
    return  $appointment = Appointments::whereDate('date', '=' ,Carbon::today()->toDateString())->first();;
});

