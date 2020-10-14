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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/** Login Controller */
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

Route::namespace('Admin')->prefix('admin')->middleware(['can:admin'])->group(function(){
    Route::get('/home', 'HomeController@index')->name('admin.dashboard');
    Route::get('/profile', 'ProfileController@index')->name('admin.profile');
    Route::get('/admin_list', 'UserController@getAdminPage')->name('admin.admin_list');
    Route::get('/staff_list', 'UserController@getStaffPage')->name('admin.staff_list');
    Route::get('/user_list', 'UserController@getUserPage')->name('admin.user_list');
    Route::get('/data', 'DataController@index')->name('admin.data');

    Route::post('/update/user/{id}', 'UserController@update')->name('update_user');
    Route::post('/destroy/user/{id}', 'UserController@destroy')->name('destroy_user');
    Route::post('/store/user', 'UserController@store')->name('store_user');
    Route::post('activate/user', 'UserController@activateSelected')->name('activeSelectedUser');
    Route::post('activateAll/user', 'UserController@activateAll')->name('activeAllUser');

});

Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');
Route::get('analytics', 'AnalyticsController@index')->name('analytics');
Route::get('news', 'NewsController@index')->name('news');

/** Data Controller */
Route::get('data', 'DataController@index')->name('data.index');

/** Users Controller */
Route::get('users', 'UsersController@index')->name('users.index');
Route::post('users', 'UsersController@store')->name('users.store');
Route::put('users/update/{id}', 'ProfileController@update');
Route::delete('users/{id}', 'ProfileController@destroy');

/** Users Controller @show */
Route::get('/users/id={id}', 'UsersController@show')->name('users.show');

/** District Controller @show*/
Route::get('/data/year={year}&state={state}&district={district}', 'DistrictController@show')->name('district.show');

/** Data Controller @show*/
Route::get('/data/year={year}&state={state}', 'DataController@show')->name('data.show');


// Graphical data : Dashboard
Route::prefix('dashboard')->group(function () {
    Route::get('/getOverallChart', 'DashboardController@getLineChart')->name('overview.getOverallChart');
    Route::get('/getAgeChart', 'DashboardController@getAgeChart')->name('overview.getAgeChart');
});

// Graphical data : States
Route::prefix('state')->group(function () {
    Route::get('/getLocalityChart', 'DataController@getLocalityChart')->name('state.getLocalityChart');
});

// Route::get('getSession', function(){return dd(Session::all());});

Auth::routes();
