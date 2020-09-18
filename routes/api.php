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
Route::post('auth/register', 'UserController@register');
Route::post('auth/login', 'UserController@authenticate');
Route::get('open', 'DataController@open');

Route::group(['middleware' => ['jwt.verify']], function() {
    // Generate Cv and Download it
    //
    Route::post('user/generate-resume','CvController@store');
    Route::get('user/resumes','CvController@index');
    Route::get('user/resumes/{id}',"CvController@createPDF");
    // Personal Infos routes
    //
    Route::post('user/personal-infos','PersonalInfoController@store');
    Route::get('user/personal-infos','PersonalInfoController@index');
    Route::get('user/personal-infos/{id}','PersonalInfoController@show');
    Route::put('user/personal-infos/{id}','PersonalInfoController@update');
    Route::delete('user/personal-infos/{id}','PersonalInfoController@destroy');

    // Experience routes
    //
    Route::post('user/experiences','ExperienceController@store');
    Route::get('user/experiences','ExperienceController@index');
    Route::get('user/experiences/{id}','ExperienceController@show');
    Route::put('user/experiences/{id}','ExperienceController@update');
    Route::delete('user/experiences/{id}','ExperienceController@destroy');
    
    //Formation Routes 
    //
    Route::post('user/formations','FormationController@store');
    Route::get('user/formations','FormationController@index');
    Route::get('user/formations/{id}','FormationController@show');
    Route::put('user/formations/{id}','FormationController@update');
    Route::delete('user/formations/{id}','FormationController@destroy');
    
    //Langue Routes 
    //
    Route::post('user/langues','LangueController@store');
    Route::get('user/langues','LangueController@index');
    Route::get('user/langues/{id}','LangueController@show');
    Route::put('user/langues/{id}','LangueController@update');
    Route::delete('user/langues/{id}','LangueController@destroy');
    
    //Competence Routes 
    //
    Route::post('user/competences','CompetenceController@store');
    Route::get('user/competences','CompetenceController@index');
    Route::get('user/competences/{id}','CompetenceController@show');
    Route::put('user/competences/{id}','CompetenceController@update');
    Route::delete('user/competences/{id}','CompetenceController@destroy');
    Route::get('user/competences-type','CompetenceController@get_types');

    //test routes
    //
    Route::get('me', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');
    Route::get('auth/logout','UserController@logout');
});