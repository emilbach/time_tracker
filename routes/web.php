<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/overview', 'HomeController@overview')->name('overview');
Route::post('/get_tasks', 'HomeController@getSavedTasks')->name('get_tasks');
Route::post('/save_task', 'HomeController@saveTask')->name('save_task');
Route::post('/delete_task', 'HomeController@deleteTask')->name('deleteTask');
