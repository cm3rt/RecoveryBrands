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

Route::get('/', 'MeetingController@saveApiData');
//Route::get('/state/{state}/city/{city}', 'MeetingController@saveApiData')->where('name', '[A-Za-z]+');
Route::get('/city/{city}/state/{state}/address/{address}/day/{day?}', function ($city, $state, $address, $day="") {
    $loc = new \RecoveryBrands\LocationManager();
    $finder = new \RecoveryBrands\MeetingFinder();
    $mtgMgr = new \RecoveryBrands\MeetingManager($loc, $finder, $city, $state, $day, $address);
    $array = $mtgMgr->showSortedMeetings(true);
    return view('meetings')->with(["meetings" => $array, 'city' => $city,
    'state'=> $state, 'day' => $day, 'address'=>$address]);;
})->where('state', '[A-Za-z]+');