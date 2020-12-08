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

/** Admin Controoler */
Route::namespace('Admin')->prefix('admin')->middleware(['can:admin'])->group(function(){
    Route::get('/home', 'HomeController@index')->name('admin.dashboard');
    Route::get('/profile', 'ProfileController@index')->name('admin.profile');
    Route::get('/admin_list', 'UserController@getAdminPage')->name('admin.admin_list');
    Route::get('/staff_list', 'UserController@getStaffPage')->name('admin.staff_list');
    Route::get('/user_list', 'UserController@getUserPage')->name('admin.user_list');
    Route::get('/data', 'DataController@index')->name('admin.data');
    Route::get('/charts', 'ChartController@index')->name('admin.chart');
    Route::get('/faq', 'FaqController@index')->name('admin.faq');

    

    // Manage User 
    Route::post('/store/user', 'UserController@store')->name('store_user');
    Route::post('/update/user/{id}', 'UserController@update')->name('update_user');
    Route::post('/destroy/user/{id}', 'UserController@destroy')->name('destroy_user');
    Route::post('activate/user', 'UserController@activateSelected')->name('activeSelectedUser');
    Route::post('activateAll/user', 'UserController@activateAll')->name('activeAllUser');

    // Manage Chart
    // Route::post('activateAll/user', 'UserController@activateAll')->name('activeAllUser');

    // Manage FAQ
    Route::post('/store/faq', 'FAQController@store')->name('store_faq');
    Route::post('/update/faq/{id}', 'FAQController@update')->name('update_faq');
    Route::post('/destroy/faq/{id}', 'FAQController@destroy')->name('destroy_faq');
    Route::post('/restore/faq/{id}', 'FAQController@restore')->name('restore_faq');


});

/** Top Nav Link */
Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');
Route::get('data', 'DataController@index')->name('data.index');
Route::get('climatic', 'ClimaticController@index')->name('climatic');
Route::get('analytics', 'AnalyticsController@index')->name('analytics');
Route::get('faq', 'FAQController@index')->name('faqs');


/** Data Controller */
Route::get('getDailyData', 'DataController@getDailyData')->name('data.getDailyData');

/** Climatic Controller */


/** FAQ Controller */
Route::post('togglelike', 'FAQController@toggleLike')->name('faq.toggleLike');
Route::post('toggledislike', 'FAQController@toggleDislike')->name('faq.toggleDislike');


// /** Users Controller */
// Route::get('users', 'UsersController@index')->name('users.index');
// Route::post('users', 'UsersController@store')->name('users.store');
// Route::put('users/update/{id}', 'ProfileController@update');
// Route::delete('users/{id}', 'ProfileController@destroy');

// /** Users Controller @show */
// Route::get('/users/id={id}', 'UsersController@show')->name('users.show');

/** District Controller @show*/
Route::get('/data/year={year}&state={state}&district={district}', 'DistrictController@index')->name('district.index');

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
    Route::get('/getGenderChart', 'DataController@getGenderChart')->name('state.getGenderChart');
    Route::get('/getDailyChart', 'DataController@getDailyChart')->name('state.getDailyChart');
    
});

// Graphical data : District
Route::prefix('district')->group(function () {
    Route::get('/getLocalityChart', 'DistrictController@getLocalityChart')->name('district.getLocalityChart');
    Route::get('/getDailyChart', 'DistrictController@getDailyChart')->name('district.getDailyChart');
    Route::get('/getMonthlyChart', 'DistrictController@getMonthlyChart')->name('district.getMonthlyChart');
    
});

// Analytics Controller
Route::prefix('analytics')->group(function () {
    Route::get('/getDistrict', 'AnalyticsController@getDistrict')->name('anaytics.getDistrict');
    Route::post('/getAnalyticsResult', 'AnalyticsController@getAnalyticsResult')->name('analytics.getAnalyticsResult');

    
});

// Route::get('getSession', function(){return dd(Session::all());});

Auth::routes();