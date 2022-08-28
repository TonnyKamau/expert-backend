<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



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
Route::get('/clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('optimize:clear');
    Artisan::call('storage:link');
    return "Cleared!";

 });
//  Route::get('/eblade', function() {
//     return view('emails.toWriter');
//  });


// Route::get('/sendmail', [MailController::class, 'sendMail']);
// Route::get('auth', [UserController::class, 'ifAuth']);
// Route::get('/authrole', [UserController::class, 'ifAuthrole']);
// Route::get('/getbalance', [PriceController::class, 'getbalance']);
// Route::get( '/price',[FrontController::class, 'priceGenerator'])->name('price');

// Route::get( '/allFormats',[PriceController::class, 'formats'])->name('formats');
// Route::get( '/allLevels',[PriceController::class, 'levels'])->name('levels');
// Route::get( '/allTypes',[PriceController::class, 'types'])->name('types');

//test route
Route::get( '/quit',[FrontController::class, 'logOut']);

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/order',[App\Http\Controllers\TaskController::class, 'store'])->name('order');
// Route::get('/all-orders', [App\Http\Controllers\TaskController::class, 'show'])->name('order-list');
// Route::get('/payment', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment');
// Route::POST('/process', [App\Http\Controllers\PaymentController::class, 'paymentAction'])->name('payment_process');
// Route::POST('/user-registration', [App\Http\Controllers\UserController::class, 'store'])->name('register-user');



// Google login
// Route::get('login/google', [App\Http\Controllers\UserController::class, 'redirectToGoogle'])->name('login.google');
// Route::get('login/google/callback', [App\Http\Controllers\UserController::class, 'handleGoogleCallback']);

// Facebook login
// Route::get('login/facebook', [App\Http\Controllers\UserController::class, 'redirectToFacebook'])->name('login.facebook');
// Route::get('login/facebook/callback', [App\Http\Controllers\UserController::class, 'handleFacebookCallback']);

Route::get('/auth/{driver}/redirect', [App\Http\Controllers\UserController::class, 'redirectToProvider'])->name('social.oauth');
Route::get('/auth/{driver}/callback', [App\Http\Controllers\UserController::class, 'handleProviderCallback'])->name('social.callback');
