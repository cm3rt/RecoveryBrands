<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function (){
return "Welcome";
});
//Route::get('/state/{state}/city/{city}', 'MeetingController@saveApiData')->where('name', '[A-Za-z]+');
Route::get('/city/{city}/state/{state}/address/{address}/day/{day?}', 'MeetingController@startApi')->where('state', '[A-Za-z]+');