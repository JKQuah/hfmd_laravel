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

/** Login Controller */
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

// Route::get('/admin/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/admin/analytics', 'AnalyticsController@index')->name('analytics');
Route::get('/admin/news', 'NewsController@index')->name('news');

/** Data Controller */
Route::get('/admin/data', 'DataController@index')->name('data.index');

/** Profile Controller */
Route::get('/admin/profile', 'ProfileController@index')->name('profile.index');
Route::post('/admin/profile', 'ProfileController@store')->name('profile.store');
Route::put('/admin/profile/update/{id}', 'ProfileController@update');
Route::delete('/admin/profile/{id}', 'ProfileController@destroy');



/** Profile Controller @show */
Route::get('/admin/profile/id={id}', 'ProfileController@show')->name('profile.show');

/** District Controller @show*/
Route::get('/admin/data/year={year}&state={state}&district={district}', 'DistrictController@show')->name('district.show');

/** Data Controller @show*/
Route::get('/admin/data/year={year}&state={state}', 'DataController@show')->name('data.show');


Auth::routes();

