<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/sign_in', 'signIn')->name('sign_in');
    Route::get('/logout', 'logout')->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->controller(UserController::class)->group(function () {
    Route::get('/tasks', 'tasks')->name('tasks');
});

Route::get('/', function () {
    return view('welcome');
});
