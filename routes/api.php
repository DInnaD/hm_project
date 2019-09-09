<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Route::group(['middleware' => ['auth:api']], function () {

//     Route::group(['prefix' => 'user'], function () {
//         Route::get('', 'UserController@index')->middleware(['isadmin']);
//         Route::get('/{user}', 'UserController@show')->middleware(['owner', 'isadmin']);
//         Route::put('/{user}', 'UserController@update')->middleware(['owner', 'isadmin']);
//         Route::delete('/{user}', 'UserController@destroy')->middleware(['owner', 'isadmin']);
//     });
    
//     Route::group(['prefix' => 'organization'], function () {
//         Route::get('', 'OrganizationController@index');
//         Route::get('/{organization}', 'OrganizationController@show')->middleware(['owner', 'isadmin']);
//         Route::post('', 'OrganizationController@store')->middleware(['isemployer']);
//         Route::put('/{organization}', 'OrganizationController@update')->middleware(['owner', 'isadmin']);
//         Route::delete('/{organization}', 'OrganizationController@destroy')->middleware(['owner', 'isadmin']);
//     });

//     Route::group(['prefix' => 'vacancy'], function () {
//         Route::get('', 'VacancyController@index');
//         Route::get('/{vacancy}/book', 'VacancyController@book');
//         Route::get('/{vacancy}/unbook', 'VacancyController@unbook');
//         Route::get('/{vacancy}', 'VacancyController@show');
//         Route::post('', 'VacancyController@store');
//         Route::put('/{vacancy}', 'VacancyController@update')->middleware(['owner', 'isadmin']);
//         Route::delete('/{vacancy}', 'VacancyController@destroy')->middleware(['owner', 'isadmin']);
//     });

//     Route::group(['prefix' => 'stats', 'middleware' => ['isadmin']], function () {
//         Route::get('/user', 'StatsController@users');
//         Route::get('/vacancy', 'StatsController@vacancies');
//         Route::get('/organization', 'StatsController@organization');
//     });
// });

Route::resource('user', 'UserController')->only(['index', 'show', 'update', 'destroy']);
