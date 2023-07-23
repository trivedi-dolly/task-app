<?php

use App\Http\Controllers\UserController;
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

Route::get('/', [UserController::class,'getEducations'])->name('index');
Route::get('/get-records', [UserController::class,'getRecords']);
Route::post('submit',[UserController::class,'postDetails'])->name('submit_user');
Route::get('edit/{id}',[UserController::class,'edit']);
Route::post('/update/{id}',[UserController::class,'update'])->name('update_user');
Route::get('delete/{id}',[UserController::class,'deleteData']);
Route::get('/search',[UserController::class,'search']);