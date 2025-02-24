<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

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

Route::get('/user-detail', function () {
    $user = new User();
    dd($user);
});

Route::get('/allUser-detail', function () {
    $user = new User();
    $allUser = $user::all();
    dd($allUser);
});