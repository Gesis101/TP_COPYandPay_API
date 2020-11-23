<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
Auth::routes();
Route::get('{any}', function () {
    return view('welcome', [
        'auth_user' => Auth::user()
    ]);
})->where('any', '.*');



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





