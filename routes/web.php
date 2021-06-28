<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\Contact_PersonController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\Request_AppController;
use App\Http\Controllers\StatusController;

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

// Route::get('/', function () {
//     return view('home');
// });

Auth::routes(['verify'=> true]);
Route::group(['middleware'=> 'auth'], function(){

    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index']);

    //Citizens
    Route::get('/citizen', [CitizenController::class, 'index']);
    Route::get('citizen/getrecord', [CitizenController::class, 'getrecords']);
    Route::post('citizen/store', [CitizenController::class, 'store']);
    Route::get('citizen/edit/{id}', [CitizenController::class, 'edit']);
    Route::patch('citizen/update/{id}', [CitizenController::class, 'update']);
    Route::get('citizen/destroy/{id}', [CitizenController::class, 'destroy']);
    
    //Contact_Persons
    Route::get('/contact_person', [Contact_PersonController::class, 'index']);
    Route::get('contact_person/getrecord', [Contact_PersonController::class, 'getrecords']);
    Route::post('contact_person/store', [Contact_PersonController::class, 'store']);
    Route::get('contact_person/edit/{id}', [Contact_PersonController::class, 'edit']);
    Route::patch('contact_person/update/{id}', [Contact_PersonController::class, 'update']);
    Route::get('contact_person/destroy/{id}', [Contact_PersonController::class, 'destroy']);

    //Offices
    Route::get('/office', [OfficeController::class, 'index']);
    Route::get('office/getrecord', [OfficeController::class, 'getrecords']);
    Route::post('office/store', [OfficeController::class, 'store']);
    Route::get('office/edit/{id}', [OfficeController::class, 'edit']);
    Route::patch('office/update/{id}', [OfficeController::class, 'update']);
    Route::get('office/destroy/{id}', [OfficeController::class, 'destroy']);

    //Requests
    Route::get('/request_app', [Request_AppController::class, 'index']);
    Route::get('request_app/getrecord', [Request_AppController::class, 'getrecords']);

}); 


