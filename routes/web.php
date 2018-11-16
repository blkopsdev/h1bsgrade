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

Route::get('/', 'HomeController@show')->name('/');
Route::post('/', 'HomeController@formsubmit')->name('formsubmit');
Route::get('/{e}/{j}/{c}/{y}', 'HomeController@index')->name('/');

Route::get('autocomplete-employer', 'HomeController@autocompleteEmployer')->name('autocomplete-employer');
Route::get('autocomplete-job-title', 'HomeController@autocompleteJobTitle');
Route::get('autocomplete-city', 'HomeController@autocompleteCity');

Route::get('topcompanies', 'TopCompaniesController@index')->name('topcompanies');
Route::get('topjobs', 'TopJobsController@index')->name('topjobs');
Route::get('topcities', 'TopCitiesController@index')->name('topcities');
Route::get('highestpaidjob', 'HighestPaidJobController@index')->name('highestpaidjob');
Route::get('highestpaidcompany', 'HighestPaidCompanyController@index')->name('highestpaidcompany');
Route::get('highestpaidcity', 'HighestPaidCityController@index')->name('highestpaidcity');
Route::get('listlca/{company}/{job?}', 'ListLcaController@index')->name('listlca');

Route::post('/clickReveal','AjaxController@index');
