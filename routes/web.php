<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('customers', [CustomerController::class, 'index'])->middleware(['auth'])->name('customers.index');
Route::resource('customers', CustomerController::class)->middleware(['auth']);

Route::get('employees', [EmployeeController::class, 'index'])->middleware(['auth'])->name('employees.index');
Route::resource('employees', EmployeeController::class)->middleware(['auth']);

require __DIR__.'/auth.php';
