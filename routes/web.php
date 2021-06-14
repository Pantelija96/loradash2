<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/','FrontEndController@index');
Route::post('/login','BackEndController@login')->name('loginroute');
Route::get('/logout','BackEndController@logout');

Route::get('/home','FrontEndController@dashboard');

Route::get('/changepassword','FrontEndController@changePassword');
Route::post('/passwordchange','BackEndController@changePassword')->name('passwordchange');


Route::get('/allusers','FrontEndController@showAllUsers');
Route::get('/adduser','FrontEndController@addUser');
Route::post('/adduser','BackEndController@addUser')->name('addUser');
Route::get('/useredit/{id}','FrontEndController@showEditUser');


Route::get('/allsensors','FrontEndController@showAllSensors');
Route::get('/addsensor','FrontEndController@addSensor');
Route::post('/addsensor','BackEndController@addSensor')->name('addSensor');


Route::get('/addservice','FrontEndController@addService');




Route::group(['prefix'=> '/ajax'], function(){
  Route::get('/finduser/{userId}','BackEndController@findUser');
});
