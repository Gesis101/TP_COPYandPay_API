<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|zww
*/

Route::get('/submitPayment', 'App\Http\Controllers\PaymentController@submitPayment');

Route::get('/api/PaymentHistory','App\Http\Controllers\PaymentController@showUserHistory');

Route::get('/', function () {
    return view('welcome', [
        'auth_user' => Auth::user()
    ]);
});
Route::get('/home', function () {
    return view('welcome', [
        'auth_user' => Auth::user()
    ]);
});

Auth::routes();



