<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ForbiddenGetParamsMiddleware;

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
Route::get('/', StaticController::class)->middleware(ForbiddenGetParamsMiddleware::class);

Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/sign_in', 'signIn')->name('sign_in');
    Route::get('/logout', 'logout')->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', function () { return redirect(route('admin.tasks')); })->name('home');

    Route::get('/tasks/{slug?}/{subSlug?}', [UserController::class,'tasks']);
    Route::post('/task', 'UserController@editTask');
    Route::post('/delete-task', 'UserController@deleteTask');

    Route::get('/sub_task/{slug?}', [UserController::class,'subTask']);
    Route::post('/sub_task', [UserController::class,'editSubTask']);
    Route::post('/delete-sub-task', [UserController::class,'deleteSubTask']);
});
