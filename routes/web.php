<?php

use App\Http\Controllers\Controller;
use App\Models\User;
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
    Auth::login(User::find(2));
    //return request()->user();
    return view('welcome');
});

Route::get('test', [Controller::class, 'test']);

//Route::fallback(fn() => abort(404));