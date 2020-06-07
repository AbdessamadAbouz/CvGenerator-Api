<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::get('open', 'DataController@open');

Route::group(['middleware' => ['jwt.verify']], function() {
    // Personal Infos routes
    //
    Route::post('user/personal-infos/add','PersonalInfoController@store');
    Route::get('user/personal-infos','PersonalInfoController@index');
    Route::get('user/personal-infos/{id}','PersonalInfoController@show');
    Route::put('user/personal-infos/{id}','PersonalInfoController@update');
    Route::delete('user/personal-infos/{id}','PersonalInfoController@destroy');

    // Experience routes
    //
    Route::post('user/experiences/add','ExperienceController@store');
    Route::get('user/experiences','ExperienceController@index');
    Route::get('user/experiences/{id}','ExperienceController@show');
    Route::put('user/experiences/{id}','ExperienceController@update');
    Route::delete('user/experiences/{id}','ExperienceController@destroy');
    
    //test routes
    //
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');
});