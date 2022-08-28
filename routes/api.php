<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WriterController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MoneyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// free routes
Route::POST('/registerStudent', [UserController::class, 'store']);
Route::get('auth', [UserController::class, 'ifAuth']);
Route::POST('/price', [FrontController::class, 'priceGenerator'])->name('price');
Route::get('/allFormats', [PriceController::class, 'formats'])->name('formats');
Route::get('/allLevels', [PriceController::class, 'levels'])->name('levels');
Route::get('/allTypes', [PriceController::class, 'types'])->name('types');
Route::POST('auth/callback', [UserController::class, 'handleGoogleCallback']);
Route::get('/thetypes', [PriceController::class, 'combined']);

// =============================protected routes =================================
// if user is auth auth
Route::middleware(['Checker'])->group(function(){
    Route::get('myinfo', [UserController::class, 'getUinfo']);
    Route::POST('saveprofile', [UserController::class, 'saveUProfile']);
    Route::get('authrole', [UserController::class, 'ifAuthrole']);
    Route::POST('/mailAssignment', [MailController::class, 'sendMail']);
    // if user is auth auth
    // student panel
    Route::get('/dashboardcount', [TaskController::class, 'clientCounter']);
    Route::POST('/order', [TaskController::class, 'store']);
    Route::POST('/updatetask/{id}', [TaskController::class, 'update']);
    Route::put('/rate', [TaskController::class, 'rateWork']);
    Route::get('/taskinfo/{id}', [TaskController::class, 'viewTask']);
    Route::get('/admintaskinfo/{id}', [TaskController::class, 'adminviewTask']);
    Route::get('/viewdoc/{id}', [TaskController::class, 'getDoc']);
    Route::get('/admindoc/{id}', [TaskController::class, 'adminDoc']);
    Route::get('/deleteTask/{id}', [TaskController::class, 'deleter']);
    // Route::get('/malipo', [TaskController::class, 'getDoc']);

    Route::get('/tasks', [TaskController::class, 'show'])->name('order-list');
    Route::get('/tasksFiltered', [TaskController::class, 'showfiltered']);
    Route::get('/stuTasks', [TaskController::class, 'adminShow']);
    Route::get('/stuTasksFiltered', [TaskController::class, 'adminShowByDate']);
    Route::get('/payment', [PaymentController::class, 'show'])->name('payment');
    Route::POST('/process', [PaymentController::class, 'paymentAction'])->name('payment_process');
    // Route::POST('/order', [App\Http\Controllers\TaskController::class, 'store'])->middleware('auth:sanctum');
    // Google login

    //payment
    Route::POST('/capturePayment', [PayPalController::class, 'capture'])->name('capturePayment');
    Route::get('/getbalance', [PriceController::class, 'getbalance']);
    Route::get('/payplan', [PriceController::class, 'getexpence']);
    //chatroutes
    Route::POST('/savetext', [ChatController::class, 'store']);
    Route::get('/fetchStudentNotification', [ChatController::class, 'fetchstudent']);
    Route::get('/fetchcurrentsms', [ChatController::class, 'fetchCurrent']);
    Route::get('/resetNotiStu', [ChatController::class, 'resetStudent']);
    Route::POST('/resetCurrent/{roomid}', [ChatController::class, 'resetCurrent']);
    Route::get('/chatUser', [ChatController::class, 'getChat']);
    //adminRoutes
    Route::get('/writers', [UserController::class, 'writers']);
    Route::get('/admindashboard', [TaskController::class, 'adminCounter']);
    Route::get('/supportdashboard', [TaskController::class, 'supportCounter']);
    Route::POST('/updateuser', [UserController::class, 'upUserDetails']);
    //destroy
    Route::POST('/deleteUser/{id}', [UserController::class, 'destroyUser']);
    // Route::get('/taskInfo/{id}', [WriterController::class, 'taskDetails']);
    Route::POST('/uploadDone', [TaskController::class, 'upFile']);
    Route::get('/clientList', [UserController::class, 'getusers']);
    Route::get('/clientListSearched/{searchID}', [UserController::class, 'getusersFiltered']);
    Route::POST('/registerStaff', [UserController::class, 'addstaff']);
    Route::get('/staff', [UserController::class, 'getstaffs']);
    Route::get('/getstaffinfo/{userid}', [UserController::class, 'info']);
    Route::get('/clients', [UserController::class, 'getClients']);
    Route::get('/getSp/{id}', [UserController::class, 'supportData']);
    Route::get('/getWrt/{id}', [WriterController::class, 'writerData']);
    Route::get('/getClnt/{id}', [UserController::class, 'clientData']);
    Route::POST('/updateSupport', [UserController::class, 'changeSupport']);
    Route::POST('/updateWriter', [WriterController::class, 'changeWriter']);
    Route::POST('/updateClient', [UserController::class, 'changeClient']);
    //save payments
    Route::POST('/payments', [MoneyController::class, 'savePayment']);
    Route::get('/finance', [MoneyController::class, 'getHistory']);
    Route::get('/transactions', [MoneyController::class, 'myHistory']);
    //jobs routes
    Route::get('/assigningmail', [MailController::class, 'sendMail']);
    // pricing routes
    Route::POST('/createPricing', [FrontController::class, 'create']);
    Route::get('/pricingcriteria', [FrontController::class, 'getall']);
    Route::get('/frontview', [FrontController::class, 'getallfronts']);
    Route::POST('/saveCombination', [FrontController::class, 'saveFront']);
    Route::get('/getbyid/{id}', [FrontController::class, 'getDetails']);
    Route::POST('/editCombination', [FrontController::class, 'editDetails']);
    Route::POST('/deleteData/{id}', [FrontController::class, 'destroyRecord']);
    Route::POST('/deletechooser', [FrontController::class, 'destroyChooser']);
    // email
    Route::POST('/multiemail', [MailController::class, 'sendMulti']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
