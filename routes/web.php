<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaticController;
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
Route::get('/', StaticController::class)->middleware(['lang']);
Route::get('/change-lang', [StaticController::class, 'changeLang'])->name('change-lang');
Route::get('/show-pdf', [StaticController::class, 'showPdf'])->name('show-pdf');

Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/sign_in', 'signIn')->name('sign_in');
    Route::get('/logout', 'logout')->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware(['auth','lang'])->group(function () {
    Route::get('/', function () { return redirect(route('admin.tasks')); })->name('home');

    Route::post('/seen-all', [UserController::class, 'seenAll']);

    Route::get('/change-lang', [UserController::class, 'changeLang'])->name('change-lang');

    Route::get('/users/{slug?}', [UserController::class, 'users']);
    Route::post('/user', [UserController::class, 'editUser']);
    Route::post('/delete-user', [AdminController::class, 'deleteUser'])->middleware(['admin']);

    Route::get('/tasks/{slug?}/{subSlug?}', [UserController::class,'tasks'])->name('tasks');
    Route::post('/task', [UserController::class, 'editTask']);
    Route::post('/delete-task', [UserController::class, 'deleteTask']);

    Route::get('/sub_task/{slug?}', [UserController::class,'subTask']);
    Route::post('/sub_task', [UserController::class,'editSubTask']);
    Route::post('/delete-sub-task', [UserController::class,'deleteSubTask']);

    Route::get('/messages', [UserController::class, 'messages']);
    Route::post('/delete-message', [UserController::class, 'deleteMessage']);

    Route::get('/customers/{slug?}', [UserController::class, 'customers']);
    Route::post('/customer', [AdminController::class, 'editCustomer'])->middleware(['admin']);
    Route::post('/delete-customer', [AdminController::class, 'deleteCustomer'])->middleware(['admin']);

    Route::get('/banks/{slug?}', [UserController::class, 'banks']);
    Route::post('/bank', [UserController::class, 'editBank']);
    Route::post('/delete-bank', [UserController::class, 'deleteBank']);

    Route::get('/bills/{slug?}', [UserController::class, 'bills']);
    Route::post('/bill', [UserController::class, 'editBill']);
    Route::post('/delete-bill', [UserController::class, 'deleteBill']);

    Route::post('/get-bill-value', [UserController::class, 'getBillsValue']);
    Route::post('/get-convention-number', [UserController::class, 'getConventionNumber']);

    Route::get('/print-doc/{slug}', [UserController::class, 'printDoc']);

    Route::get('/statistics/{slug?}', [AdminController::class, 'statistics'])->middleware(['admin']);

    Route::get('/seo', [AdminController::class, 'seo']);
    Route::post('/seo', [AdminController::class, 'editSeo'])->middleware(['admin']);

    Route::get('/settings', [AdminController::class, 'settings'])->middleware(['admin']);
    Route::post('/settings', [AdminController::class, 'editSettings'])->middleware(['admin']);

    Route::get('/chapters/{slug?}/{subSlug?}', [AdminController::class, 'chapters'])->middleware(['admin']);
    Route::post('/chapter', [AdminController::class, 'editChapter'])->middleware(['admin']);

    Route::post('/work', [AdminController::class, 'editWork'])->middleware(['admin']);
    Route::post('/delete-work', [AdminController::class, 'deleteWork'])->middleware(['admin']);
    Route::post('/delete-pdf', [AdminController::class, 'deletePdf'])->middleware(['admin']);

    Route::get('/sent-emails', [AdminController::class, 'sentEmails'])->middleware(['admin']);
    Route::post('/delete-sent-email', [AdminController::class, 'deleteSentEmail'])->middleware(['admin']);
});
