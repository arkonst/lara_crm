<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
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
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::resource('employee', EmployeeController::class)->middleware('auth');
Route::resource('company', CompanyController::class)->middleware('auth');
Route::post('/company/update/{id}', [CompanyController::class, 'update'])->middleware('auth');

require __DIR__.'/auth.php';
